<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QwenVLService
{
    public function selectBestImage(array $imagePaths, string $productName): array
    {
        if (empty($imagePaths)) {
            throw new Model3DGenerationException('No images provided to QwenVLService.');
        }

        $token = config('model3d.hf_token');
        $space = config('model3d.qwen3_space');
        $maxRetries = config('model3d.max_retries');

        if (! $token) {
            return $this->fallback($imagePaths, $productName, 'HF_API_TOKEN not configured');
        }

        $count = count($imagePaths);
        $primaryImage = $imagePaths[0];

        if (! file_exists($primaryImage)) {
            throw new Model3DGenerationException("Image not found: {$primaryImage}");
        }

        $mime = mime_content_type($primaryImage) ?: 'image/jpeg';
        $base64 = base64_encode(file_get_contents($primaryImage));
        $dataUrl = "data:{$mime};base64,{$base64}";

        $prompt = "You are analyzing product images for 3D model generation. "
            ."Given these {$count} product images for '{$productName}', "
            ."select the BEST single image for 3D reconstruction. "
            ."The best image has: single object centered, clean/white background preferred, "
            ."clear geometry visible, no extreme angles. "
            ."Return JSON only: "
            .'{"selected_index": 0, "description": "...detailed material and geometry description...", "confidence": 0.0, "reason": "..."}';

        $attempt = 0;
        $delay = 1;

        while ($attempt < $maxRetries) {
            $attempt++;

            try {
                $response = Http::withToken($token)
                    ->timeout(60)
                    ->acceptJson()
                    ->post('https://api-inference.huggingface.co/v1/chat/completions', [
                        'model'      => $space,
                        'messages'   => [
                            [
                                'role'    => 'user',
                                'content' => [
                                    ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]],
                                    ['type' => 'text', 'text' => $prompt],
                                ],
                            ],
                        ],
                        'max_tokens' => 512,
                    ]);

                if ($response->status() === 429) {
                    sleep($delay);
                    $delay *= 2;
                    continue;
                }

                if ($response->status() === 503) {
                    sleep(10);
                    continue;
                }

                if (! $response->successful()) {
                    Log::warning('Qwen VL API error', [
                        'status' => $response->status(),
                        'body'   => substr($response->body(), 0, 500),
                    ]);
                    return $this->fallback($imagePaths, $productName, 'Qwen API returned '.$response->status());
                }

                $text = $response->json('choices.0.message.content');

                if (! $text) {
                    return $this->fallback($imagePaths, $productName, 'Empty response from Qwen');
                }

                $parsed = $this->parseJson($text, $count);

                if ($parsed === null) {
                    return $this->fallback($imagePaths, $productName, 'Invalid JSON from Qwen');
                }

                $idx = max(0, min($count - 1, (int) $parsed['selected_index']));

                return [
                    'selected_image' => $imagePaths[$idx],
                    'description'    => $parsed['description'] ?: $productName,
                    'confidence'     => (float) ($parsed['confidence'] ?? 0.5),
                    'reason'         => $parsed['reason'] ?? '',
                ];

            } catch (\Throwable $e) {
                Log::warning('Qwen VL request failed', ['error' => $e->getMessage()]);
                if ($attempt >= $maxRetries) {
                    return $this->fallback($imagePaths, $productName, $e->getMessage());
                }
                sleep($delay);
                $delay *= 2;
            }
        }

        return $this->fallback($imagePaths, $productName, 'Max retries exceeded');
    }

    private function parseJson(string $text, int $count): ?array
    {
        if (preg_match('/\{.*\}/s', $text, $m)) {
            $json = json_decode($m[0], true);
            if (is_array($json) && isset($json['selected_index'])) {
                return $json;
            }
        }

        return null;
    }

    private function fallback(array $imagePaths, string $productName, string $reason): array
    {
        Log::info('Qwen fallback triggered', ['reason' => $reason]);

        return [
            'selected_image' => $imagePaths[0],
            'description'    => $productName,
            'confidence'     => 0.0,
            'reason'         => 'fallback: '.$reason,
        ];
    }
}

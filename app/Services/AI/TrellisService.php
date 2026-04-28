<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use App\Exceptions\Model3DTimeoutException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * TRELLIS via Replicate hosted API.
 *
 * Flow:
 *   1. POST /v1/predictions with Prefer: wait=60 (blocks until done or 60s elapse)
 *   2. If still processing, poll GET /v1/predictions/{id} every 3s
 *   3. Download the resulting .glb to storage/app/public/models/{id}/model.glb
 */
class TrellisService
{
    private const API = 'https://api.replicate.com/v1/predictions';

    public function generateModel(string $imagePath, string $description, int $productId): string
    {
        if (! file_exists($imagePath)) {
            throw new Model3DGenerationException("Selected image not found: {$imagePath}");
        }

        $token = config('model3d.replicate_token');
        $timeout = (int) config('model3d.timeout', 300);
        $maxRetries = (int) config('model3d.max_retries', 3);
        $provider = config('model3d.provider', 'trellis');

        if (! $token) {
            throw new Model3DGenerationException('REPLICATE_API_TOKEN not configured.');
        }

        [$version, $input] = $this->resolveProvider($provider, $imagePath);

        if (! $version) {
            throw new Model3DGenerationException("Provider '{$provider}' has no version configured. Set the matching *_VERSION env var.");
        }

        Log::info('3D generation provider', ['provider' => $provider, 'version' => $version]);

        $attempt = 0;
        $lastError = null;

        while ($attempt < $maxRetries) {
            $attempt++;

            try {
                $glbUrl = $this->runPrediction($token, $version, $input, $timeout);
                return $this->saveGlb($glbUrl, $productId, $timeout);
            } catch (Model3DTimeoutException $e) {
                throw $e;
            } catch (\Throwable $e) {
                $lastError = $e;
                Log::warning('Replicate 3D attempt failed', [
                    'provider' => $provider,
                    'attempt'  => $attempt,
                    'error'    => $e->getMessage(),
                ]);
                if (str_contains($e->getMessage(), 'insufficient credit')) {
                    throw $e;
                }
                if ($attempt >= $maxRetries) {
                    break;
                }
                sleep(2 ** $attempt);
            }
        }

        throw new Model3DGenerationException(
            'Trellis generation failed: '.($lastError ? $lastError->getMessage() : 'unknown error')
        );
    }

    private function resolveProvider(string $provider, string $imagePath): array
    {
        $mime = mime_content_type($imagePath) ?: 'image/jpeg';
        $dataUrl = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($imagePath));

        switch ($provider) {
            case 'hunyuan':
                return [
                    config('model3d.hunyuan_version'),
                    [
                        'image'         => $dataUrl,
                        'enable_pbr'    => true,
                        'face_count'    => 500000,
                        'generate_type' => 'Normal',
                    ],
                ];

            case 'rodin':
                return [
                    config('model3d.rodin_version'),
                    [
                        'images'               => [$dataUrl],
                        'tier'                 => 'Gen-2',
                        'quality'              => 'medium',
                        'material'             => 'PBR',
                        'mesh_mode'            => 'Quad',
                        'geometry_file_format' => 'glb',
                    ],
                ];

            case 'trellis2':
                return [
                    config('model3d.trellis2_version'),
                    [
                        'image'                => $dataUrl,
                        'texture_size'         => 4096,
                        'pipeline_type'        => '1024_cascade',
                        'generate_model'       => true,
                        'generate_video'       => false,
                        'preprocess_image'     => true,
                        'return_no_background' => true,
                        'randomize_seed'       => true,
                    ],
                ];

            case 'trellis':
            default:
                return [
                    config('model3d.replicate_version'),
                    [
                        'images'                 => [$dataUrl],
                        'generate_model'         => true,
                        'generate_color'         => true,
                        'generate_normal'        => false,
                        'save_gaussian_ply'      => false,
                        'return_no_background'   => true,
                        'mesh_simplify'          => 0.95,
                        'texture_size'           => 2048,
                        'ss_sampling_steps'      => 25,
                        'slat_sampling_steps'    => 25,
                        'ss_guidance_strength'   => 7.5,
                        'slat_guidance_strength' => 3.0,
                        'randomize_seed'         => true,
                    ],
                ];
        }
    }

    private function runPrediction(string $token, string $version, array $input, int $timeout): string
    {
        $deadline = microtime(true) + $timeout;

        $response = Http::withToken($token)
            ->withHeaders([
                'Prefer'       => 'wait=60',
                'Content-Type' => 'application/json',
            ])
            ->timeout(70)
            ->post(self::API, [
                'version' => $version,
                'input'   => $input,
            ]);

        if ($response->status() === 402) {
            throw new Model3DGenerationException(
                'Replicate: insufficient credit. Add a payment method at https://replicate.com/account/billing then retry.'
            );
        }

        if ($response->status() === 429) {
            $retryAfter = (int) ($response->json('retry_after') ?? 10);
            Log::info('Replicate rate limited, waiting', ['seconds' => $retryAfter]);
            sleep(min($retryAfter + 1, 30));
            throw new Model3DGenerationException('Replicate rate limited (429); will retry.');
        }

        if (! $response->successful()) {
            throw new Model3DGenerationException(
                "Replicate create prediction failed: {$response->status()} {$response->body()}"
            );
        }

        $prediction = $response->json();
        $id = $prediction['id'] ?? null;
        $status = $prediction['status'] ?? null;

        if (! $id) {
            throw new Model3DGenerationException('Replicate response missing prediction id.');
        }

        Log::debug('Replicate prediction started', ['id' => $id, 'status' => $status]);

        while (! in_array($status, ['succeeded', 'failed', 'canceled'], true)) {
            if (microtime(true) > $deadline) {
                throw new Model3DTimeoutException("Trellis generation timed out after {$timeout}s");
            }

            usleep(3_000_000);

            $poll = Http::withToken($token)
                ->timeout(30)
                ->get(self::API."/{$id}");

            if (! $poll->successful()) {
                throw new Model3DGenerationException(
                    "Replicate poll failed: {$poll->status()} {$poll->body()}"
                );
            }

            $prediction = $poll->json();
            $status = $prediction['status'] ?? null;
            Log::debug('Replicate poll', ['id' => $id, 'status' => $status]);
        }

        if ($status !== 'succeeded') {
            $err = $prediction['error'] ?? 'no error message';
            throw new Model3DGenerationException("Replicate prediction {$status}: ".(is_string($err) ? $err : json_encode($err)));
        }

        return $this->extractGlbUrl($prediction['output'] ?? null);
    }

    private function extractGlbUrl(mixed $output): string
    {
        // Hunyuan/Rodin return a plain string URL (may not contain .glb in URL path).
        if (is_string($output) && (str_contains(strtolower($output), '.glb') || preg_match('#^https?://#', $output))) {
            return $output;
        }

        if (is_array($output)) {
            foreach (['model_file', 'glb', 'model', 'mesh', 'output', 'mesh_url', 'glb_url'] as $key) {
                if (isset($output[$key]) && is_string($output[$key]) && (str_contains(strtolower($output[$key]), '.glb') || preg_match('#^https?://#', $output[$key]))) {
                    return $output[$key];
                }
            }
            foreach ($output as $val) {
                if (is_string($val) && str_contains(strtolower($val), '.glb')) {
                    return $val;
                }
            }
        }

        throw new Model3DGenerationException('Replicate output did not contain a .glb URL: '.json_encode($output));
    }

    private function saveGlb(string $glbUrl, int $productId, int $timeout): string
    {
        $response = Http::timeout($timeout)->get($glbUrl);

        if (! $response->successful()) {
            throw new Model3DGenerationException("Failed to download .glb: {$response->status()}");
        }

        $bytes = $response->body();
        if (strlen($bytes) < 100 || substr($bytes, 0, 4) !== 'glTF') {
            throw new Model3DGenerationException('Downloaded file is not a valid .glb.');
        }

        $filename = 'model.glb';
        Storage::disk('public')->put("models/{$productId}/{$filename}", $bytes);

        return $filename;
    }
}

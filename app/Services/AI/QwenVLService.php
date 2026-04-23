<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class QwenVLService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey  = config('model3d.vl_provider.api_key', '');
        $this->model   = config('model3d.vl_provider.model', 'gpt-4o');
        $this->baseUrl = config('model3d.vl_provider.base_url', 'https://api.openai.com/v1');
        $this->timeout = config('model3d.vl_provider.timeout', 60);
    }

    /**
     * Analyse an image and return a rich text description suitable for 3D generation.
     *
     * @param  string  $imageUrl  Publicly accessible URL or base64 data-URI
     * @param  string  $prompt    Additional instruction passed to the model
     * @return string
     */
    public function describeImage(string $imageUrl, string $prompt = ''): string
    {
        $systemPrompt = 'You are a precise 3D-modelling assistant. '
            . 'Describe the object in the image in detail: shape, geometry, materials, '
            . 'colours, textures, and any distinguishing features. '
            . 'Focus only on physical attributes relevant to recreating a 3D model.';

        $userContent = [
            [
                'type'      => 'image_url',
                'image_url' => ['url' => $imageUrl, 'detail' => 'high'],
            ],
            [
                'type' => 'text',
                'text' => $prompt ?: 'Describe this object for 3D reconstruction.',
            ],
        ];

        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->post('/chat/completions', [
                'model'      => $this->model,
                'messages'   => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userContent],
                ],
                'max_tokens' => 1024,
            ]);

        if ($response->failed()) {
            $status = $response->status();
            $body   = $response->body();
            Log::error('VL provider request failed', ['status' => $status, 'body' => $body]);
            throw new RuntimeException("VL provider returned HTTP {$status}: {$body}");
        }

        $description = $response->json('choices.0.message.content');

        if (empty($description)) {
            throw new RuntimeException('VL provider returned an empty description.');
        }

        return trim($description);
    }

    /**
     * Extract structured tags (shape, material, colour) from an image.
     *
     * @param  string  $imageUrl
     * @return array{shape: string, material: string, colour: string, tags: string[]}
     */
    public function extractTags(string $imageUrl): array
    {
        $userContent = [
            [
                'type'      => 'image_url',
                'image_url' => ['url' => $imageUrl, 'detail' => 'high'],
            ],
            [
                'type' => 'text',
                'text' => 'Return a JSON object with keys: shape (string), material (string), '
                    . 'colour (string), tags (array of strings). No markdown, raw JSON only.',
            ],
        ];

        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->post('/chat/completions', [
                'model'           => $this->model,
                'messages'        => [
                    ['role' => 'user', 'content' => $userContent],
                ],
                'max_tokens'      => 512,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            Log::error('VL tag extraction failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('VL tag extraction failed: HTTP ' . $response->status());
        }

        $raw = $response->json('choices.0.message.content', '{}');
        $data = json_decode($raw, true) ?? [];

        return [
            'shape'    => $data['shape']    ?? 'unknown',
            'material' => $data['material'] ?? 'unknown',
            'colour'   => $data['colour']   ?? 'unknown',
            'tags'     => $data['tags']     ?? [],
        ];
    }
}

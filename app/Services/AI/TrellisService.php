<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TrellisService
{
    private string $apiKey;
    private string $baseUrl;
    private int    $timeout;
    private int    $pollInterval;
    private int    $maxPollAttempts;

    public function __construct()
    {
        $this->apiKey          = config('model3d.trellis.api_key', '');
        $this->baseUrl         = config('model3d.trellis.base_url', 'https://api.trellis3d.ai/v1');
        $this->timeout         = config('model3d.trellis.timeout', 120);
        $this->pollInterval    = config('model3d.trellis.poll_interval', 5);
        $this->maxPollAttempts = config('model3d.trellis.max_poll_attempts', 60);
    }

    /**
     * Submit a 3D generation job from a text prompt.
     *
     * @return string Job ID
     */
    public function generateFromText(string $prompt, array $options = []): string
    {
        $payload = array_merge([
            'prompt'          => $prompt,
            'output_format'   => config('model3d.trellis.output_format', 'glb'),
            'mesh_simplify'   => config('model3d.trellis.mesh_simplify', 0.95),
            'texture_size'    => config('model3d.trellis.texture_size', 1024),
        ], $options);

        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->post('/generations/text-to-3d', $payload);

        if ($response->failed()) {
            Log::error('Trellis generation submission failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('Trellis submission failed: HTTP ' . $response->status());
        }

        $jobId = $response->json('job_id') ?? $response->json('id');

        if (empty($jobId)) {
            throw new RuntimeException('Trellis did not return a job ID.');
        }

        Log::info('Trellis job submitted', ['job_id' => $jobId]);

        return (string) $jobId;
    }

    /**
     * Submit a 3D generation job from an image URL.
     *
     * @return string Job ID
     */
    public function generateFromImage(string $imageUrl, array $options = []): string
    {
        $payload = array_merge([
            'image_url'     => $imageUrl,
            'output_format' => config('model3d.trellis.output_format', 'glb'),
            'mesh_simplify' => config('model3d.trellis.mesh_simplify', 0.95),
            'texture_size'  => config('model3d.trellis.texture_size', 1024),
        ], $options);

        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->post('/generations/image-to-3d', $payload);

        if ($response->failed()) {
            Log::error('Trellis image-to-3D submission failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('Trellis image-to-3D failed: HTTP ' . $response->status());
        }

        $jobId = $response->json('job_id') ?? $response->json('id');

        if (empty($jobId)) {
            throw new RuntimeException('Trellis did not return a job ID.');
        }

        Log::info('Trellis image-to-3D job submitted', ['job_id' => $jobId]);

        return (string) $jobId;
    }

    /**
     * Poll until the job completes and return the download URL of the 3D asset.
     *
     * @throws RuntimeException on failure or timeout
     */
    public function waitForResult(string $jobId): string
    {
        $attempts = 0;

        while ($attempts < $this->maxPollAttempts) {
            $status = $this->getJobStatus($jobId);

            match ($status['status']) {
                'succeeded', 'completed' => (function () use ($status, $jobId) {
                    $url = $status['output_url'] ?? $status['result_url'] ?? null;
                    if (empty($url)) {
                        throw new RuntimeException("Trellis job {$jobId} completed but no output URL found.");
                    }
                    // Return handled below after match
                })(),
                'failed', 'error' => throw new RuntimeException(
                    "Trellis job {$jobId} failed: " . ($status['error'] ?? 'unknown error')
                ),
                default => null,
            };

            if (in_array($status['status'], ['succeeded', 'completed'], true)) {
                return $status['output_url'] ?? $status['result_url'];
            }

            Log::debug('Trellis job pending', ['job_id' => $jobId, 'status' => $status['status']]);
            sleep($this->pollInterval);
            $attempts++;
        }

        throw new RuntimeException("Trellis job {$jobId} timed out after {$attempts} polling attempts.");
    }

    /**
     * Retrieve the raw status payload for a job.
     */
    public function getJobStatus(string $jobId): array
    {
        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->timeout(30)
            ->get("/generations/{$jobId}");

        if ($response->failed()) {
            throw new RuntimeException("Trellis status check failed for job {$jobId}: HTTP " . $response->status());
        }

        return $response->json();
    }
}

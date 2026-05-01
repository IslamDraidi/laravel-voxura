<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use App\Exceptions\Model3DTimeoutException;
use App\Services\AI\Contracts\TryOnBodyProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * SAM 3D Body via fal.ai REST API.
 *
 * Flow:
 *   1. POST https://queue.fal.run/{endpoint} with {image_url: data:URI}.
 *   2. Poll status_url until COMPLETED (or timeout).
 *   3. GET response_url, extract model_glb URL.
 *   4. Download the GLB into storage/app/public/bodies/{user_id}/...
 */
class Sam3DBodyFalService implements TryOnBodyProvider
{
    private const QUEUE_BASE = 'https://queue.fal.run/';

    public function generateBodyModel(string $photoAbsPath, ?int $heightCm, int $userId): string
    {
        if (! file_exists($photoAbsPath)) {
            throw new Model3DGenerationException("Photo not found at: {$photoAbsPath}");
        }

        $key = config('model3d.tryon.fal_key');
        if (! $key) {
            throw new Model3DGenerationException('Try-on service is misconfigured. Please contact support.');
        }

        $endpoint = trim((string) config('model3d.tryon.fal_endpoint'), '/');
        $timeout = (int) config('model3d.tryon.request_timeout', 300);
        $maxRetries = (int) config('model3d.tryon.max_retries', 3);

        $mime = mime_content_type($photoAbsPath) ?: 'image/jpeg';
        $dataUri = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($photoAbsPath));

        $payload = ['image_url' => $dataUri];
        if ($heightCm !== null) {
            $payload['height_cm'] = $heightCm;
        }

        $attempt = 0;
        $lastError = null;

        while ($attempt < $maxRetries) {
            $attempt++;

            try {
                $glbUrl = $this->runRequest($key, $endpoint, $payload, $timeout);
                return $this->saveGlb($glbUrl, $userId, $timeout);
            } catch (Model3DTimeoutException $e) {
                throw $e;
            } catch (Model3DGenerationException $e) {
                $lastError = $e;
                $msg = $e->getMessage();
                Log::warning('SAM3D body attempt failed', [
                    'attempt' => $attempt,
                    'error'   => $msg,
                ]);

                if (str_contains($msg, 'misconfigured')
                    || str_contains($msg, '401')
                    || str_contains($msg, '403')) {
                    throw $e;
                }

                if ($attempt >= $maxRetries) {
                    break;
                }
                sleep(min(2 ** $attempt, 30));
            }
        }

        throw new Model3DGenerationException(
            'SAM3D body generation failed: '.($lastError ? $lastError->getMessage() : 'unknown error')
        );
    }

    private function runRequest(string $key, string $endpoint, array $payload, int $timeout): string
    {
        $deadline = microtime(true) + $timeout;
        $submitUrl = self::QUEUE_BASE.$endpoint;

        $submit = Http::withHeaders([
                'Authorization' => "Key {$key}",
                'Content-Type'  => 'application/json',
            ])
            ->timeout(60)
            ->post($submitUrl, $payload);

        if ($submit->status() === 401 || $submit->status() === 403) {
            Log::error('SAM3D body auth rejected by fal.ai', [
                'status'      => $submit->status(),
                'body'        => $submit->body(),
                'submit_url'  => $submitUrl,
                'key_prefix'  => substr($key, 0, 8).'...',
                'key_length'  => strlen($key),
            ]);
            throw new Model3DGenerationException('Try-on service is misconfigured. Please contact support.');
        }

        if ($submit->status() === 429) {
            $retryAfter = (int) ($submit->header('Retry-After') ?: 10);
            sleep(min($retryAfter + 1, 30));
            throw new Model3DGenerationException('fal.ai rate limited (429); will retry.');
        }

        if (! $submit->successful()) {
            throw new Model3DGenerationException(
                "fal.ai submit failed: {$submit->status()} {$submit->body()}"
            );
        }

        $body = $submit->json();
        $statusUrl = $body['status_url'] ?? null;
        $responseUrl = $body['response_url'] ?? null;

        if (! $statusUrl || ! $responseUrl) {
            throw new Model3DGenerationException(
                'fal.ai response missing status_url/response_url: '.json_encode($body)
            );
        }

        Log::debug('SAM3D body queued', ['request_id' => $body['request_id'] ?? null]);

        $sleep = 3;
        while (true) {
            if (microtime(true) > $deadline) {
                throw new Model3DTimeoutException("SAM3D body timed out after {$timeout}s");
            }

            sleep($sleep);
            $sleep = min($sleep + 1, 10);

            $poll = Http::withHeaders(['Authorization' => "Key {$key}"])
                ->timeout(30)
                ->get($statusUrl);

            if (! $poll->successful()) {
                throw new Model3DGenerationException(
                    "fal.ai poll failed: {$poll->status()} {$poll->body()}"
                );
            }

            $status = $poll->json('status');
            Log::debug('SAM3D body poll', ['status' => $status]);

            if ($status === 'COMPLETED') {
                break;
            }

            if (! in_array($status, ['IN_QUEUE', 'IN_PROGRESS'], true)) {
                throw new Model3DGenerationException(
                    "fal.ai job ended in unexpected state: ".json_encode($poll->json())
                );
            }
        }

        $result = Http::withHeaders(['Authorization' => "Key {$key}"])
            ->timeout(60)
            ->get($responseUrl);

        if (! $result->successful()) {
            throw new Model3DGenerationException(
                "fal.ai result fetch failed: {$result->status()} {$result->body()}"
            );
        }

        return $this->extractGlbUrl($result->json());
    }

    private function extractGlbUrl(mixed $output): string
    {
        if (is_array($output)) {
            // fal.ai sam-3/3d-body returns { model_glb: { url: "..." } | "..." }
            foreach (['model_glb', 'glb', 'mesh', 'model'] as $key) {
                if (! isset($output[$key])) {
                    continue;
                }
                $val = $output[$key];
                if (is_string($val) && preg_match('#^https?://#', $val)) {
                    return $val;
                }
                if (is_array($val) && isset($val['url']) && is_string($val['url'])) {
                    return $val['url'];
                }
            }
        }

        throw new Model3DGenerationException(
            'fal.ai response did not contain a model_glb URL: '.json_encode($output)
        );
    }

    private function saveGlb(string $glbUrl, int $userId, int $timeout): string
    {
        $download = Http::timeout($timeout)->get($glbUrl);

        if (! $download->successful()) {
            throw new Model3DGenerationException("Failed to download body GLB: {$download->status()}");
        }

        $bytes = $download->body();
        if (strlen($bytes) < 100 || substr($bytes, 0, 4) !== 'glTF') {
            throw new Model3DGenerationException('Downloaded body file is not a valid .glb.');
        }

        $dir = trim((string) config('model3d.tryon.body_storage', 'bodies'), '/');
        $relativePath = "{$dir}/{$userId}/body_".time().'.glb';

        Storage::disk('public')->put($relativePath, $bytes);

        return $relativePath;
    }
}

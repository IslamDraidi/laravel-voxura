<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;

class Model3DPipeline
{
    public function __construct(
        private readonly QwenVLService $vlService,
        private readonly TrellisService $trellisService,
    ) {}

    /**
     * Full pipeline: image → VL description → Trellis 3D job → output URL.
     *
     * Returns the public URL of the generated 3D asset (GLB/OBJ/etc.).
     */
    public function generateFromImage(string $imageUrl, array $options = []): string
    {
        Log::info('Model3DPipeline: starting image pipeline', ['image_url' => $imageUrl]);

        // Step 1 — use VL model to produce a rich text description
        $userPrompt  = $options['prompt'] ?? '';
        $description = $this->vlService->describeImage($imageUrl, $userPrompt);

        Log::info('Model3DPipeline: VL description obtained', [
            'description_length' => strlen($description),
        ]);

        // Step 2 — submit the description (and optionally the image) to Trellis
        $trellisOptions = $options['trellis'] ?? [];

        $useImageDirect = config('model3d.pipeline.image_to_3d_direct', false);

        $jobId = $useImageDirect
            ? $this->trellisService->generateFromImage($imageUrl, $trellisOptions)
            : $this->trellisService->generateFromText($description, $trellisOptions);

        Log::info('Model3DPipeline: Trellis job submitted', ['job_id' => $jobId]);

        // Step 3 — poll until the job is done
        $outputUrl = $this->trellisService->waitForResult($jobId);

        Log::info('Model3DPipeline: 3D asset ready', ['output_url' => $outputUrl]);

        return $outputUrl;
    }

    /**
     * Lighter pipeline: text prompt only → Trellis 3D job → output URL.
     */
    public function generateFromText(string $prompt, array $options = []): string
    {
        Log::info('Model3DPipeline: starting text pipeline', ['prompt' => $prompt]);

        $trellisOptions = $options['trellis'] ?? [];
        $jobId = $this->trellisService->generateFromText($prompt, $trellisOptions);

        Log::info('Model3DPipeline: Trellis job submitted', ['job_id' => $jobId]);

        $outputUrl = $this->trellisService->waitForResult($jobId);

        Log::info('Model3DPipeline: 3D asset ready', ['output_url' => $outputUrl]);

        return $outputUrl;
    }

    /**
     * Analyse an image and return VL tags without generating a 3D model.
     *
     * Useful for preflight checks or search-indexing workflows.
     */
    public function analyseImage(string $imageUrl): array
    {
        return $this->vlService->extractTags($imageUrl);
    }
}

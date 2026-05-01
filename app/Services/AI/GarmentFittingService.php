<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class GarmentFittingService
{
    /**
     * Run the Python fit_garment.py script to merge body + product into a single GLB.
     *
     * @return array  ['success' => true, 'output' => path, 'scale_applied' => float, 'category' => string]
     */
    public function fitGarment(string $bodyAbsPath, string $productAbsPath, string $outputAbsPath, string $category): array
    {
        if (! file_exists($bodyAbsPath)) {
            throw new Model3DGenerationException("Body GLB not found: {$bodyAbsPath}");
        }
        if (! file_exists($productAbsPath)) {
            throw new Model3DGenerationException("Product GLB not found: {$productAbsPath}");
        }

        $outputDir = dirname($outputAbsPath);
        if (! is_dir($outputDir) && ! @mkdir($outputDir, 0775, true) && ! is_dir($outputDir)) {
            throw new Model3DGenerationException("Could not create output dir: {$outputDir}");
        }

        $python = (string) config('model3d.tryon.python_path', 'python3');
        $script = (string) config('model3d.tryon.python_script');
        $timeout = (int) config('model3d.tryon.fit_timeout', 90);

        if (! file_exists($script)) {
            throw new Model3DGenerationException("Fitting script not found: {$script}");
        }

        $process = new Process([
            $python,
            $script,
            $bodyAbsPath,
            $productAbsPath,
            $outputAbsPath,
            $category,
        ]);
        $process->setTimeout($timeout);

        try {
            $process->run();
        } catch (ProcessTimedOutException $e) {
            throw new Model3DGenerationException("Garment fitting timed out after {$timeout}s");
        }

        $stdout = trim($process->getOutput());
        $stderr = trim($process->getErrorOutput());

        Log::debug('Garment fitting completed', [
            'exit_code' => $process->getExitCode(),
            'stdout'    => $stdout,
            'stderr'    => $stderr,
        ]);

        $lastLine = $this->lastJsonLine($stdout);
        $payload = $lastLine ? json_decode($lastLine, true) : null;

        if (! is_array($payload)) {
            throw new Model3DGenerationException(
                'Garment fitting produced no parseable JSON output. stderr: '.$stderr
            );
        }

        if (empty($payload['success'])) {
            $err = $payload['error'] ?? 'unknown error';
            throw new Model3DGenerationException("Garment fitting failed: {$err}");
        }

        if (! file_exists($outputAbsPath)) {
            throw new Model3DGenerationException(
                "Garment fitting reported success but output file is missing: {$outputAbsPath}"
            );
        }

        return $payload;
    }

    /**
     * Map a product Category model to a fitting class understood by fit_garment.py.
     */
    public function mapCategoryToFitClass(?Category $category): string
    {
        $name = strtolower((string) ($category?->name ?? ''));

        if ($name === '') {
            return 'jacket';
        }

        $map = [
            'dress'       => 'dress',
            'gown'        => 'dress',
            'shoe'        => 'shoes',
            'shoes'       => 'shoes',
            'footwear'    => 'shoes',
            'sneaker'     => 'shoes',
            'boot'        => 'shoes',
            'pant'        => 'pants',
            'pants'       => 'pants',
            'trouser'     => 'pants',
            'jean'        => 'pants',
            'bottom'      => 'pants',
            'shirt'       => 'shirt',
            'tee'         => 'shirt',
            't-shirt'     => 'shirt',
            'top'         => 'shirt',
            'blouse'      => 'shirt',
            'jacket'      => 'jacket',
            'coat'        => 'jacket',
            'outerwear'   => 'jacket',
            'hoodie'      => 'jacket',
        ];

        foreach ($map as $needle => $fitClass) {
            if (str_contains($name, $needle)) {
                return $fitClass;
            }
        }

        return 'accessory';
    }

    private function lastJsonLine(string $stdout): ?string
    {
        $lines = preg_split('/\r?\n/', $stdout);
        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $line = trim($lines[$i]);
            if ($line !== '' && str_starts_with($line, '{')) {
                return $line;
            }
        }
        return null;
    }
}

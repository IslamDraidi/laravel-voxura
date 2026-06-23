<?php

namespace Tests\Feature;

use Symfony\Component\Process\Process;
use Tests\TestCase;

class FitGarmentScriptTest extends TestCase
{
    private function pythonBinary(): string
    {
        $venv = base_path('.venv/bin/python');

        if (file_exists($venv)) {
            return $venv;
        }

        return 'python3';
    }

    public function test_fit_garment_script_outputs_usage_json_when_no_arguments(): void
    {
        $script = base_path('scripts/fit_garment.py');
        $process = new Process([$this->pythonBinary(), $script]);
        $process->run();

        $output = trim($process->getOutput());
        $payload = json_decode($output, true);

        $this->assertIsArray($payload);
        $this->assertFalse($payload['success']);
        $this->assertStringContainsString('Usage: fit_garment.py', $payload['error']);
    }

    public function test_fit_garment_script_reports_missing_input_files(): void
    {
        $script = base_path('scripts/fit_garment.py');
        $process = new Process([
            $this->pythonBinary(),
            $script,
            'missing_body.glb',
            'missing_product.glb',
            base_path('storage/app/public/tryon_results/tmp_output.glb'),
            'shirt',
        ]);
        $process->run();

        $output = trim($process->getOutput());
        $payload = json_decode($output, true);

        $this->assertIsArray($payload);
        $this->assertFalse($payload['success']);
        $this->assertStringContainsString('Body file not found', $payload['error']);
    }
}

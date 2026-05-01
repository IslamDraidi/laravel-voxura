<?php

namespace App\Services\AI\Contracts;

interface TryOnBodyProvider
{
    /**
     * Generate a 3D body GLB from a customer photo.
     *
     * @param  string    $photoAbsPath  Absolute path to the photo on disk.
     * @param  int|null  $heightCm      Optional customer height for scale calibration.
     * @param  int       $userId        For per-user storage scoping.
     * @return string                   Path relative to the public disk (e.g. "bodies/42/body_xxx.glb").
     */
    public function generateBodyModel(string $photoAbsPath, ?int $heightCm, int $userId): string;
}

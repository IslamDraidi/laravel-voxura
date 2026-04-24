<?php

return [
    'enabled'       => env('MODEL3D_GENERATION_ENABLED', true),
    'hf_token'      => env('HF_API_TOKEN'),
    'qwen3_space'   => env('HF_QWEN3_VL_SPACE', 'Qwen/Qwen2.5-VL-7B-Instruct'),
    'trellis_space' => env('HF_TRELLIS_SPACE', 'trellis-community/TRELLIS'),
    'max_retries'   => (int) env('MODEL3D_MAX_RETRIES', 3),
    'timeout'       => (int) env('MODEL3D_TIMEOUT', 300),
    'storage_path'  => 'public/models',

    // Replicate (TRELLIS hosted API)
    'replicate_token'   => env('REPLICATE_API_TOKEN'),
    'replicate_model'   => env('REPLICATE_TRELLIS_MODEL', 'firtoz/trellis'),
    'replicate_version' => env('REPLICATE_TRELLIS_VERSION'),
];

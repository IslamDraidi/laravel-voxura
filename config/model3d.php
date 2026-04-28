<?php

return [
    'enabled'       => env('MODEL3D_GENERATION_ENABLED', true),
    'hf_token'      => env('HF_API_TOKEN'),
    'qwen3_space'   => env('HF_QWEN3_VL_SPACE', 'Qwen/Qwen2.5-VL-7B-Instruct'),
    'trellis_space' => env('HF_TRELLIS_SPACE', 'trellis-community/TRELLIS'),
    'max_retries'   => (int) env('MODEL3D_MAX_RETRIES', 3),
    'timeout'       => (int) env('MODEL3D_TIMEOUT', 300),
    'storage_path'  => 'public/models',

    // Replicate provider selector: trellis | trellis2 | hunyuan | rodin
    'provider'          => env('MODEL3D_PROVIDER', 'trellis'),
    'replicate_token'   => env('REPLICATE_API_TOKEN'),

    // firtoz/trellis (current default)
    'replicate_model'   => env('REPLICATE_TRELLIS_MODEL', 'firtoz/trellis'),
    'replicate_version' => env('REPLICATE_TRELLIS_VERSION'),

    // tencent/hunyuan-3d-3.1 (recommended for texture fidelity)
    'hunyuan_version'   => env('REPLICATE_HUNYUAN_VERSION', 'a2838628b41a2e0ee2eb19b3ea98a40d75f8d7639bf5a1ddd37ea299bb334854'),

    // hyper3d/rodin (premium quality, slower/pricier)
    'rodin_version'     => env('REPLICATE_RODIN_VERSION', '9492be065b4a8b671ea929e63f8411ebcfa245e9af641400035e7ece20e1ba28'),

    // fishwowater/trellis2 (newer TRELLIS, 4096 textures)
    'trellis2_version'  => env('REPLICATE_TRELLIS2_VERSION', '52e1ad6852599ea10ce8e257635a3c11485cba51c181ea5173e34d9b2955b226'),
];

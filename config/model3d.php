<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Visual Language (VL) Provider
    |--------------------------------------------------------------------------
    | Replaces the original QwenVL integration. Defaults to OpenAI GPT-4o
    | vision, but any OpenAI-compatible endpoint works (Groq, Azure, etc.).
    */
    'vl_provider' => [
        'api_key'  => env('VL_PROVIDER_API_KEY'),
        'base_url' => env('VL_PROVIDER_BASE_URL', 'https://api.openai.com/v1'),
        'model'    => env('VL_PROVIDER_MODEL', 'gpt-4o'),
        'timeout'  => (int) env('VL_PROVIDER_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Trellis 3D Generation Service
    |--------------------------------------------------------------------------
    */
    'trellis' => [
        'api_key'           => env('TRELLIS_API_KEY'),
        'base_url'          => env('TRELLIS_BASE_URL', 'https://api.trellis3d.ai/v1'),
        'output_format'     => env('TRELLIS_OUTPUT_FORMAT', 'glb'),
        'texture_size'      => (int) env('TRELLIS_TEXTURE_SIZE', 1024),
        'mesh_simplify'     => (float) env('TRELLIS_MESH_SIMPLIFY', 0.95),
        'timeout'           => (int) env('TRELLIS_TIMEOUT', 120),
        'poll_interval'     => (int) env('TRELLIS_POLL_INTERVAL', 5),
        'max_poll_attempts' => (int) env('TRELLIS_MAX_POLL_ATTEMPTS', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pipeline Behaviour
    |--------------------------------------------------------------------------
    | image_to_3d_direct: when true, the pipeline sends the raw image URL to
    | Trellis instead of first converting it to a text description via the VL
    | model. Faster but produces less-guided geometry.
    */
    'pipeline' => [
        'image_to_3d_direct' => (bool) env('MODEL3D_IMAGE_TO_3D_DIRECT', false),
    ],

];

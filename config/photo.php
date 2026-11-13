<?php

return [
    'driver' => env('PHOTO_DRIVER', 'stub'),
    'disk' => env('PHOTO_DISK', 'public'),

    'local_rembg' => [
        'python' => env('PHOTO_PYTHON', base_path('.venv/bin/python')),
        'script' => env('PHOTO_SCRIPT', base_path('scripts/enhance.py')),
        'backdrop' => env('PHOTO_BACKDROP', storage_path('app/backgrounds/studio.png')),
        'model' => env('PHOTO_MODEL', storage_path('app/models/u2net.onnx')),
        'timeout' => (int) env('PHOTO_TIMEOUT', 120),
    ],

    'angles' => [
        'front' => 'Front 3/4',
        'rear' => 'Rear 3/4',
        'driver-side' => 'Driver side',
        'passenger-side' => 'Passenger side',
        'interior-front' => 'Interior (front)',
        'interior-rear' => 'Interior (rear)',
        'wheel' => 'Wheel detail',
        'other' => 'Other',
    ],
];

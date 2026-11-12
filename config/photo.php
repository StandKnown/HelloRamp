<?php

return [
    'driver' => env('PHOTO_DRIVER', 'stub'),
    'disk' => env('PHOTO_DISK', 'public'),

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

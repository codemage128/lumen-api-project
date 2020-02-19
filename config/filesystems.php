<?php

return [
    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => public_path('dropbox'),
            'url' => env('APP_URL') . '/dropbox',
            'visibility' => 'public',
        ],
        'dropbox' => [
            'driver' => 'dropbox',
            'token' => env('DROPBOX_TOKEN'),
        ],
    ]
];

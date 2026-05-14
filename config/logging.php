<?php

return [
    'default' => env('LOG_CHANNEL', 'file'),
    'channels' => [
        'file' => [
            'path' => env('LOG_PATH', 'storage/logs/app.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],
    ],
];

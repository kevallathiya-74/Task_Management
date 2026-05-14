<?php

return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'secure' => env('SESSION_SECURE_COOKIE', false),
    'http_only' => env('SESSION_HTTP_ONLY', true),
    'same_site' => env('SESSION_SAME_SITE', 'Lax'),
];

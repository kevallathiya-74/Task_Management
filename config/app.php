<?php

return [
    'name' => env('APP_NAME', 'Task Management System'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost/Task_Management'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    
    'upload_path' => 'public/uploads',
    'storage_path' => 'storage',
    
    'pagination_limit' => 10,
];

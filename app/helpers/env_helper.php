<?php

use App\Core\Env;

/**
 * Global helper to get environment variables
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return Env::get($key, $default);
    }
}

/**
 * Global helper to load .env file
 * @param string $path
 * @return void
 */
if (!function_exists('loadEnv')) {
    function loadEnv($path)
    {
        Env::load($path);
    }
}

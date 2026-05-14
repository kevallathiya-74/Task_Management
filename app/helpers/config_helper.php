<?php

use App\Core\Config;

/**
 * Global helper to get configuration values
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
if (!function_exists('config')) {
    function config($key, $default = null)
    {
        return Config::get($key, $default);
    }
}

/**
 * Global helper to load configuration files
 * @param string $path
 * @return void
 */
if (!function_exists('loadConfig')) {
    function loadConfig($path)
    {
        Config::load($path);
    }
}

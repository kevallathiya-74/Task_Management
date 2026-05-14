<?php

namespace App\Core;

class Config
{
    /**
     * Loaded configuration data
     * @var array
     */
    protected static $items = [];

    /**
     * Load all configuration files from directory
     * @param string $path
     * @return void
     */
    public static function load($path)
    {
        if (!is_dir($path)) {
            return;
        }

        $files = glob($path . '/*.php');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            static::$items[$key] = require $file;
        }
    }

    /**
     * Get configuration value using dot notation
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $parts = explode('.', $key);
        $data = static::$items;

        foreach ($parts as $part) {
            if (!isset($data[$part])) {
                return $default;
            }
            $data = $data[$part];
        }

        return $data;
    }

    /**
     * Set configuration value at runtime
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $parts = explode('.', $key);
        $data = &static::$items;

        foreach ($parts as $part) {
            if (!isset($data[$part]) || !is_array($data[$part])) {
                $data[$part] = [];
            }
            $data = &$data[$part];
        }

        $data = $value;
    }
}

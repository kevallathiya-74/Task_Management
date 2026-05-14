<?php

/**
 * URL Helper
 */
function url($path = '')
{
    $baseUrl = config('app.url', 'http://localhost');
    $path = ltrim($path, '/');
    return rtrim($baseUrl, '/') . ($path ? '/' . $path : '');
}

function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

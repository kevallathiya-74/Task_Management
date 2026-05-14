<?php

/**
 * Task Management System Entry Point
 */

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Load core classes for Env and Config (Manually before autoloader if needed, but better to load autoloader first)
require_once ROOT_PATH . '/app/core/Autoloader.php';
require_once ROOT_PATH . '/app/core/Env.php';
require_once ROOT_PATH . '/app/core/Config.php';

// Load helpers
require_once ROOT_PATH . '/app/helpers/env_helper.php';
require_once ROOT_PATH . '/app/helpers/config_helper.php';
require_once ROOT_PATH . '/app/helpers/url_helper.php';

// Load environment variables
loadEnv(ROOT_PATH . '/.env');

// Load configurations
loadConfig(ROOT_PATH . '/config');

// Set error reporting based on config
if (config('app.debug', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configure and start session
$sessionConfig = config('session');
session_set_cookie_params([
    'lifetime' => ($sessionConfig['lifetime'] ?? 120) * 60,
    'path' => '/',
    'domain' => '',
    'secure' => $sessionConfig['secure'] ?? false,
    'httponly' => $sessionConfig['http_only'] ?? true,
    'samesite' => $sessionConfig['same_site'] ?? 'Lax'
]);
session_start();

// Load Router
use App\Core\Router;

$router = new Router();

// Load routes
require_once ROOT_PATH . '/routes/web.php';
require_once ROOT_PATH . '/routes/api.php';

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

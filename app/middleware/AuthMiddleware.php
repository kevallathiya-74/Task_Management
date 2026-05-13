<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Check if user is authenticated
     */
    public static function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            self::redirect();
        }

        // Verify session in database if token exists
        if (isset($_SESSION['session_token'])) {
            $sessionModel = new \App\Models\Session();
            if (!$sessionModel->isValid($_SESSION['session_token'])) {
                self::logout();
            }
        }
    }

    /**
     * Check if user is Admin
     */
    public static function adminOnly()
    {
        self::handle();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            if (self::isApiRequest()) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Forbidden: Admin access only']);
                exit;
            }
            header('Location: ' . url('/dashboard?error=forbidden'));
            exit;
        }
    }

    private static function redirect()
    {
        if (self::isApiRequest()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit;
        }
        header('Location: ' . url('/login'));
        exit;
    }

    private static function logout()
    {
        session_unset();
        session_destroy();
        self::redirect();
    }

    private static function isApiRequest()
    {
        return strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
    }
}

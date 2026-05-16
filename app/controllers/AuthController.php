<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Session as SessionModel;

class AuthController
{
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            $prefix = ($_SESSION['user_role'] === 'admin') ? 'admin' : 'staff';
            header('Location: ' . url("/$prefix/dashboard"));
            exit;
        }
        
        $title = 'Login';
        require_once ROOT_PATH . '/app/views/auth/login.php';
    }

    public function login()
    {
        header('Content-Type: application/json');
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(['status' => 'validation_error', 'message' => 'Username and password are required']);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                echo json_encode(['status' => 'error', 'message' => 'Account is inactive. Please contact admin.']);
                return;
            }

            // Set PHP Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role_slug'];
            $_SESSION['user_username'] = $user['username'];
            
            // Create Database Session
            $sessionToken = bin2hex(random_bytes(32));
            $_SESSION['session_token'] = $sessionToken;
            
            $sessionModel = new SessionModel();
            $sessionModel->create($user['id'], $sessionToken);

            $userModel->updateLastLogin($user['id']);

            $prefix = ($user['role_slug'] === 'admin') ? 'admin' : 'staff';
            echo json_encode([
                'status' => 'success', 
                'message' => 'Login successful! Redirecting...',
                'redirect' => url("/$prefix/dashboard")
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    }

    public function logout()
    {
        if (isset($_SESSION['session_token'])) {
            $sessionModel = new SessionModel();
            $sessionModel->deleteByToken($_SESSION['session_token']);
        }
        
        session_unset();
        session_destroy();
        header('Location: ' . url('/login'));
        exit;
    }
}

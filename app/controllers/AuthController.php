<?php

namespace App\Controllers;

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
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
            return;
        }

        $userModel = new \App\Models\User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                echo json_encode(['success' => false, 'message' => 'Account is inactive. Please contact admin.']);
                return;
            }

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role_slug'];
            $_SESSION['user_username'] = $user['username'];
            
            $userModel->updateLastLogin($user['id']);

            $prefix = ($user['role_slug'] === 'admin') ? 'admin' : 'staff';
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful! Redirecting...',
                'redirect' => url("/$prefix/dashboard")
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . url('/login'));
        exit;
    }
}

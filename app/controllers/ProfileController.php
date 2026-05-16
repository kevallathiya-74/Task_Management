<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;

class ProfileController
{
    protected $userModel;
    protected $taskModel;
    protected $projectModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('/login'));
            exit;
        }
        $this->userModel = new User();
        $this->taskModel = new Task();
        $this->projectModel = new Project();
    }

    public function index()
    {
        $user = $this->userModel->findById($_SESSION['user_id']);
        $title = 'My Profile';
        $active_page = 'profile';

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';

        if ($user['role_slug'] === 'admin') {
            // Admin Analytics
            $stats = [
                'total_projects' => $this->projectModel->countAll(),
                'total_tasks' => $this->taskModel->countAll(),
                'total_staff' => count($this->userModel->listAll()),
            ];
            require_once ROOT_PATH . '/app/views/profile/admin.php';
        } else {
            // Staff Analytics
            $staffStats = [
                'completed_tasks' => $this->taskModel->countByStatusAndUser('completed', $user['id']),
                'total_tasks' => $this->taskModel->countByUser($user['id']),
                'active_projects' => $this->projectModel->listActiveByStaff($user['id']),
                'recent_activity' => $this->taskModel->listRecentByUser($user['id'], 5)
            ];
            
            // Completion Indicator
            $completion = 0;
            if ($staffStats['total_tasks'] > 0) {
                $completion = round(($staffStats['completed_tasks'] / $staffStats['total_tasks']) * 100);
            }

            require_once ROOT_PATH . '/app/views/profile/staff.php';
        }

        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function update()
    {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($newPassword)) {
            echo json_encode(['status' => 'error', 'message' => 'New password cannot be empty']);
            return;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
            return;
        }

        // Fetch current user to preserve other data
        $user = $this->userModel->findById($userId);
        
        $updateData = [
            'role_id' => $user['role_id'],
            'role' => $user['role'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'status' => $user['status'],
            'password' => $newPassword
        ];

        if ($this->userModel->update($userId, $updateData)) {
            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
        }
    }
}

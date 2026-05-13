<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Middleware\AuthMiddleware;

class StaffController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index()
    {
        $title = 'Staff Management';
        $active_page = 'staff';
        $roles = $this->roleModel->all();

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/staff/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function list()
    {
        header('Content-Type: application/json');
        
        $filters = [
            'role_id' => $_GET['role_id'] ?? null,
            'status' => $_GET['status'] ?? null
        ];

        $staff = $this->userModel->listAll($filters);
        
        echo json_encode([
            'success' => true,
            'data' => $staff
        ]);
    }

    public function create()
    {
        header('Content-Type: application/json');

        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role_id' => $_POST['role_id'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ];

        // Robust Validation
        if (empty($data['full_name']) || empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['role_id'])) {
            echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9._]{3,20}$/', $data['username'])) {
            echo json_encode(['success' => false, 'message' => 'Username must be 3-20 characters (alphanumeric, dots, underscores)']);
            return;
        }

        if (strlen($data['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
            return;
        }

        $role = $this->roleModel->findById($data['role_id']);
        if (!$role) {
            echo json_encode(['success' => false, 'message' => 'Invalid department selected']);
            return;
        }
        $data['role'] = $role['name'];

        // Check if username or email exists
        if ($this->userModel->findByUsername($data['username'])) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            return;
        }

        if ($this->userModel->findByEmail($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Email address already registered']);
            return;
        }

        $id = $this->userModel->create($data);
        if ($id) {
            echo json_encode(['success' => true, 'message' => 'Staff member created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create staff member']);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Staff ID is missing']);
            return;
        }

        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '', // Optional
            'role_id' => $_POST['role_id'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ];

        if (empty($data['full_name']) || empty($data['username']) || empty($data['email']) || empty($data['role_id'])) {
            echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        $role = $this->roleModel->findById($data['role_id']);
        if (!$role) {
            echo json_encode(['success' => false, 'message' => 'Invalid department selected']);
            return;
        }
        $data['role'] = $role['name'];

        if ($this->userModel->update($id, $data)) {
            echo json_encode(['success' => true, 'message' => 'Staff member updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update staff member']);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Staff ID is missing']);
            return;
        }

        // Prevent self-deletion
        if ($id === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
            return;
        }

        if ($this->userModel->softDelete($id)) {
            echo json_encode(['success' => true, 'message' => 'Staff member deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete staff member']);
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Middleware\AuthMiddleware;

class LeaveController
{
    protected $leaveModel;
    protected $userModel;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->leaveModel = new LeaveRequest();
        $this->userModel = new User();
    }

    public function staffIndex()
    {
        $title = 'Leave Management';
        $active_page = 'leaves';
        $leaves = $this->leaveModel->listByStaff($_SESSION['user_id']);

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/layouts/topbar.php';
        require_once ROOT_PATH . '/app/views/leave/staff_index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function adminIndex()
    {
        AuthMiddleware::adminOnly();
        $title = 'Leave Administration';
        $active_page = 'leaves_admin';
        $stats = $this->leaveModel->getStats();
        $staff = $this->userModel->listAll();

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/layouts/topbar.php';
        require_once ROOT_PATH . '/app/views/leave/admin_index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function submitRequest()
    {
        header('Content-Type: application/json');
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'leave_type' => $_POST['leave_type'] ?? '',
            'from_date' => $_POST['from_date'] ?? '',
            'to_date' => $_POST['to_date'] ?? '',
            'reason' => trim($_POST['reason'] ?? ''),
            'total_days' => (int)($_POST['total_days'] ?? 0)
        ];

        // Hardened Validation
        if (empty($data['leave_type']) || empty($data['from_date']) || empty($data['to_date']) || strlen($data['reason']) < 10) {
            echo json_encode(['success' => false, 'message' => 'Please fill all fields correctly. Reason must be at least 10 chars.']);
            return;
        }

        if (strtotime($data['from_date']) < strtotime(date('Y-m-d'))) {
            echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past']);
            return;
        }

        if (strtotime($data['to_date']) < strtotime($data['from_date'])) {
            echo json_encode(['success' => false, 'message' => 'End date cannot be before start date']);
            return;
        }

        if ($this->leaveModel->hasOverlap($data['user_id'], $data['from_date'], $data['to_date'])) {
            echo json_encode(['success' => false, 'message' => 'Leave request already exists for selected dates.']);
            return;
        }

        if ($this->leaveModel->create($data)) {
            echo json_encode(['success' => true, 'message' => 'Leave request submitted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit request.']);
        }
    }

    public function updateStatus()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        $comment = trim($_POST['admin_comment'] ?? '');

        if (!$id || !in_array($status, ['approved', 'rejected'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status or missing ID.']);
            return;
        }

        if ($this->leaveModel->updateStatus($id, $status, $_SESSION['user_id'], $comment)) {
            echo json_encode(['success' => true, 'message' => 'Leave request ' . $status . ' successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed.']);
        }
    }

    public function cancelRequest()
    {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Request ID is missing']);
            return;
        }

        if ($this->leaveModel->cancel($id, $_SESSION['user_id'])) {
            echo json_encode(['success' => true, 'message' => 'Leave request cancelled.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cancellation failed.']);
        }
    }

    public function getList()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');
        $filters = [
            'status' => $_GET['status'] ?? '',
            'user_id' => $_GET['user_id'] ?? ''
        ];
        $leaves = $this->leaveModel->listAll($filters);
        echo json_encode(['success' => true, 'data' => $leaves]);
    }
}

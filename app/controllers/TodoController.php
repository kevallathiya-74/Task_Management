<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Models\User;
use App\Middleware\AuthMiddleware;

class TodoController
{
    protected $todoModel;
    protected $userModel;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->todoModel = new Todo();
        $this->userModel = new User();
    }

    public function index()
    {
        $title = 'Todo List';
        $active_page = 'todo';
        
        $staff = $this->userModel->listAll();

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/todo/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function list()
    {
        header('Content-Type: application/json');
        
        try {
            $filters = [];
            if ($_SESSION['user_role'] !== 'admin') {
                $filters['assigned_to'] = $_SESSION['user_id'];
            } else {
                if (!empty($_GET['assigned_to'])) {
                    $filters['assigned_to'] = $_GET['assigned_to'];
                }
            }
            
            if (!empty($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }

            $todos = $this->todoModel->listAll($filters);
            echo json_encode(['status' => 'success', 'data' => $todos]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function create()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'assigned_to' => $_POST['assigned_to'] ?? '',
                'status' => $_POST['status'] ?? 'pending',
                'priority' => $_POST['priority'] ?? 'medium',
                'notes' => trim($_POST['notes'] ?? ''),
                'is_pinned' => isset($_POST['is_pinned']) ? (bool)$_POST['is_pinned'] : false
            ];



            if (empty($data['title']) || empty($data['assigned_to'])) {
                echo json_encode(['status' => 'validation_error', 'message' => 'Title and Assignee are required']);
                return;
            }

            $id = $this->todoModel->create($data);
            if ($id) {
                echo json_encode(['status' => 'success', 'message' => 'Todo created successfully', 'id' => $id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create todo']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Todo ID is missing']);
                return;
            }

            $todo = $this->todoModel->getById($id);
            if (!$todo) {
                echo json_encode(['status' => 'error', 'message' => 'Todo not found']);
                return;
            }

            // Authorization
            if ($_SESSION['user_role'] !== 'admin' && $todo['assigned_to'] !== $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }

            $data = [
                'title' => $_POST['title'] ?? $todo['title'],
                'assigned_to' => $_POST['assigned_to'] ?? $todo['assigned_to'],
                'status' => $_POST['status'] ?? $todo['status'],
                'priority' => $_POST['priority'] ?? $todo['priority'],
                'notes' => $_POST['notes'] ?? $todo['notes'],
                'is_pinned' => isset($_POST['is_pinned']) ? (bool)$_POST['is_pinned'] : (bool)$todo['is_pinned']
            ];



            // Staff can only update status
            if ($_SESSION['user_role'] !== 'admin') {
                $data = [
                    'title' => $todo['title'],
                    'assigned_to' => $todo['assigned_to'],
                    'status' => $_POST['status'] ?? $todo['status'],
                    'priority' => $todo['priority'],
                    'notes' => $todo['notes'],
                    'is_pinned' => $todo['is_pinned']
                ];
                

            }

            if ($this->todoModel->update($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Todo updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update todo']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Todo ID is missing']);
                return;
            }

            if ($this->todoModel->delete($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Todo deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete todo']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function resetPinned()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE todo_lists SET status = 'pending' WHERE is_pinned = 1");
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Pinned tasks reset successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to reset pinned tasks']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Middleware\AuthMiddleware;

class TaskController
{
    protected $taskModel;
    protected $projectModel;
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->taskModel = new Task();
        $this->projectModel = new Project();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index()
    {
        $title = 'Task Management';
        $active_page = 'tasks';
        
        $projects = $this->projectModel->listAll();
        $staff = $this->userModel->listAll();
        $roles = $this->roleModel->all();
        $project_id = $_GET['project_id'] ?? null;

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/tasks/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function list()
    {
        header('Content-Type: application/json');
        
        try {
            $filters = [
                'project_id' => $_GET['project_id'] ?? null,
                'assigned_to' => $_GET['assigned_to'] ?? null,
                'status' => $_GET['status'] ?? null,
                'role_id' => $_GET['role_id'] ?? null
            ];

            // If user is not admin, they can only see tasks assigned to them (unless they are a manager of that dept - but keeping it simple for now)
            if ($_SESSION['user_role'] !== 'admin' && empty($filters['assigned_to'])) {
                // $filters['assigned_to'] = $_SESSION['user_id']; 
                // Let's allow staff to see all tasks for now as per PRD "Transparency"
            }

            $tasks = $this->taskModel->listAll($filters);
            
            echo json_encode([
                'success' => true,
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        header('Content-Type: application/json');

        try {
            $data = [
                'project_id' => $_POST['project_id'] ?? '',
                'assigned_to' => $_POST['assigned_to'] ?? '',
                'role_id' => $_POST['role_id'] ?? '',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'due_date' => (!empty($_POST['due_date']) && !empty($_POST['due_time'])) ? $_POST['due_date'] . ' ' . $_POST['due_time'] : date('Y-m-d H:i'),
                'due_time' => $_POST['due_time'] ?? '09:00',
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => $_POST['status'] ?? 'pending'
            ];

            if (empty($data['project_id']) || empty($data['assigned_to']) || empty($data['title'])) {
                echo json_encode(['success' => false, 'message' => 'Project, Assignee, and Title are required']);
                return;
            }

            $id = $this->taskModel->create($data);
            if ($id) {
                echo json_encode(['success' => true, 'message' => 'Task created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create task']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Task ID is missing']);
                return;
            }

            $task = $this->taskModel->getById($id);
            if (!$task) {
                echo json_encode(['success' => false, 'message' => 'Task not found']);
                return;
            }

            $data = [
                'assigned_to' => $_POST['assigned_to'] ?? $task['assigned_to'],
                'role_id' => $_POST['role_id'] ?? $task['role_id'],
                'title' => $_POST['title'] ?? $task['title'],
                'description' => $_POST['description'] ?? $task['description'],
                'status' => $_POST['status'] ?? $task['status'],
                'due_date' => (!empty($_POST['due_date']) && !empty($_POST['due_time'])) ? $_POST['due_date'] . ' ' . $_POST['due_time'] : $task['due_date'],
                'due_time' => $_POST['due_time'] ?? $task['due_time'],
                'priority' => $_POST['priority'] ?? $task['priority'],
                'progress_percentage' => $_POST['progress_percentage'] ?? $task['progress_percentage'],
                'status_notes' => $_POST['status_notes'] ?? $task['status_notes'],
                'is_completed' => $task['is_completed'],
                'is_incomplete' => $task['is_incomplete'],
                'completed_at' => $task['completed_at'],
                'admin_alert_sent' => $task['admin_alert_sent']
            ];

            if ($this->taskModel->update($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update task']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Task ID is missing']);
            return;
        }

        if ($this->taskModel->softDelete($id)) {
            echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
        }
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            $type = $_POST['type'] ?? ''; // 'complete' or 'incomplete'

            if (empty($id) || empty($type)) {
                echo json_encode(['success' => false, 'message' => 'Missing data']);
                return;
            }

            $task = $this->taskModel->getById($id);
            if (!$task) {
                echo json_encode(['success' => false, 'message' => 'Task not found']);
                return;
            }

            // Role check
            if ($_SESSION['user_role'] !== 'admin' && $task['assigned_to'] !== $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

            $data = [
                'assigned_to' => $task['assigned_to'],
                'role_id' => $task['role_id'],
                'title' => $task['title'],
                'description' => $task['description'],
                'due_date' => $task['due_date'],
                'due_time' => $task['due_time'],
                'priority' => $task['priority'],
                'progress_percentage' => $task['progress_percentage'],
                'status_notes' => $task['status_notes'],
                'is_completed' => $task['is_completed'],
                'is_incomplete' => $task['is_incomplete'],
                'completed_at' => $task['completed_at'],
                'admin_alert_sent' => $task['admin_alert_sent'],
                'status' => $task['status']
            ];

            if ($type === 'complete') {
                $data['is_completed'] = 1;
                $data['is_incomplete'] = 0;
                $data['status'] = 'completed';
                $data['completed_at'] = date('Y-m-d H:i:s');
                $data['progress_percentage'] = 100;
            } elseif ($type === 'incomplete') {
                $data['is_incomplete'] = 1;
                $data['is_completed'] = 0;
                $data['status'] = 'in_progress'; // or keep existing
                
                // Create alert if not already sent
                if (!$task['admin_alert_sent']) {
                    $alertModel = new \App\Models\TaskAlert();
                    $alertModel->create([
                        'task_id' => $id,
                        'user_id' => $task['assigned_to'],
                        'message' => "Task marked incomplete"
                    ]);
                    $data['admin_alert_sent'] = 1;
                }
            }

            if ($this->taskModel->update($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Task status updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Models\TaskAlert;
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

            $tasks = $this->taskModel->listAll($filters);
            
            echo json_encode([
                'status' => 'success',
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        $tasksData = $_POST['tasks'] ?? [];

        if (empty($tasksData) || !is_array($tasksData)) {
            echo json_encode(['status' => 'error', 'message' => 'No task data provided']);
            return;
        }

        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $db->beginTransaction();

            $createdCount = 0;
            foreach ($tasksData as $index => $rawTask) {
                $data = [
                    'project_id' => $rawTask['project_id'] ?? '',
                    'assigned_to' => $rawTask['assigned_to'] ?? '',
                    'role_id' => $rawTask['role_id'] ?? '',
                    'title' => trim($rawTask['title'] ?? ''),
                    'description' => trim($rawTask['description'] ?? ''),
                    'due_date' => (!empty($rawTask['due_date'])) ? $rawTask['due_date'] . ' ' . ($rawTask['due_time'] ?? '09:00') : date('Y-m-d H:i:s'),
                    'due_time' => $rawTask['due_time'] ?? '09:00',
                    'priority' => $rawTask['priority'] ?? 'medium',
                    'status' => $rawTask['status'] ?? 'pending',
                    'progress_percentage' => $rawTask['progress_percentage'] ?? 0,
                    'status_notes' => trim($rawTask['status_notes'] ?? '')
                ];

                if (empty($data['project_id']) || empty($data['assigned_to']) || empty($data['title'])) {
                    echo json_encode(['status' => 'validation_error', 'message' => "Task #" . ($index + 1) . ": Project, Assignee, and Title are required"]);
                    $db->rollBack();
                    return;
                }

                // Verify existence
                if (!$this->projectModel->findById($data['project_id'])) {
                    echo json_encode(['status' => 'validation_error', 'message' => "Task #" . ($index + 1) . ": Invalid project selected"]);
                    $db->rollBack();
                    return;
                }
                if (!$this->userModel->findById($data['assigned_to'])) {
                    echo json_encode(['status' => 'validation_error', 'message' => "Task #" . ($index + 1) . ": Invalid assignee selected"]);
                    $db->rollBack();
                    return;
                }

                if (!$this->taskModel->create($data)) {
                    throw new \Exception("Task #" . ($index + 1) . ": Failed to create task record");
                }
                $createdCount++;
            }

            $db->commit();
            echo json_encode([
                'status' => 'success', 
                'message' => $createdCount . ' task(s) created successfully'
            ]);
        } catch (\Exception $e) {
            $db = \App\Core\Database::getInstance()->getConnection();
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Task ID is missing']);
                return;
            }

            $task = $this->taskModel->getById($id);
            if (!$task) {
                echo json_encode(['status' => 'error', 'message' => 'Task not found']);
                return;
            }

            // Authorization check: Admin or Assignee
            if ($_SESSION['user_role'] !== 'admin' && $task['assigned_to'] !== $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'You are not authorized to update this task']);
                return;
            }

            $data = [
                'project_id' => $_POST['project_id'] ?? $task['project_id'],
                'assigned_to' => $_POST['assigned_to'] ?? $task['assigned_to'],
                'role_id' => $_POST['role_id'] ?? $task['role_id'],
                'title' => trim($_POST['title'] ?? $task['title']),
                'description' => trim($_POST['description'] ?? $task['description']),
                'status' => $_POST['status'] ?? $task['status'],
                'due_date' => (!empty($_POST['due_date']) && !empty($_POST['due_time'])) ? $_POST['due_date'] . ' ' . $_POST['due_time'] : $task['due_date'],
                'due_time' => $_POST['due_time'] ?? $task['due_time'],
                'priority' => $_POST['priority'] ?? $task['priority'],
                'progress_percentage' => $_POST['progress_percentage'] ?? $task['progress_percentage'],
                'status_notes' => trim($_POST['status_notes'] ?? $task['status_notes']),
                'is_completed' => $task['is_completed'],
                'is_incomplete' => $task['is_incomplete'],
                'completed_at' => $task['completed_at'],
                'admin_alert_sent' => $task['admin_alert_sent'],
                'is_recurring' => $_POST['is_recurring'] ?? $task['is_recurring'],
                'recurring_type' => $_POST['recurring_type'] ?? $task['recurring_type'],
                'recurring_parent_id' => $task['recurring_parent_id'],
                'next_repeat_date' => $_POST['next_repeat_date'] ?? $task['next_repeat_date'],
                'repeat_status' => $_POST['repeat_status'] ?? $task['repeat_status']
            ];

            // If not admin, restrict certain fields
            if ($_SESSION['user_role'] !== 'admin') {
                $data['project_id'] = $task['project_id'];
                $data['assigned_to'] = $task['assigned_to'];
                $data['role_id'] = $task['role_id'];
                $data['title'] = $task['title'];
            }

            if ($this->taskModel->update($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Task ID is missing']);
            return;
        }

        if ($this->taskModel->softDelete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete task']);
        }
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            $type = $_POST['type'] ?? ''; // 'complete' or 'incomplete'

            if (empty($id) || empty($type)) {
                echo json_encode(['status' => 'error', 'message' => 'Missing data']);
                return;
            }

            $task = $this->taskModel->getById($id);
            if (!$task) {
                echo json_encode(['status' => 'error', 'message' => 'Task not found']);
                return;
            }

            // Authorization check
            if ($_SESSION['user_role'] !== 'admin' && $task['assigned_to'] !== $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }

            $data = $task; // Start with current data

            if ($type === 'complete') {
                $data['is_completed'] = 1;
                $data['is_incomplete'] = 0;
                $data['status'] = 'completed';
                $data['completed_at'] = date('Y-m-d H:i:s');
                $data['progress_percentage'] = 100;
            } elseif ($type === 'incomplete') {
                $data['is_incomplete'] = 1;
                $data['is_completed'] = 0;
                $data['status'] = 'in_progress';
                
                if (!$task['admin_alert_sent']) {
                    $alertModel = new TaskAlert();
                    $alertModel->create([
                        'task_id' => $id,
                        'user_id' => $task['assigned_to'],
                        'message' => "Task marked incomplete"
                    ]);
                    $data['admin_alert_sent'] = 1;
                }
            }

            if ($this->taskModel->update($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Task status updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update failed']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function enableRecurring()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            $type = $_POST['type'] ?? ''; // 'daily', 'weekly' or 'monthly'

            if (empty($id) || !in_array($type, ['daily', 'weekly', 'monthly'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
                return;
            }

            $task = $this->taskModel->getById($id);
            if (!$task) {
                echo json_encode(['success' => false, 'message' => 'Task not found']);
                return;
            }

            // Calculate next repeat date
            $currentDueDate = !empty($task['due_date']) ? $task['due_date'] : date('Y-m-d');
            $nextDate = $this->calculateNextDate($currentDueDate, $type);

            if ($this->taskModel->updateRecurringStatus($id, 1, $type, $nextDate, 'active')) {
                // Generate first repeat task immediately
                $parentTask = $this->taskModel->getById($id);
                $newId = $this->generateNextTask($parentTask);
                
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Recurring enabled. New task created for ' . $nextDate
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to enable recurring']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function disableRecurring()
    {
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Task ID is missing']);
                return;
            }

            if ($this->taskModel->updateRecurringStatus($id, 0, null, null, 'completed')) {
                echo json_encode(['status' => 'success', 'message' => 'Recurring disabled successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to disable recurring']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function recurringLogs()
    {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Task ID is missing']);
                return;
            }

            $logs = $this->taskModel->listRecurringLogs($id);
            echo json_encode(['status' => 'success', 'data' => $logs]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function processRecurring()
    {
        // This could be called by a cron job or manually
        AuthMiddleware::adminOnly();
        header('Content-Type: application/json');

        try {
            $tasks = $this->taskModel->getRecurringTasks();
            $generatedCount = 0;

            foreach ($tasks as $task) {
                $newId = $this->generateNextTask($task);
                if ($newId) {
                    $generatedCount++;
                }
            }

            echo json_encode([
                'success' => true, 
                'message' => $generatedCount . ' recurring task(s) processed'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function generateNextTask($parentTask)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();

            // 1. Prepare new task data (cloning parent)
            $newData = [
                'project_id' => $parentTask['project_id'],
                'assigned_to' => $parentTask['assigned_to'],
                'role_id' => $parentTask['role_id'],
                'title' => $parentTask['title'],
                'description' => $parentTask['description'],
                'due_date' => $parentTask['next_repeat_date'],
                'due_time' => $parentTask['due_time'],
                'priority' => $parentTask['priority'],
                'status' => 'pending',
                'progress_percentage' => 0,
                'status_notes' => '',
                'is_completed' => 0,
                'is_incomplete' => 0,
                'admin_alert_sent' => 0,
                'is_recurring' => 0, // Generated task is not recurring by default unless it's the new parent
                'recurring_type' => null,
                'recurring_parent_id' => $parentTask['id'],
                'next_repeat_date' => null,
                'repeat_status' => 'active'
            ];

            // 2. Create the new task
            $newTaskId = $this->taskModel->create($newData);
            if (!$newTaskId) {
                throw new \Exception("Failed to create generated task");
            }

            // 3. Log the generation
            $this->taskModel->logRecurringGeneration(
                $parentTask['id'], 
                $parentTask['recurring_type'], 
                $newTaskId, 
                $parentTask['next_repeat_date'], 
                $_SESSION['user_id']
            );

            // 4. Update the parent task's next_repeat_date
            $nextDate = $this->calculateNextDate($parentTask['next_repeat_date'], $parentTask['recurring_type']);
            $this->taskModel->updateRecurringStatus(
                $parentTask['id'], 
                1, 
                $parentTask['recurring_type'], 
                $nextDate, 
                'active'
            );

            $db->commit();
            return $newTaskId;

        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    private function calculateNextDate($currentDate, $type)
    {
        $date = new \DateTime($currentDate);
        if ($type === 'daily') {
            $date->modify('+1 day');
        } elseif ($type === 'weekly') {
            $date->modify('+7 days');
        } elseif ($type === 'monthly') {
            $date->modify('+1 month');
        }
        return $date->format('Y-m-d');
    }
}

<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Core\Database;
use App\Middleware\AuthMiddleware;

class DashboardController
{
    protected $db;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        $title = 'Dashboard';
        $active_page = 'dashboard';

        // Fetch Stats
        $stats = $this->getStats();
        
        // Fetch Recent Activities
        $recent_tasks = $this->getRecentTasks();

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/dashboard/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function getChartData()
    {
        header('Content-Type: application/json');
        
        // Task priority distribution
        $stmt = $this->db->query("SELECT priority, COUNT(*) as count FROM tasks WHERE deleted_at IS NULL GROUP BY priority");
        $tasks = $stmt->fetchAll();

        // Growth Analysis (tasks over time)
        $stmt = $this->db->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM tasks WHERE deleted_at IS NULL GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 7");
        $growth = array_reverse($stmt->fetchAll());

        echo json_encode([
            'tasks' => $tasks,
            'growth' => $growth
        ]);
    }

    public function getPriorityTasks()
    {
        header('Content-Type: application/json');
        $priority = $_GET['priority'] ?? 'high';
        $taskModel = new \App\Models\Task();
        
        $userId = ($_SESSION['user_role'] !== 'admin') ? $_SESSION['user_id'] : null;
        $tasks = $taskModel->listByPriority($priority, $userId);
        
        echo json_encode(['success' => true, 'data' => $tasks]);
    }

    public function getAlerts()
    {
        header('Content-Type: application/json');
        if ($_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $alertModel = new \App\Models\TaskAlert();
        $alerts = $alertModel->listUnread();
        
        echo json_encode(['success' => true, 'data' => $alerts]);
    }

    public function markAlertRead()
    {
        header('Content-Type: application/json');
        if ($_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $id = $_POST['id'] ?? '';
        $alertModel = new \App\Models\TaskAlert();
        if ($alertModel->markAsRead($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    private function getStats()
    {
        return [
            'total_projects' => $this->db->query("SELECT COUNT(*) FROM projects WHERE deleted_at IS NULL")->fetchColumn(),
            'active_projects' => $this->db->query("SELECT COUNT(*) FROM projects WHERE status = 'active' AND deleted_at IS NULL")->fetchColumn(),
            'active_tasks' => $this->db->query("SELECT COUNT(*) FROM tasks WHERE status != 'completed' AND deleted_at IS NULL")->fetchColumn(),
            'total_staff' => $this->db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn(),
            'completed_projects' => $this->db->query("SELECT COUNT(*) FROM projects WHERE status = 'completed' AND deleted_at IS NULL")->fetchColumn()
        ];
    }

    private function getRecentTasks()
    {
        $stmt = $this->db->query("
            SELECT t.*, p.project_name, u.full_name as assigned_to_name 
            FROM tasks t
            JOIN projects p ON t.project_id = p.id
            JOIN users u ON t.assigned_to = u.id
            WHERE t.deleted_at IS NULL
            ORDER BY t.created_at DESC
            LIMIT 5
        ");
        return $stmt->fetchAll();
    }
}

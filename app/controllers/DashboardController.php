<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Core\Database;
use App\Middleware\AuthMiddleware;
use PDO;

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
        $role = $_SESSION['user_role'] ?? 'staff';
        $prefix = ($role === 'admin') ? 'admin' : 'staff';
        $currentUri = $_SERVER['REQUEST_URI'];

        // If accessing root or generic dashboard, redirect to role-prefixed dashboard
        if ($currentUri == url('/') || $currentUri == url('/dashboard')) {
            header('Location: ' . url("/$prefix/dashboard"));
            exit;
        }

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
        
        // Project status distribution
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM projects WHERE deleted_at IS NULL GROUP BY status");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Task priority distribution - ONLY ACTIVE TASKS
        $stmt = $this->db->query("SELECT priority, COUNT(*) as count FROM tasks WHERE status != 'completed' AND deleted_at IS NULL GROUP BY priority");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Growth Analysis (Last 7 Days)
        $growth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE DATE(created_at) = :date AND deleted_at IS NULL");
            $stmt->execute(['date' => $date]);
            $growth[] = [
                'date' => $date,
                'count' => $stmt->fetchColumn()
            ];
        }

        echo json_encode([
            'success' => true,
            'projects' => $projects,
            'tasks' => $tasks,
            'growth' => $growth
        ]);
    }

    public function getPriorityTasks()
    {
        header('Content-Type: application/json');
        $priority = $_GET['priority'] ?? '';
        
        if (!$priority) {
            echo json_encode(['success' => false, 'message' => 'Priority required']);
            return;
        }

        $stmt = $this->db->prepare("
            SELECT t.*, p.project_name, u.full_name as staff_name 
            FROM tasks t
            JOIN projects p ON t.project_id = p.id
            JOIN users u ON t.assigned_to = u.id
            WHERE t.priority = :priority 
            AND t.status != 'completed' 
            AND t.deleted_at IS NULL
            ORDER BY t.created_at DESC
        ");
        $stmt->execute(['priority' => $priority]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function getAlerts()
    {
        header('Content-Type: application/json');
        $stmt = $this->db->query("
            SELECT a.*, t.title as task_title, u.full_name as staff_name 
            FROM task_alerts a
            JOIN tasks t ON a.task_id = t.id
            JOIN users u ON t.assigned_to = u.id
            WHERE a.is_read = 0
            ORDER BY a.created_at DESC
        ");
        $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $alerts
        ]);
    }

    public function markAlertRead()
    {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? '';
        
        if (!$id) {
            echo json_encode(['success' => false]);
            return;
        }

        $stmt = $this->db->prepare("UPDATE task_alerts SET is_read = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        echo json_encode(['success' => true]);
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

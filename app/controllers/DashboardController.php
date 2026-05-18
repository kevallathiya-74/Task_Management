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
        $stmt = $this->db->query("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM tasks 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
            AND deleted_at IS NULL 
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $growth_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Map to ensure all 7 days are present
        $growth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = 0;
            foreach ($growth_data as $g) {
                if ($g['date'] === $date) {
                    $count = $g['count'];
                    break;
                }
            }
            $growth[] = [
                'date' => $date,
                'count' => (int)$count
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
        // Combine projects counts
        $projectStats = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            FROM projects 
            WHERE deleted_at IS NULL
        ")->fetch(PDO::FETCH_ASSOC);

        // Combine tasks counts
        $taskStats = $this->db->query("
            SELECT 
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
            FROM tasks 
            WHERE deleted_at IS NULL
        ")->fetch(PDO::FETCH_ASSOC);

        return [
            'total_projects' => (int)$projectStats['total'],
            'active_projects' => (int)$projectStats['active'],
            'active_tasks' => (int)$taskStats['in_progress'],
            'pending_tasks' => (int)$taskStats['pending'],
            'total_staff' => $this->db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn(),
            'completed_projects' => (int)$projectStats['completed']
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

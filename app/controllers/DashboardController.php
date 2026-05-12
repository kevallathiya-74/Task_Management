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
        
        // Project status distribution
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM projects WHERE deleted_at IS NULL GROUP BY status");
        $projects = $stmt->fetchAll();

        // Task priority distribution
        $stmt = $this->db->query("SELECT priority, COUNT(*) as count FROM tasks WHERE deleted_at IS NULL GROUP BY priority");
        $tasks = $stmt->fetchAll();

        echo json_encode([
            'projects' => $projects,
            'tasks' => $tasks
        ]);
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

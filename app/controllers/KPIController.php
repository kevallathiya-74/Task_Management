<?php

namespace App\Controllers;

use App\Models\KPI;
use App\Models\User;
use App\Middleware\AuthMiddleware;

class KPIController
{
    protected $kpiModel;
    protected $userModel;

    public function __construct()
    {
        AuthMiddleware::handle();
        AuthMiddleware::adminOnly();
        $this->kpiModel = new KPI();
        $this->userModel = new User();
    }

    public function index()
    {
        $title = 'KPI Management';
        $active_page = 'kpi';
        $staff = $this->userModel->listAll();
        $analytics = $this->kpiModel->getTeamAnalytics();

        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/layouts/topbar.php';
        require_once ROOT_PATH . '/app/views/kpi/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function getDailyRecord()
    {
        header('Content-Type: application/json');
        $userId = $_GET['user_id'] ?? '';
        $date = $_GET['date'] ?? '';

        if (!$userId || !$date) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }

        $record = $this->kpiModel->findDaily($userId, $date);
        echo json_encode(['success' => true, 'data' => $record]);
    }

    public function getStaffReportData()
    {
        header('Content-Type: application/json');
        $userId = $_GET['user_id'] ?? '';
        $duration = $_GET['duration'] ?? 'monthly';

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        $history = $this->kpiModel->getHistoryByDuration($userId, $duration);
        
        $stats = [
            'avg_productivity' => 0, 'avg_quality' => 0, 'avg_discipline' => 0,
            'avg_communication' => 0, 'avg_growth' => 0, 'avg_total' => 0,
            'avg_salary' => 0, 'days_recorded' => count($history)
        ];

        if ($stats['days_recorded'] > 0) {
            foreach ($history as $row) {
                $stats['avg_productivity'] += $row['productivity_score'];
                $stats['avg_quality'] += $row['quality_score'];
                $stats['avg_discipline'] += $row['discipline_score'];
                $stats['avg_communication'] += $row['communication_score'];
                $stats['avg_growth'] += $row['growth_score'];
                $stats['avg_total'] += $row['weighted_total_score'];
                $stats['avg_salary'] += $row['salary_approval_percentage'];
            }
            foreach ($stats as $key => $val) {
                if ($key !== 'days_recorded') $stats[$key] = round($stats[$key] / $stats['days_recorded'], 2);
            }
        }

        echo json_encode([
            'success' => true, 
            'stats' => $stats,
            'history' => $history
        ]);
    }

    public function saveDaily()
    {
        header('Content-Type: application/json');
        
        $userId = $_POST['user_id'] ?? '';
        $date = $_POST['kpi_date'] ?? '';
        
        if (!$userId || !$date) {
            echo json_encode(['success' => false, 'message' => 'Staff and Date are required']);
            return;
        }

        if ($date > date('Y-m-d')) {
            echo json_encode(['success' => false, 'message' => 'Future dates are not allowed.']);
            return;
        }

        if (!$this->userModel->findById($userId)) {
            echo json_encode(['success' => false, 'message' => 'Invalid staff member selected']);
            return;
        }

        $data = [
            'user_id' => $userId,
            'kpi_date' => $date,
            'productivity_score' => max(0, min(10, (float)($_POST['productivity_score'] ?? 0))),
            'quality_score' => max(0, min(10, (float)($_POST['quality_score'] ?? 0))),
            'discipline_score' => max(0, min(10, (float)($_POST['discipline_score'] ?? 0))),
            'communication_score' => max(0, min(10, (float)($_POST['communication_score'] ?? 0))),
            'growth_score' => max(0, min(10, (float)($_POST['growth_score'] ?? 0))),
            'weighted_total_score' => (float)($_POST['weighted_total_score'] ?? 0),
            'salary_approval_percentage' => max(0, min(100, (float)($_POST['salary_approval_percentage'] ?? 0))),
            'performance_status' => trim($_POST['performance_status'] ?? 'Average'),
            'admin_notes' => trim($_POST['admin_notes'] ?? '')
        ];

        if ($this->kpiModel->saveDaily($data)) {
            echo json_encode(['success' => true, 'message' => 'Daily KPI saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save KPI']);
        }
    }

    public function staffReport()
    {
        $userId = $_GET['id'] ?? '';
        if (!$userId || !$this->userModel->findById($userId)) {
            header('Location: ' . url('/admin/kpi'));
            exit;
        }

        $title = 'Staff Performance Report';
        $active_page = 'kpi';
        
        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/layouts/topbar.php';
        require_once ROOT_PATH . '/app/views/kpi/staff-report.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function logReport()
    {
        header('Content-Type: application/json');
        $userId = $_POST['user_id'] ?? '';
        $duration = $_POST['duration'] ?? '';

        if (!$userId || !$duration) {
            echo json_encode(['success' => false]);
            return;
        }

        $this->kpiModel->logReport($userId, $duration);
        echo json_encode(['success' => true]);
    }
}

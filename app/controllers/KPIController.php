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

    public function getMonthlyReport()
    {
        header('Content-Type: application/json');
        $userId = $_GET['user_id'] ?? '';
        $month = $_GET['month'] ?? date('n');
        $year = $_GET['year'] ?? date('Y');

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        $stats = $this->kpiModel->getMonthlyStats($userId, $month, $year);
        $history = $this->kpiModel->getDailyHistory($userId, $month, $year);
        
        echo json_encode([
            'success' => true, 
            'stats' => $stats,
            'history' => $history
        ]);
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
        
        // Calculate aggregated stats from history
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
                if ($key !== 'days_recorded') $stats[$key] /= $stats['days_recorded'];
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
        
        $date = $_POST['kpi_date'] ?? '';
        if ($date > date('Y-m-d')) {
            echo json_encode(['success' => false, 'message' => 'Future dates are not allowed.']);
            return;
        }

        $data = [
            'user_id' => $_POST['user_id'] ?? '',
            'kpi_date' => $date,
            'productivity_score' => $_POST['productivity_score'] ?? 0,
            'quality_score' => $_POST['quality_score'] ?? 0,
            'discipline_score' => $_POST['discipline_score'] ?? 0,
            'communication_score' => $_POST['communication_score'] ?? 0,
            'growth_score' => $_POST['growth_score'] ?? 0,
            'weighted_total_score' => $_POST['weighted_total_score'] ?? 0,
            'salary_approval_percentage' => $_POST['salary_approval_percentage'] ?? 0,
            'performance_status' => $_POST['performance_status'] ?? 'Average',
            'admin_notes' => $_POST['admin_notes'] ?? ''
        ];

        if (!$data['user_id'] || !$data['kpi_date']) {
            echo json_encode(['success' => false, 'message' => 'Staff and Date are required']);
            return;
        }

        if ($this->kpiModel->saveDaily($data)) {
            echo json_encode(['success' => true, 'message' => 'Daily KPI saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save KPI']);
        }
    }

    public function staffReport()
    {
        $userId = $_GET['id'] ?? '';
        if (!$userId) {
            header('Location: ' . url('/admin/kpi'));
            exit;
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
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

<?php

namespace App\Controllers;

use App\Models\PublishingModel;
use App\Middleware\AuthMiddleware;

class PublishingController
{
    protected $model;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->model = new PublishingModel();
    }

    public function index()
    {
        $title = 'Publishing Report';
        $active_page = 'publishing';
        
        $extra_css = '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
        $extra_js = '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
        
        $userModel = new \App\Models\User();
        $users = $userModel->listAll();
        
        require_once ROOT_PATH . '/app/views/layouts/header.php';
        require_once ROOT_PATH . '/app/views/layouts/sidebar.php';
        require_once ROOT_PATH . '/app/views/publishing/index.php';
        require_once ROOT_PATH . '/app/views/layouts/footer.php';
    }

    public function fetchReport()
    {
        header('Content-Type: application/json');
        try {
            $userId = $_SESSION['user_id'];
            $isAdmin = ($_SESSION['user_role'] === 'admin');
            
            $month = !empty($_GET['month']) ? $_GET['month'] : date('n');
            $year = !empty($_GET['year']) ? $_GET['year'] : date('Y');
            
            $data = $this->model->fetchReportData($userId, $isAdmin, $month, $year);
            
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function createMonth()
    {
        header('Content-Type: application/json');
        try {
            $isAdmin = ($_SESSION['user_role'] === 'admin');
            if (!$isAdmin) {
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }
            
            $month = $_POST['month'] ?? '';
            $year = $_POST['year'] ?? '';
            
            if (empty($month) || empty($year)) {
                echo json_encode(['status' => 'error', 'message' => 'Month and Year are required']);
                return;
            }
            
            $reportId = $this->model->createMonthReport($month, $year);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Month report created successfully',
                'report_id' => $reportId
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function saveReport()
    {
        header('Content-Type: application/json');
        try {
            $userId = $_SESSION['user_id'];
            $isAdmin = ($_SESSION['user_role'] === 'admin');
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                return;
            }
            
            $this->model->saveReportData($input, $userId, $isAdmin);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Report saved successfully'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deleteRow()
    {
        header('Content-Type: application/json');
        try {
            $isAdmin = ($_SESSION['user_role'] === 'admin');
            
            $rowId = $_POST['id'] ?? '';
            
            if (empty($rowId)) {
                echo json_encode(['status' => 'error', 'message' => 'Row ID is missing']);
                return;
            }
            
            $this->model->deleteRow($rowId, $isAdmin);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Row deleted successfully'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

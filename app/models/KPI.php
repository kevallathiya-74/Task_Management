<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class KPI
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findDaily($userId, $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM kpi_records WHERE user_id = :user_id AND kpi_date = :date");
        $stmt->execute(['user_id' => $userId, 'date' => $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveDaily($data)
    {
        $existing = $this->findDaily($data['user_id'], $data['kpi_date']);
        
        if ($existing) {
            return $this->updateDaily($existing['id'], $data);
        } else {
            return $this->createDaily($data);
        }
    }

    public function createDaily($data)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO kpi_records (
                id, user_id, kpi_date, productivity_score, quality_score, 
                discipline_score, communication_score, growth_score, weighted_total_score, 
                salary_approval_percentage, performance_status, admin_notes, created_by
            ) VALUES (
                :id, :user_id, :kpi_date, :productivity, :quality, 
                :discipline, :communication, :growth, :total, 
                :salary, :status, :notes, :created_by
            )
        ");
        
        return $stmt->execute([
            'id' => $id,
            'user_id' => $data['user_id'],
            'kpi_date' => $data['kpi_date'],
            'productivity' => $data['productivity_score'],
            'quality' => $data['quality_score'],
            'discipline' => $data['discipline_score'],
            'communication' => $data['communication_score'],
            'growth' => $data['growth_score'],
            'total' => $data['weighted_total_score'],
            'salary' => $data['salary_approval_percentage'],
            'status' => $data['performance_status'],
            'notes' => $data['admin_notes'],
            'created_by' => $_SESSION['user_id']
        ]);
    }

    public function updateDaily($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE kpi_records SET 
                productivity_score = :productivity,
                quality_score = :quality,
                discipline_score = :discipline,
                communication_score = :communication,
                growth_score = :growth,
                weighted_total_score = :total,
                salary_approval_percentage = :salary,
                performance_status = :status,
                admin_notes = :notes
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'productivity' => $data['productivity_score'],
            'quality' => $data['quality_score'],
            'discipline' => $data['discipline_score'],
            'communication' => $data['communication_score'],
            'growth' => $data['growth_score'],
            'total' => $data['weighted_total_score'],
            'salary' => $data['salary_approval_percentage'],
            'status' => $data['performance_status'],
            'notes' => $data['admin_notes']
        ]);
    }

    public function getMonthlyStats($userId, $month, $year)
    {
        $stmt = $this->db->prepare("
            SELECT 
                AVG(productivity_score) as avg_productivity,
                AVG(quality_score) as avg_quality,
                AVG(discipline_score) as avg_discipline,
                AVG(communication_score) as avg_communication,
                AVG(growth_score) as avg_growth,
                AVG(weighted_total_score) as avg_total,
                AVG(salary_approval_percentage) as avg_salary,
                COUNT(*) as days_recorded
            FROM kpi_records 
            WHERE user_id = :user_id 
            AND MONTH(kpi_date) = :month 
            AND YEAR(kpi_date) = :year
        ");
        $stmt->execute(['user_id' => $userId, 'month' => $month, 'year' => $year]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDailyHistory($userId, $month, $year)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM kpi_records 
            WHERE user_id = :user_id 
            AND MONTH(kpi_date) = :month 
            AND YEAR(kpi_date) = :year 
            ORDER BY kpi_date DESC
        ");
        $stmt->execute(['user_id' => $userId, 'month' => $month, 'year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryByDuration($userId, $duration)
    {
        $interval = "30 DAY"; // Default
        switch ($duration) {
            case '7days': $interval = "7 DAY"; break;
            case 'monthly': $interval = "30 DAY"; break;
            case '3months': $interval = "90 DAY"; break;
            case '6months': $interval = "180 DAY"; break;
            case '12months': $interval = "365 DAY"; break;
        }

        $stmt = $this->db->prepare("
            SELECT * FROM kpi_records 
            WHERE user_id = :user_id 
            AND kpi_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
            ORDER BY kpi_date DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamAnalytics()
    {
        return [
            'highest' => $this->db->query("
                SELECT u.full_name, AVG(t.weighted_total_score) as avg_score 
                FROM kpi_records t 
                JOIN users u ON t.user_id = u.id 
                WHERE t.kpi_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY t.user_id 
                ORDER BY avg_score DESC LIMIT 1
            ")->fetch(PDO::FETCH_ASSOC),
            'lowest' => $this->db->query("
                SELECT u.full_name, AVG(t.weighted_total_score) as avg_score 
                FROM kpi_records t 
                JOIN users u ON t.user_id = u.id 
                WHERE t.kpi_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY t.user_id 
                ORDER BY avg_score ASC LIMIT 1
            ")->fetch(PDO::FETCH_ASSOC),
            'team_avg' => $this->db->query("
                SELECT AVG(weighted_total_score) FROM kpi_records 
                WHERE kpi_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ")->fetchColumn()
        ];
    }

    public function logReport($userId, $duration)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO kpi_reports (id, user_id, report_type, report_duration, generated_by) 
            VALUES (:id, :user_id, 'performance_report', :duration, :generated_by)
        ");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'duration' => $duration,
            'generated_by' => $_SESSION['user_id']
        ]);
    }

    private function generateUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

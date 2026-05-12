<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class LeaveRequest
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO leave_requests (id, user_id, leave_type, from_date, to_date, total_days, reason, status) 
            VALUES (:id, :user_id, :type, :from, :to, :days, :reason, 'pending')
        ");
        
        return $stmt->execute([
            'id' => $id,
            'user_id' => $data['user_id'],
            'type' => $data['leave_type'],
            'from' => $data['from_date'],
            'to' => $data['to_date'],
            'days' => $data['total_days'],
            'reason' => $data['reason']
        ]);
    }

    public function listByStaff($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM leave_requests WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listAll($filters = [])
    {
        $sql = "SELECT l.*, u.full_name as staff_name FROM leave_requests l JOIN users u ON l.user_id = u.id WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND l.status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['user_id'])) {
            $sql .= " AND l.user_id = :user_id";
            $params['user_id'] = $filters['user_id'];
        }

        $sql .= " ORDER BY l.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $adminId, $comment = null)
    {
        $stmt = $this->db->prepare("
            UPDATE leave_requests 
            SET status = :status, admin_comment = :comment, approved_by = :admin_id, approved_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'comment' => $comment,
            'admin_id' => $adminId
        ]);
    }

    public function cancel($id, $userId)
    {
        $stmt = $this->db->prepare("UPDATE leave_requests SET status = 'cancelled' WHERE id = :id AND user_id = :user_id AND status = 'pending'");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }

    public function hasOverlap($userId, $from, $to)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM leave_requests 
            WHERE user_id = :user_id 
            AND status IN ('pending', 'approved')
            AND ((from_date <= :to AND to_date >= :from))
        ");
        $stmt->execute(['user_id' => $userId, 'from' => $from, 'to' => $to]);
        return $stmt->fetchColumn() > 0;
    }

    public function getStats()
    {
        return [
            'total' => $this->db->query("SELECT COUNT(*) FROM leave_requests")->fetchColumn(),
            'pending' => $this->db->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'")->fetchColumn(),
            'approved' => $this->db->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'approved'")->fetchColumn(),
            'rejected' => $this->db->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'rejected'")->fetchColumn()
        ];
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

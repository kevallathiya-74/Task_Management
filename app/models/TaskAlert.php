<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class TaskAlert
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
            INSERT INTO task_alerts (id, task_id, user_id, message) 
            VALUES (:id, :task_id, :user_id, :message)
        ");
        
        $result = $stmt->execute([
            'id' => $id,
            'task_id' => $data['task_id'],
            'user_id' => $data['user_id'],
            'message' => $data['message']
        ]);

        return $result ? $id : false;
    }

    public function listUnread()
    {
        $stmt = $this->db->prepare("
            SELECT a.*, t.title as task_title, u.full_name as staff_name 
            FROM task_alerts a 
            JOIN tasks t ON a.task_id = t.id 
            JOIN users u ON a.user_id = u.id 
            WHERE a.is_read = 0 
            ORDER BY a.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsRead($id)
    {
        $stmt = $this->db->prepare("UPDATE task_alerts SET is_read = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
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

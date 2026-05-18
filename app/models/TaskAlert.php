<?php
namespace App\Models;

use App\Core\Database;

class TaskAlert {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO task_alerts (id, task_id, user_id, message) 
            VALUES (:id, :task_id, :user_id, :message)
        ");
        return $stmt->execute([
            'id' => $id,
            'task_id' => $data['task_id'],
            'user_id' => $data['user_id'],
            'message' => $data['message']
        ]);
    }

    private function generateUuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

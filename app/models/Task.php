<?php

namespace App\Models;

use App\Core\Database;

class Task
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function listAll($filters = [])
    {
        $sql = "
            SELECT t.*, p.project_name, u.full_name as assigned_to_name, r.name as role_name
            FROM tasks t
            JOIN projects p ON t.project_id = p.id
            JOIN users u ON t.assigned_to = u.id
            JOIN roles r ON t.role_id = r.id
            WHERE t.deleted_at IS NULL
        ";
        
        $params = [];
        if (!empty($filters['project_id'])) {
            $sql .= " AND t.project_id = :project_id";
            $params['project_id'] = $filters['project_id'];
        }
        
        if (!empty($filters['assigned_to'])) {
            $sql .= " AND t.assigned_to = :assigned_to";
            $params['assigned_to'] = $filters['assigned_to'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['role_id'])) {
            $sql .= " AND t.role_id = :role_id";
            $params['role_id'] = $filters['role_id'];
        }

        $sql .= " ORDER BY t.due_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO tasks (id, project_id, assigned_to, role_id, title, description, status, due_date, due_time, priority, is_completed, is_incomplete, admin_alert_sent) 
            VALUES (:id, :project_id, :assigned_to, :role_id, :title, :description, :status, :due_date, :due_time, :priority, :is_completed, :is_incomplete, :admin_alert_sent)
        ");
        
        $result = $stmt->execute([
            'id' => $id,
            'project_id' => $data['project_id'],
            'assigned_to' => $data['assigned_to'],
            'role_id' => $data['role_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'due_date' => $data['due_date'],
            'due_time' => $data['due_time'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'is_completed' => $data['is_completed'] ?? 0,
            'is_incomplete' => $data['is_incomplete'] ?? 0,
            'admin_alert_sent' => $data['admin_alert_sent'] ?? 0
        ]);

        return $result ? $id : false;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET 
                assigned_to = :assigned_to,
                role_id = :role_id,
                title = :title,
                description = :description,
                status = :status,
                due_date = :due_date,
                due_time = :due_time,
                priority = :priority,
                is_completed = :is_completed,
                is_incomplete = :is_incomplete,
                completed_at = :completed_at,
                admin_alert_sent = :admin_alert_sent,
                progress_percentage = :progress,
                status_notes = :notes
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'assigned_to' => $data['assigned_to'],
            'role_id' => $data['role_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'due_date' => $data['due_date'],
            'due_time' => $data['due_time'] ?? null,
            'priority' => $data['priority'],
            'is_completed' => $data['is_completed'] ?? 0,
            'is_incomplete' => $data['is_incomplete'] ?? 0,
            'completed_at' => $data['completed_at'] ?? null,
            'admin_alert_sent' => $data['admin_alert_sent'] ?? 0,
            'progress' => $data['progress_percentage'] ?? 0,
            'notes' => $data['status_notes'] ?? null
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tasks WHERE deleted_at IS NULL");
        return $stmt->fetchColumn();
    }

    public function countByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = :user_id AND deleted_at IS NULL");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function countByStatusAndUser($status, $userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE status = :status AND assigned_to = :user_id AND deleted_at IS NULL");
        $stmt->execute(['status' => $status, 'user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function listRecentByUser($userId, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, p.project_name 
            FROM tasks t 
            JOIN projects p ON t.project_id = p.id 
            WHERE t.assigned_to = :user_id AND t.deleted_at IS NULL 
            ORDER BY t.updated_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
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

    public function listByPriority($priority, $userId = null)
    {
        $sql = "
            SELECT t.*, u.full_name as staff_name, p.project_name, r.name as role_name
            FROM tasks t 
            JOIN users u ON t.assigned_to = u.id 
            JOIN projects p ON t.project_id = p.id 
            JOIN roles r ON t.role_id = r.id
            WHERE t.priority = :priority AND t.deleted_at IS NULL
        ";
        
        $params = ['priority' => $priority];
        if ($userId) {
            $sql .= " AND t.assigned_to = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY t.due_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

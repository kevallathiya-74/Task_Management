<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Project
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function listAll($filters = [])
    {
        $sql = "
            SELECT p.*, r.name as role_name, 
                   (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND deleted_at IS NULL) as total_tasks,
                   (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status = 'completed' AND deleted_at IS NULL) as completed_tasks
            FROM projects p 
            JOIN roles r ON p.role_id = r.id 
            WHERE p.deleted_at IS NULL
        ";
        
        $params = [];

        if (!empty($filters['role_id'])) {
            $sql .= " AND p.role_id = :role_id";
            $params['role_id'] = $filters['role_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE project_name = :name AND deleted_at IS NULL");
        $stmt->execute(['name' => $name]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO projects (id, created_by, role_id, project_name, client_name, description, start_date, deadline, status) 
            VALUES (:id, :created_by, :role_id, :name, :client, :description, :start, :deadline, :status)
        ");
        
        $success = $stmt->execute([
            'id' => $id,
            'created_by' => $_SESSION['user_id'],
            'role_id' => $data['role_id'],
            'name' => $data['project_name'],
            'client' => $data['client_name'],
            'description' => $data['description'],
            'start' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status' => $data['status']
        ]);

        return $success ? $id : false;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE projects SET 
                role_id = :role_id, 
                project_name = :name, 
                client_name = :client, 
                description = :description, 
                start_date = :start, 
                deadline = :deadline, 
                status = :status 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'role_id' => $data['role_id'],
            'name' => $data['project_name'],
            'client' => $data['client_name'],
            'description' => $data['description'],
            'start' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status' => $data['status']
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("UPDATE projects SET deleted_at = NOW() WHERE id = :id");
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

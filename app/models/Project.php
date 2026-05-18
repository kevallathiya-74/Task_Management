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
            SELECT p.*, 
                   (SELECT GROUP_CONCAT(r.name SEPARATOR ', ') FROM project_departments pd JOIN roles r ON pd.role_id = r.id WHERE pd.project_id = p.id) as role_name,
                   (SELECT GROUP_CONCAT(pd.role_id) FROM project_departments pd WHERE pd.project_id = p.id) as role_ids_csv,
                   (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND deleted_at IS NULL) as total_tasks,
                   (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status = 'completed' AND deleted_at IS NULL) as completed_tasks,
                   (SELECT GROUP_CONCAT(user_id) FROM project_assignments WHERE project_id = p.id) as assigned_users_csv
            FROM projects p 
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
        
        $role_id = !empty($data['role_ids']) ? $data['role_ids'][0] : '';
        
        $success = $stmt->execute([
            'id' => $id,
            'created_by' => $_SESSION['user_id'],
            'role_id' => $role_id,
            'name' => $data['project_name'],
            'client' => $data['client_name'],
            'description' => $data['description'],
            'start' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status' => $data['status']
        ]);

        if ($success && !empty($data['role_ids'])) {
            $stmtInsDept = $this->db->prepare("INSERT INTO project_departments (id, project_id, role_id) VALUES (:id, :project_id, :role_id)");
            foreach ($data['role_ids'] as $roleId) {
                $stmtInsDept->execute([
                    'id' => $this->generateUuid(),
                    'project_id' => $id,
                    'role_id' => $roleId
                ]);
            }
        }

        if ($success && !empty($data['assigned_users'])) {
            $stmtIns = $this->db->prepare("INSERT INTO project_assignments (id, project_id, user_id) VALUES (:id, :project_id, :user_id)");
            foreach ($data['assigned_users'] as $userId) {
                $stmtIns->execute([
                    'id' => $this->generateUuid(),
                    'project_id' => $id,
                    'user_id' => $userId
                ]);
            }
        }

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
        
        $role_id = !empty($data['role_ids']) ? $data['role_ids'][0] : '';
        
        $success = $stmt->execute([
            'id' => $id,
            'role_id' => $role_id,
            'name' => $data['project_name'],
            'client' => $data['client_name'],
            'description' => $data['description'],
            'start' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status' => $data['status']
        ]);
 
        if ($success) {
            // Delete old departments
            $stmtDelDept = $this->db->prepare("DELETE FROM project_departments WHERE project_id = :project_id");
            $stmtDelDept->execute(['project_id' => $id]);
 
            // Insert new departments
            if (!empty($data['role_ids'])) {
                $stmtInsDept = $this->db->prepare("INSERT INTO project_departments (id, project_id, role_id) VALUES (:id, :project_id, :role_id)");
                foreach ($data['role_ids'] as $roleId) {
                    $stmtInsDept->execute([
                        'id' => $this->generateUuid(),
                        'project_id' => $id,
                        'role_id' => $roleId
                    ]);
                }
            }

            // Delete old assignments
            $stmtDel = $this->db->prepare("DELETE FROM project_assignments WHERE project_id = :project_id");
            $stmtDel->execute(['project_id' => $id]);
 
            // Insert new assignments
            if (!empty($data['assigned_users'])) {
                $stmtIns = $this->db->prepare("INSERT INTO project_assignments (id, project_id, user_id) VALUES (:id, :project_id, :user_id)");
                foreach ($data['assigned_users'] as $userId) {
                    $stmtIns->execute([
                        'id' => $this->generateUuid(),
                        'project_id' => $id,
                        'user_id' => $userId
                    ]);
                }
            }
        }
 
        return $success;
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("UPDATE projects SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM projects WHERE deleted_at IS NULL");
        return $stmt->fetchColumn();
    }

    public function listActiveByStaff($userId)
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.* 
            FROM projects p
            JOIN tasks t ON p.id = t.project_id
            WHERE t.assigned_to = :user_id 
            AND t.status != 'completed'
            AND t.deleted_at IS NULL
            AND p.deleted_at IS NULL
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

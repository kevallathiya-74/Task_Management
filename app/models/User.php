<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.slug as role_slug, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = :username AND u.deleted_at IS NULL 
            LIMIT 1
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.slug as role_slug, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email AND u.deleted_at IS NULL 
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.slug as role_slug, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = :id AND u.deleted_at IS NULL
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function listAll($filters = [])
    {
        $sql = "
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.deleted_at IS NULL
        ";
        
        $params = [];
        if (!empty($filters['role_id'])) {
            $sql .= " AND u.role_id = :role_id";
            $params['role_id'] = $filters['role_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $id = $this->generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO users (id, role_id, role, full_name, username, email, password_hash, status) 
            VALUES (:id, :role_id, :role, :full_name, :username, :email, :password_hash, :status)
        ");
        
        $result = $stmt->execute([
            'id' => $id,
            'role_id' => $data['role_id'],
            'role' => $data['role'] ?? null,
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'status' => $data['status'] ?? 'active'
        ]);

        return $result ? $id : false;
    }

    public function update($id, $data)
    {
        $fields = [
            'role_id = :role_id',
            'role = :role',
            'full_name = :full_name',
            'username = :username',
            'email = :email',
            'status = :status'
        ];
        
        $params = [
            'id' => $id,
            'role_id' => $data['role_id'],
            'role' => $data['role'] ?? null,
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'status' => $data['status']
        ];

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateLastLogin($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
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

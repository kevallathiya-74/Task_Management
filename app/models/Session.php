<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Session
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $token)
    {
        $id = $this->generateUuid();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        $stmt = $this->db->prepare("
            INSERT INTO sessions (id, user_id, session_token, user_agent, expires_at) 
            VALUES (:id, :user_id, :token, :ua, :expires)
        ");
        
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'token' => $token,
            'ua' => $userAgent,
            'expires' => $expiresAt
        ]);
    }

    public function deleteByToken($token)
    {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE session_token = :token");
        return $stmt->execute(['token' => $token]);
    }

    public function deleteByUserId($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }

    public function isValid($token)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM sessions WHERE session_token = :token AND expires_at > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetchColumn() > 0;
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

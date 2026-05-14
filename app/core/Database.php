<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = config('database.connections.mysql');
        
        $host = $config['host'] ?? '127.0.0.1';
        $db = $config['database'] ?? '';
        $user = $config['username'] ?? 'root';
        $pass = $config['password'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        $port = $config['port'] ?? '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            if (config('app.debug', false)) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection failed. Please contact administrator.");
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Prevent cloning and unserializing
    private function __clone() {}
    public function __wakeup() {}
}

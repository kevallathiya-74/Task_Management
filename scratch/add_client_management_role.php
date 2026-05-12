<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $stmt = $db->prepare("INSERT INTO roles (id, name, slug) VALUES (:id, :name, :slug)");
    $stmt->execute([
        'id' => $id,
        'name' => 'Client Management',
        'slug' => 'client_management'
    ]);
    
    echo "Client Management role added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
    print_r($roles);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

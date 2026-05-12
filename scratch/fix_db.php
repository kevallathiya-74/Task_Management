<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if due_time column exists
    $stmt = $db->query("SHOW COLUMNS FROM tasks LIKE 'due_time'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $db->exec("ALTER TABLE tasks ADD COLUMN due_time TIME NULL AFTER due_date");
        echo "Added due_time column.\n";
    } else {
        echo "due_time column already exists.\n";
    }
    
    // Also ensure due_date is DATETIME or at least DATE
    // Actually let's just make sure it's DATETIME to be safe
    $db->exec("ALTER TABLE tasks MODIFY COLUMN due_date DATETIME NULL");
    echo "Modified due_date to DATETIME.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

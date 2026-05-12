<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Update tasks table
    $columns = $db->query("SHOW COLUMNS FROM tasks")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('is_completed', $columns)) {
        $db->exec("ALTER TABLE tasks ADD COLUMN is_completed TINYINT(1) DEFAULT 0 AFTER status");
    }
    if (!in_array('is_incomplete', $columns)) {
        $db->exec("ALTER TABLE tasks ADD COLUMN is_incomplete TINYINT(1) DEFAULT 0 AFTER is_completed");
    }
    if (!in_array('completed_at', $columns)) {
        $db->exec("ALTER TABLE tasks ADD COLUMN completed_at TIMESTAMP NULL AFTER is_incomplete");
    }
    if (!in_array('admin_alert_sent', $columns)) {
        $db->exec("ALTER TABLE tasks ADD COLUMN admin_alert_sent TINYINT(1) DEFAULT 0 AFTER completed_at");
    }
    
    echo "Tasks table updated.\n";

    // 2. Create task_alerts table
    $db->exec("CREATE TABLE IF NOT EXISTS task_alerts (
        id CHAR(36) PRIMARY KEY,
        task_id CHAR(36) NOT NULL,
        user_id CHAR(36) NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");
    
    echo "task_alerts table created.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

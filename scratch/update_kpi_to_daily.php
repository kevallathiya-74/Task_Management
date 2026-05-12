<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Check if kpi_records exists and update it
    $db->exec("DROP TABLE IF EXISTS kpi_records");
    
    $db->exec("CREATE TABLE kpi_records (
        id CHAR(36) PRIMARY KEY,
        user_id CHAR(36) NOT NULL,
        kpi_date DATE NOT NULL,
        productivity_score DECIMAL(5,2) DEFAULT 0,
        quality_score DECIMAL(5,2) DEFAULT 0,
        discipline_score DECIMAL(5,2) DEFAULT 0,
        communication_score DECIMAL(5,2) DEFAULT 0,
        growth_score DECIMAL(5,2) DEFAULT 0,
        weighted_total_score DECIMAL(5,2) DEFAULT 0,
        salary_approval_percentage DECIMAL(5,2) DEFAULT 0,
        performance_status VARCHAR(50) DEFAULT 'Average',
        admin_notes TEXT NULL,
        created_by CHAR(36) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY user_kpi_date (user_id, kpi_date),
        INDEX idx_user_id (user_id),
        INDEX idx_kpi_date (kpi_date),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB;");
    
    echo "kpi_records table updated to DAILY format.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

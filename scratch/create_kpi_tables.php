<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/core/Database.php';

loadEnv(__DIR__ . '/../.env');

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Create kpi_records table
    $db->exec("CREATE TABLE IF NOT EXISTS kpi_records (
        id CHAR(36) PRIMARY KEY,
        user_id CHAR(36) NOT NULL,
        month TINYINT NOT NULL,
        year SMALLINT NOT NULL,
        productivity_score DECIMAL(5,2) DEFAULT 0,
        quality_score DECIMAL(5,2) DEFAULT 0,
        discipline_score DECIMAL(5,2) DEFAULT 0,
        communication_score DECIMAL(5,2) DEFAULT 0,
        growth_score DECIMAL(5,2) DEFAULT 0,
        total_score DECIMAL(5,2) DEFAULT 0,
        salary_approval_percentage DECIMAL(5,2) DEFAULT 0,
        performance_status VARCHAR(50) DEFAULT 'Average',
        admin_notes TEXT NULL,
        created_by CHAR(36) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY user_month_year (user_id, month, year),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB;");
    
    echo "kpi_records table created.\n";

    // 2. Create kpi_reports table
    $db->exec("CREATE TABLE IF NOT EXISTS kpi_reports (
        id CHAR(36) PRIMARY KEY,
        user_id CHAR(36) NOT NULL,
        report_type VARCHAR(50) NOT NULL,
        report_duration VARCHAR(50) NOT NULL,
        generated_by CHAR(36) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB;");
    
    echo "kpi_reports table created.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

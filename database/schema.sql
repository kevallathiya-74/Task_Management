-- Task Management System Database Schema

CREATE DATABASE IF NOT EXISTS task_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE task_management;

-- Roles Table
CREATE TABLE IF NOT EXISTS roles (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,
    role_id CHAR(36) NOT NULL,
    role VARCHAR(50) NULL,
    full_name VARCHAR(150) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NULL,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX (status)
) ENGINE=InnoDB;

-- Sessions Table
CREATE TABLE IF NOT EXISTS sessions (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    user_agent TEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Projects Table
CREATE TABLE IF NOT EXISTS projects (
    id CHAR(36) PRIMARY KEY,
    created_by CHAR(36) NOT NULL,
    role_id CHAR(36) NOT NULL, -- Department role
    project_name VARCHAR(255) UNIQUE NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    start_date DATE NOT NULL,
    deadline DATE NOT NULL,
    status ENUM('pending', 'active', 'completed', 'cancelled', 'other') DEFAULT 'pending',
    status_notes TEXT NULL, -- To store reason if status is 'other'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX (status),
    INDEX (deadline)
) ENGINE=InnoDB;

-- Tasks Table
CREATE TABLE IF NOT EXISTS tasks (
    id CHAR(36) PRIMARY KEY,
    project_id CHAR(36) NOT NULL,
    assigned_to CHAR(36) NOT NULL,
    role_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'in_progress', 'review', 'completed', 'other') DEFAULT 'pending',
    status_notes TEXT NULL,
    progress_percentage TINYINT UNSIGNED DEFAULT 0,
    due_date DATETIME NOT NULL,
    due_time TIME NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX (status),
    INDEX (due_date)
) ENGINE=InnoDB;

-- Task Comments Table
CREATE TABLE IF NOT EXISTS task_comments (
    id CHAR(36) PRIMARY KEY,
    task_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id CHAR(36) NOT NULL,
    action VARCHAR(100) NOT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id CHAR(36) PRIMARY KEY,
    setting_key VARCHAR(150) UNIQUE NOT NULL,
    setting_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed Data for Roles
INSERT INTO roles (id, name, slug) VALUES 
('6f9e836b-67a4-4770-96f1-67e39a5f4581', 'Admin', 'admin'),
('b5c3e6d2-7f1a-4d9e-8c3b-5a6f9e7d2c1b', 'Facebook & Google Ads', 'ads'),
('d4f2a1b7-e9c3-4a8d-b7f1-2c6e9a3d4f5b', 'Website Design & Development', 'web_dev'),
('a1b2c3d4-e5f6-4a7b-8c9d-0e1f2a3b4c5d', 'Graphics Design', 'graphics'),
('f5e4d3c2-b1a0-4f9e-8d7c-6b5a4f3e2d1c', 'Search Engine Optimization', 'seo'),
('c7b8a9d0-e1f2-4a3b-8c4d-5e6f7a8b9c0d', 'Video Editing', 'video_editing'),
('9d0e1f2a-3b4c-4d5e-8f6a-7b8c9d0e1f2a', 'Social Media Management', 'smm'),
('3b4c5d6e-7f8a-4b9c-bd0e-1f2a3b4c5d6e', 'AI Video Making', 'ai_video');

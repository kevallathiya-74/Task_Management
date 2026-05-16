-- Migration to add 'daily' to recurring_type ENUM
-- Safe ALTER query to preserve existing data

ALTER TABLE tasks MODIFY COLUMN recurring_type ENUM('daily', 'weekly', 'monthly') NULL;
ALTER TABLE task_recurring_logs MODIFY COLUMN recurring_type ENUM('daily', 'weekly', 'monthly') NOT NULL;

-- Add reschedule columns to appointments table if they don't exist
-- Run this if you haven't already added the reschedule columns

-- Check if columns exist first, then add them
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_NAME = 'appointments' 
     AND COLUMN_NAME = 'proposed_datetime' 
     AND TABLE_SCHEMA = DATABASE()) = 0,
    'ALTER TABLE appointments 
     ADD COLUMN proposed_datetime DATETIME NULL,
     ADD COLUMN proposed_by ENUM(\'doctor\',\'patient\') NULL,
     ADD COLUMN reschedule_status ENUM(\'none\',\'pending_patient\',\'pending_doctor\',\'accepted\',\'declined\') NOT NULL DEFAULT \'none\',
     ADD COLUMN reschedule_message VARCHAR(255) NULL,
     ADD COLUMN reschedule_expires_at DATETIME NULL;',
    'SELECT "Reschedule columns already exist";'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
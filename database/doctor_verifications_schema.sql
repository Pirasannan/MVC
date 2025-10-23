-- Doctor Profile Verification System Database Schema
-- Run this SQL in your MySQL database to create the verification table

-- Create doctor_verifications table
CREATE TABLE IF NOT EXISTS doctor_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    photo_path VARCHAR(500) NOT NULL,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    verified_at DATETIME NULL,
    rejection_reason TEXT NULL,
    
    -- Foreign key constraint to Users table
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    
    -- Indexes for performance optimization
    INDEX idx_user_id (user_id),
    INDEX idx_status (verification_status),
    INDEX idx_uploaded_at (uploaded_at),
    
    -- Ensure one verification record per user
    UNIQUE KEY unique_user_verification (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add table comment
ALTER TABLE doctor_verifications 
COMMENT = 'Stores doctor profile verification documents and status';

-- Show table structure
DESCRIBE doctor_verifications;
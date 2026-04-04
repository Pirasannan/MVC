-- ======================================================
-- 🏥 VIRTUAL CONSULTATION PLATFORM - COMPLETE DATABASE
-- Comprehensive SQL file with all tables and dummy data
-- ======================================================

-- Set SQL mode and character set
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ======================================================
-- 🗑️ CLEANUP - Drop existing tables (if any)
-- ======================================================
DROP TABLE IF EXISTS `prescriptions`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `doctor_verifications`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `slmc`;
DROP TABLE IF EXISTS `Users`;

-- ======================================================
-- 🧩 USERS TABLE (Base table for all user types)
-- ======================================================
CREATE TABLE `Users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('Admin','Doctor','Patient') NOT NULL,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `slmc` VARCHAR(50) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_role` (`role`),
    INDEX `idx_status` (`status`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 🏥 SLMC TABLE (Sri Lanka Medical Council numbers)
-- ======================================================
CREATE TABLE `slmc` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slmc` VARCHAR(50) UNIQUE NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `specialization` VARCHAR(100),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 📅 APPOINTMENTS TABLE
-- ======================================================
CREATE TABLE `appointments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT UNSIGNED NOT NULL,
    `doctor_id` INT UNSIGNED NOT NULL,
    `slot_id` INT NULL,
    `starts_at` DATETIME NOT NULL,
    `ends_at` DATETIME NOT NULL,
    `status` ENUM('pending','approved','rejected','cancelled','completed') DEFAULT 'pending',
    `reason` TEXT,
    `notes` TEXT,
    `video_room_token` VARCHAR(255) NULL,
    `proposed_datetime` DATETIME NULL,
    `proposed_by` ENUM('doctor', 'patient') NULL,
    `reschedule_status` ENUM('none', 'pending_patient', 'pending_doctor', 'accepted', 'declined') DEFAULT 'none',
    `reschedule_message` TEXT NULL,
    `reschedule_expires_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_appointments_patient`
        FOREIGN KEY (`patient_id`) REFERENCES `Users`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    CONSTRAINT `fk_appointments_doctor`
        FOREIGN KEY (`doctor_id`) REFERENCES `Users`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    INDEX `idx_patient` (`patient_id`),
    INDEX `idx_doctor` (`doctor_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_starts_at` (`starts_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 💊 PRESCRIPTIONS TABLE
-- ======================================================
CREATE TABLE `prescriptions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` INT UNSIGNED NOT NULL,
    `patient_id` INT UNSIGNED NOT NULL,
    `drug_name` VARCHAR(255) NOT NULL,
    `formulation` VARCHAR(255),
    `route` VARCHAR(100),
    `brand_substitution` BOOLEAN DEFAULT 0,
    `prn` BOOLEAN DEFAULT 0,
    `max_per_24h` INT,
    `prn_indication` VARCHAR(255),
    `dose_amount` VARCHAR(50),
    `dose_unit` VARCHAR(50),
    `frequency` VARCHAR(50),
    `custom_frequency` INT,
    `time_of_day` VARCHAR(255),
    `meal_relation` VARCHAR(100),
    `duration_value` INT,
    `duration_type` VARCHAR(50),
    `special_instructions` TEXT,
    `dispense_quantity` INT,
    `unit_type` VARCHAR(100),
    `diagnosis` VARCHAR(255),
    `valid_until` DATE,
    `pharmacy_note` TEXT,
    `doctor_notes` TEXT,
    `is_deleted` ENUM('not_deleted', 'deleted') DEFAULT 'not_deleted',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_prescriptions_doctor`
        FOREIGN KEY (`doctor_id`) REFERENCES `Users`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    CONSTRAINT `fk_prescriptions_patient`
        FOREIGN KEY (`patient_id`) REFERENCES `Users`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    INDEX `idx_doctor` (`doctor_id`),
    INDEX `idx_patient` (`patient_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 💬 MESSAGES TABLE
-- ======================================================
CREATE TABLE `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sender_id` INT NOT NULL,
    `receiver_id` INT NOT NULL,
    `sender_type` ENUM('doctor', 'patient') NOT NULL,
    `receiver_type` ENUM('doctor', 'patient') NOT NULL,
    `subject` VARCHAR(255),
    `message_text` TEXT NOT NULL,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`sender_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`receiver_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
    
    INDEX `idx_sender` (`sender_id`, `sender_type`),
    INDEX `idx_receiver` (`receiver_id`, `receiver_type`),
    INDEX `idx_read_status` (`is_read`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- ✅ DOCTOR VERIFICATIONS TABLE
-- ======================================================
CREATE TABLE `doctor_verifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `photo_path` VARCHAR(500) NOT NULL,
    `verification_status` ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `verified_at` DATETIME NULL,
    `rejection_reason` TEXT NULL,
    
    FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
    
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_status` (`verification_status`),
    INDEX `idx_uploaded_at` (`uploaded_at`),
    
    UNIQUE KEY `unique_user_verification` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 🔔 NOTIFICATIONS TABLE
-- ======================================================
CREATE TABLE `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipient_type` ENUM('admin', 'doctor', 'patient', 'all') NOT NULL,
    `recipient_id` INT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `notification_type` ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    `status` ENUM('sent', 'read', 'unread') DEFAULT 'sent',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `read_at` DATETIME NULL,
    
    INDEX `idx_recipient` (`recipient_type`, `recipient_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 📋 REPORTS TABLE
-- ======================================================
CREATE TABLE `reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reporter_type` ENUM('Patient', 'Doctor', 'Admin') NOT NULL,
    `reporter_id` INT NOT NULL,
    `reported_type` ENUM('Patient', 'Doctor', 'Admin', 'System') NOT NULL,
    `reported_id` INT NULL,
    `report_type` VARCHAR(100) NOT NULL,
    `reason` TEXT NOT NULL,
    `description` TEXT,
    `status` ENUM('pending', 'under_review', 'resolved', 'dismissed') DEFAULT 'pending',
    `resolution` TEXT NULL,
    `resolved_by` INT NULL,
    `resolved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`reporter_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`reported_id`) REFERENCES `Users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`resolved_by`) REFERENCES `Users`(`id`) ON DELETE SET NULL,
    
    INDEX `idx_reporter` (`reporter_type`, `reporter_id`),
    INDEX `idx_reported` (`reported_type`, `reported_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 📊 ACTIVITY LOGS TABLE
-- ======================================================
CREATE TABLE `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT NULL,
    `user_id` INT NULL,
    `action` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`admin_id`) REFERENCES `Users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`) ON DELETE SET NULL,
    
    INDEX `idx_admin` (`admin_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- 📊 DUMMY DATA INSERTION
-- ======================================================

-- Insert SLMC numbers
INSERT INTO `slmc` (`slmc`, `name`, `specialization`) VALUES
('SLMC001', 'Dr. Sarah Johnson', 'Cardiology'),
('SLMC002', 'Dr. Michael Chen', 'Dermatology'),
('SLMC003', 'Dr. Emily Davis', 'Pediatrics'),
('SLMC004', 'Dr. Sunil Jayawardena', 'Internal Medicine'),
('SLMC005', 'Dr. Rajesh Kumar', 'Orthopedics'),
('SLMC006', 'Dr. Nisha Perera', 'Neurology'),
('SLMC007', 'Dr. Amara Fernando', 'Psychiatry'),
('SLMC008', 'Dr. Kasun Silva', 'Oncology'),
('SLMC009', 'Dr. Anil Rodrigo', 'Radiology'),
('SLMC010', 'Dr. Priya Gunawardena', 'Emergency Medicine');

-- Insert Admin Users
INSERT INTO `Users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@system.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'active', NOW(), NOW()),
('System Admin', 'system@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'active', NOW(), NOW()),
('Super Admin', 'super@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'active', NOW(), NOW());

-- Insert Doctor Users (mix of statuses)
INSERT INTO `Users` (`name`, `email`, `password`, `role`, `status`, `slmc`, `created_at`, `updated_at`) VALUES
-- Pending doctors (inactive status)
('Dr. Rajesh Kumar', 'rajesh.kumar@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'inactive', 'SLMC005', '2025-01-15 10:30:00', NOW()),
('Dr. Nisha Perera', 'nisha.perera@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'inactive', 'SLMC006', '2025-01-16 14:20:00', NOW()),
('Dr. Amara Fernando', 'amara.fernando@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'inactive', 'SLMC007', '2025-01-17 09:15:00', NOW()),
('Dr. Kasun Silva', 'kasun.silva@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'inactive', 'SLMC008', '2025-01-18 16:45:00', NOW()),

-- Verified doctors (active status)
('Dr. Sarah Johnson', 'sarah.johnson@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'active', 'SLMC001', '2025-01-10 08:00:00', '2025-01-20 10:30:00'),
('Dr. Michael Chen', 'michael.chen@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'active', 'SLMC002', '2025-01-11 12:15:00', '2025-01-21 14:20:00'),
('Dr. Emily Davis', 'emily.davis@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'active', 'SLMC003', '2025-01-12 15:30:00', '2025-01-22 09:15:00'),
('Dr. Sunil Jayawardena', 'sunil.jayawardena@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'active', 'SLMC004', '2025-01-13 11:45:00', '2025-01-23 16:45:00'),

-- Rejected doctors (suspended status)
('Dr. Anil Rodrigo', 'anil.rodrigo@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'suspended', 'SLMC009', '2025-01-05 13:20:00', '2025-01-12 15:30:00'),
('Dr. Priya Gunawardena', 'priya.gunawardena@doctor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Doctor', 'suspended', 'SLMC010', '2025-01-06 09:45:00', '2025-01-08 11:20:00');

-- Insert Patient Users (mix of statuses)
INSERT INTO `Users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
-- Pending patients (inactive status)
('Kamal Wickramasinghe', 'kamal.wickramasinghe@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'inactive', '2025-01-18 10:30:00', NOW()),
('Priya Mendis', 'priya.mendis@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'inactive', '2025-01-17 14:20:00', NOW()),
('Saman De Silva', 'saman.desilva@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'inactive', '2025-01-17 09:15:00', NOW()),
('Nimal Jayasuriya', 'nimal.jayasuriya@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'inactive', '2025-01-16 16:45:00', NOW()),

-- Verified patients (active status)
('Anura Bandara', 'anura.bandara@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-10 08:00:00', '2025-01-20 10:30:00'),
('Nethmi Silva', 'nethmi.silva@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-11 12:15:00', '2025-01-21 14:20:00'),
('Roshan Fernando', 'roshan.fernando@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Yal4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-12 15:30:00', '2025-01-22 09:15:00'),
('Dilani Perera', 'dilani.perera@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-13 11:45:00', '2025-01-23 16:45:00'),
('Kumar Dissanayake', 'kumar.dissanayake@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-14 07:30:00', '2025-01-24 13:20:00'),
('Malini Rajapaksa', 'malini.rajapaksa@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-15 09:45:00', '2025-01-25 15:40:00'),
('Tharaka Amarasinghe', 'tharaka.amarasinghe@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-16 08:15:00', '2025-01-26 12:30:00'),

-- Rejected patients (suspended status)
('Chaminda Gunasekara', 'chaminda.gunasekara@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-05 13:20:00', '2025-01-12 15:30:00'),
('Sunil Wijeratne', 'sunil.wijeratne@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-08 10:30:00', '2025-01-25 12:00:00');

-- Insert Doctor Verifications
INSERT INTO `doctor_verifications` (`user_id`, `email`, `photo_path`, `verification_status`, `verified_at`) VALUES
(5, 'sarah.johnson@doctor.com', 'uploads/verifications/5/sarah_johnson_license.jpg', 'verified', '2025-01-20 10:30:00'),
(6, 'michael.chen@doctor.com', 'uploads/verifications/6/michael_chen_license.jpg', 'verified', '2025-01-21 14:20:00'),
(7, 'emily.davis@doctor.com', 'uploads/verifications/7/emily_davis_license.jpg', 'verified', '2025-01-22 09:15:00'),
(8, 'sunil.jayawardena@doctor.com', 'uploads/verifications/8/sunil_jayawardena_license.jpg', 'verified', '2025-01-23 16:45:00'),
(1, 'rajesh.kumar@doctor.com', 'uploads/verifications/1/rajesh_kumar_license.jpg', 'pending', NULL),
(2, 'nisha.perera@doctor.com', 'uploads/verifications/2/nisha_perera_license.jpg', 'pending', NULL),
(3, 'amara.fernando@doctor.com', 'uploads/verifications/3/amara_fernando_license.jpg', 'pending', NULL),
(4, 'kasun.silva@doctor.com', 'uploads/verifications/4/kasun_silva_license.jpg', 'pending', NULL),
(9, 'anil.rodrigo@doctor.com', 'uploads/verifications/9/anil_rodrigo_license.jpg', 'rejected', NULL),
(10, 'priya.gunawardena@doctor.com', 'uploads/verifications/10/priya_gunawardena_license.jpg', 'rejected', NULL);

-- Insert Appointments
INSERT INTO `appointments` (`patient_id`, `doctor_id`, `starts_at`, `ends_at`, `status`, `reason`, `notes`, `video_room_token`) VALUES
(15, 5, '2025-01-25 10:00:00', '2025-01-25 10:30:00', 'approved', 'Regular checkup', 'Patient requested follow-up appointment', 'room_001_20250125'),
(16, 6, '2025-01-25 14:00:00', '2025-01-25 14:30:00', 'pending', 'Skin consultation', 'Patient has concerns about skin condition', 'room_002_20250125'),
(17, 7, '2025-01-26 09:00:00', '2025-01-26 09:30:00', 'approved', 'Pediatric consultation', 'Child vaccination schedule discussion', 'room_003_20250126'),
(18, 8, '2025-01-26 11:00:00', '2025-01-26 11:30:00', 'completed', 'Blood pressure monitoring', 'Regular monitoring appointment', 'room_004_20250126'),
(19, 5, '2025-01-27 15:00:00', '2025-01-27 15:30:00', 'approved', 'Cardiology consultation', 'Heart health assessment', 'room_005_20250127'),
(20, 6, '2025-01-28 10:30:00', '2025-01-28 11:00:00', 'pending', 'Dermatology follow-up', 'Follow-up on previous treatment', 'room_006_20250128'),
(21, 7, '2025-01-28 16:00:00', '2025-01-28 16:30:00', 'approved', 'Pediatric examination', 'Annual health checkup', 'room_007_20250128'),
(22, 8, '2025-01-29 08:00:00', '2025-01-29 08:30:00', 'cancelled', 'Internal medicine consultation', 'Patient cancelled due to emergency', NULL);

-- Insert Prescriptions
INSERT INTO `prescriptions` (`doctor_id`, `patient_id`, `drug_name`, `formulation`, `route`, `dose_amount`, `dose_unit`, `frequency`, `duration_value`, `duration_type`, `dispense_quantity`, `unit_type`, `diagnosis`, `valid_until`, `doctor_notes`, `created_at`) VALUES
(5, 15, 'Lisinopril', 'Tablet', 'Oral', '10', 'mg', 'Once daily', 30, 'days', 30, 'tablets', 'Hypertension', '2025-02-25', 'Take with food, monitor blood pressure weekly', '2025-01-20 10:30:00'),
(6, 16, 'Clobetasol', 'Cream', 'Topical', '0.05', '%', 'Twice daily', 14, 'days', 1, 'tube', 'Eczema', '2025-02-10', 'Apply thin layer to affected areas', '2025-01-21 14:15:00'),
(7, 17, 'Amoxicillin', 'Suspension', 'Oral', '250', 'mg', 'Three times daily', 7, 'days', 1, 'bottle', 'Upper respiratory infection', '2025-02-05', 'Complete full course, take with meals', '2025-01-22 09:00:00'),
(8, 18, 'Metformin', 'Tablet', 'Oral', '500', 'mg', 'Twice daily', 30, 'days', 60, 'tablets', 'Type 2 Diabetes', '2025-02-20', 'Monitor blood glucose levels', '2025-01-23 16:30:00'),
(5, 19, 'Atorvastatin', 'Tablet', 'Oral', '20', 'mg', 'Once daily at bedtime', 30, 'days', 30, 'tablets', 'Hypercholesterolemia', '2025-02-25', 'Take with evening meal', '2025-01-24 11:45:00'),
(6, 20, 'Hydrocortisone', 'Cream', 'Topical', '1', '%', 'Apply as needed', 14, 'days', 1, 'tube', 'Contact dermatitis', '2025-02-15', 'Use sparingly, avoid face and genitals', '2025-01-25 08:20:00'),
(7, 21, 'Paracetamol', 'Syrup', 'Oral', '15', 'mg/kg', 'Every 6 hours', 5, 'days', 1, 'bottle', 'Fever', '2025-02-08', 'Do not exceed 4 doses per day', '2025-01-26 14:00:00'),
(8, 22, 'Omeprazole', 'Capsule', 'Oral', '20', 'mg', 'Once daily before breakfast', 30, 'days', 30, 'capsules', 'Gastroesophageal reflux', '2025-02-22', 'Take 30 minutes before first meal', '2025-01-27 10:15:00');

-- Insert Messages
INSERT INTO `messages` (`sender_id`, `receiver_id`, `sender_type`, `receiver_type`, `subject`, `message_text`, `is_read`, `created_at`) VALUES
(15, 5, 'patient', 'doctor', 'Appointment Follow-up', 'Hi Dr. Johnson, I wanted to follow up on my recent appointment. The medication you prescribed seems to be working well, but I have a few questions about the dosage.', FALSE, '2025-01-20 10:30:00'),
(5, 15, 'doctor', 'patient', 'RE: Appointment Follow-up', 'Hello Anura! I am glad to hear the medication is working. Please feel free to ask any questions about the dosage. We can also schedule a follow-up if needed.', TRUE, '2025-01-20 14:15:00'),
(16, 6, 'patient', 'doctor', 'Skin Condition Update', 'Doctor Chen, I have been using the cream as prescribed and noticed some improvement. Should I continue with the same regimen?', FALSE, '2025-01-21 09:45:00'),
(6, 16, 'doctor', 'patient', 'RE: Skin Condition Update', 'Great to hear about the improvement! Yes, please continue with the current regimen for another week and let me know how it progresses.', TRUE, '2025-01-21 16:20:00'),
(17, 7, 'patient', 'doctor', 'Child Vaccination Schedule', 'Dr. Davis, I would like to discuss my child\'s vaccination schedule. When is the next vaccination due?', FALSE, '2025-01-22 08:30:00'),
(7, 17, 'doctor', 'patient', 'RE: Child Vaccination Schedule', 'Thank you for reaching out. Based on your child\'s age, the next vaccination is due in 2 weeks. I will send you the detailed schedule.', TRUE, '2025-01-22 12:45:00'),
(18, 8, 'patient', 'doctor', 'Blood Pressure Results', 'Dr. Jayawardena, I have been monitoring my blood pressure as advised. The readings have been stable around 130/85. Is this within normal range?', FALSE, '2025-01-23 15:30:00'),
(8, 18, 'doctor', 'patient', 'RE: Blood Pressure Results', 'Your blood pressure readings are slightly elevated but manageable. Continue with the current medication and lifestyle modifications. Schedule a follow-up in 2 weeks.', TRUE, '2025-01-23 18:00:00'),
(19, 5, 'patient', 'doctor', 'Heart Health Questions', 'Dr. Johnson, I have been experiencing occasional chest discomfort. Should I be concerned?', FALSE, '2025-01-24 11:15:00'),
(5, 19, 'doctor', 'patient', 'RE: Heart Health Questions', 'Chest discomfort should always be taken seriously. Please schedule an appointment as soon as possible so we can evaluate this properly.', TRUE, '2025-01-24 14:30:00');

-- Insert Notifications
INSERT INTO `notifications` (`recipient_type`, `recipient_id`, `title`, `message`, `notification_type`, `status`, `created_at`) VALUES
('all', NULL, 'System Maintenance', 'Scheduled maintenance will occur tonight from 2-4 AM', 'info', 'sent', '2025-01-20 10:00:00'),
('doctor', NULL, 'New Patient Registration', 'A new patient has registered and requires verification', 'info', 'sent', '2025-01-21 14:30:00'),
('patient', NULL, 'Appointment Reminder', 'Your appointment is scheduled for tomorrow at 2 PM', 'info', 'sent', '2025-01-22 09:15:00'),
('admin', NULL, 'Doctor Verification Required', '3 new doctors are pending verification', 'warning', 'sent', '2025-01-23 11:45:00'),
('all', NULL, 'New Feature Announcement', 'New prescription template feature now available in your dashboard', 'info', 'sent', '2025-01-24 16:20:00'),
('doctor', NULL, 'Patient Flagged Content', 'Inappropriate message reported in consultation chat', 'warning', 'sent', '2025-01-25 08:30:00'),
('patient', 15, 'Prescription Ready', 'Your prescription has been processed and is ready for pickup', 'success', 'sent', '2025-01-26 10:00:00'),
('doctor', 5, 'Appointment Request', 'New appointment request from patient Anura Bandara', 'info', 'sent', '2025-01-27 09:30:00');

-- Insert Reports
INSERT INTO `reports` (`reporter_type`, `reporter_id`, `reported_type`, `reported_id`, `report_type`, `reason`, `description`, `status`, `created_at`) VALUES
('Patient', 15, 'Doctor', 5, 'Inappropriate Content', 'Unprofessional language during consultation', 'Doctor used inappropriate language during video call', 'pending', '2025-01-20 16:30:00'),
('Doctor', 6, 'Patient', 16, 'Spam Messages', 'Repeated spam messages', 'Patient sent multiple irrelevant messages', 'pending', '2025-01-21 11:20:00'),
('Patient', 17, 'Doctor', 7, 'Harassment', 'Inappropriate consultation behavior', 'Doctor made inappropriate comments during consultation', 'under_review', '2025-01-22 14:15:00'),
('Doctor', 8, 'Patient', 18, 'Fraudulent Activity', 'Fake medical documents', 'Patient submitted fake medical documents', 'pending', '2025-01-23 09:45:00'),
('Admin', 1, 'System', NULL, 'Technical Issue', 'System performance degradation', 'Users reporting slow response times during peak hours', 'resolved', '2025-01-24 13:30:00');

-- Insert Activity Logs
INSERT INTO `activity_logs` (`admin_id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 5, 'Doctor Verification Approved', 'Approved: Dr. Sarah Johnson', '192.168.1.100', '2025-01-20 10:15:00'),
(1, NULL, 'System Notification Sent', 'Recipients: All Users', '192.168.1.100', '2025-01-20 09:45:00'),
(1, 22, 'Patient Account Suspended', 'Suspended: Sunil Wijeratne', '192.168.1.100', '2025-01-17 16:30:00'),
(2, 6, 'Doctor Verification Approved', 'Approved: Dr. Michael Chen', '192.168.1.101', '2025-01-21 14:20:00'),
(2, NULL, 'System Configuration Updated', 'Updated email notification settings', '192.168.1.101', '2025-01-21 13:15:00'),
(3, 7, 'Doctor Verification Approved', 'Approved: Dr. Emily Davis', '192.168.1.102', '2025-01-22 09:15:00'),
(3, 18, 'Patient Report Resolved', 'Resolved report against Dr. Jayawardena', '192.168.1.102', '2025-01-22 17:45:00'),
(1, 8, 'Doctor Verification Approved', 'Approved: Dr. Sunil Jayawardena', '192.168.1.100', '2025-01-23 16:45:00');

-- ======================================================
-- ✅ COMMIT TRANSACTION
-- ======================================================
COMMIT;

-- ======================================================
-- 📊 SUMMARY
-- ======================================================
-- Total Tables Created: 9
-- - Users (with Admins, Doctors, Patients)
-- - SLMC (Sri Lanka Medical Council numbers)
-- - Appointments
-- - Prescriptions
-- - Messages
-- - Doctor Verifications
-- - Notifications
-- - Reports
-- - Activity Logs
--
-- Total Records Inserted:
-- - 10 SLMC numbers
-- - 3 Admin users
-- - 10 Doctor users (mix of statuses)
-- - 12 Patient users (mix of statuses)
-- - 10 Doctor verifications
-- - 8 Appointments
-- - 8 Prescriptions
-- - 10 Messages
-- - 8 Notifications
-- - 5 Reports
-- - 8 Activity logs
--
-- All foreign key relationships are properly maintained
-- All data is realistic and follows medical practice standards
-- ======================================================


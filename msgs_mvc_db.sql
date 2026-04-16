-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 08:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `video_room_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `proposed_datetime` datetime DEFAULT NULL,
  `proposed_by` enum('doctor','patient') DEFAULT NULL,
  `reschedule_status` enum('none','pending_patient','pending_doctor','accepted','declined') NOT NULL DEFAULT 'none',
  `reschedule_message` varchar(255) DEFAULT NULL,
  `reschedule_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `slot_id`, `starts_at`, `ends_at`, `status`, `reason`, `notes`, `video_room_token`, `created_at`, `updated_at`, `proposed_datetime`, `proposed_by`, `reschedule_status`, `reschedule_message`, `reschedule_expires_at`) VALUES
(14, 1, 2, 1, '2025-10-25 09:00:00', '2025-10-25 09:30:00', 'approved', 'Headache', 'Discuss recurring migraines', 'ROOM123', '2025-10-24 03:47:15', '2025-10-24 03:47:15', NULL, NULL, 'none', NULL, NULL),
(15, 3, 2, 2, '2025-10-25 09:30:00', '2025-10-25 10:00:00', 'approved', 'General checkup', 'First appointment', 'ROOM124', '2025-10-24 03:47:15', '2026-02-10 06:58:26', NULL, NULL, 'none', NULL, NULL),
(16, 55, 5, 3, '2025-10-25 10:00:00', '2025-10-25 10:30:00', 'approved', 'Fever', 'Fever for 3 days', 'ROOM125', '2025-10-24 03:47:15', '2025-10-24 03:47:15', NULL, NULL, 'none', NULL, NULL),
(17, 56, 6, 4, '2025-10-25 11:00:00', '2025-10-25 11:30:00', 'completed', 'Cough', 'Persistent dry cough', 'ROOM126', '2025-10-24 03:47:15', '2025-10-24 03:47:15', NULL, NULL, 'none', NULL, NULL),
(18, 57, 6, 5, '2025-10-25 11:30:00', '2025-10-25 12:00:00', 'cancelled', 'Follow-up', 'Rescheduled by patient', 'ROOM127', '2025-10-24 03:47:15', '2025-10-24 03:47:15', NULL, NULL, 'none', NULL, NULL),
(19, 55, 2, 6, '2025-10-26 09:00:00', '2025-10-26 09:30:00', 'approved', 'Back pain', 'Mild lower back pain after lifting heavy objects.', 'ROOM200', '2025-10-24 03:51:02', '2025-10-24 03:51:02', NULL, NULL, 'none', NULL, NULL),
(20, 58, 2, 7, '2025-10-26 09:30:00', '2025-10-26 10:00:00', 'pending', 'Allergy', 'Skin rash consultation', 'ROOM201', '2025-10-24 03:51:02', '2026-02-10 06:58:19', '2025-10-30 13:32:00', 'doctor', 'pending_patient', '', '2026-02-12 12:28:19'),
(21, 59, 2, 8, '2025-10-26 10:00:00', '2025-10-26 10:30:00', 'cancelled', 'Follow-up', 'Patient cancelled due to travel.', 'ROOM202', '2025-10-24 03:51:02', '2025-10-24 03:51:02', NULL, NULL, 'none', NULL, NULL),
(22, 1, 5, 9, '2025-10-27 09:00:00', '2025-10-27 09:30:00', 'approved', 'Stomach Pain', 'Mild stomach discomfort for 2 days.', 'ROOM300', '2025-10-24 03:51:20', '2025-10-24 03:51:20', NULL, NULL, 'none', NULL, NULL),
(23, 1, 5, 10, '2025-10-27 09:30:00', '2025-10-27 10:00:00', 'pending', 'Diet Consultation', 'Wants advice on balanced meals.', 'ROOM301', '2025-10-24 03:51:20', '2026-04-16 06:08:04', NULL, NULL, 'declined', NULL, NULL),
(24, 1, 6, NULL, '2025-10-31 07:00:00', '2025-10-31 07:15:00', 'completed', 'Poor eyesight', NULL, NULL, '2025-10-24 04:18:04', '2026-04-06 03:12:47', NULL, NULL, 'none', NULL, NULL),
(25, 1, 6, NULL, '2025-10-28 19:18:00', '2025-10-28 19:33:00', 'completed', 'Headache', NULL, NULL, '2025-10-24 04:19:15', '2026-04-06 03:12:49', NULL, NULL, 'none', NULL, NULL),
(26, 1, 6, NULL, '2026-04-15 07:15:00', '2026-04-15 07:30:00', 'completed', '', NULL, NULL, '2026-04-06 03:11:32', '2026-04-16 05:49:48', NULL, NULL, 'none', NULL, NULL),
(27, 1, 6, NULL, '2026-04-16 06:30:00', '2026-04-16 06:45:00', 'approved', '', NULL, NULL, '2026-04-16 05:57:29', '2026-04-16 05:58:15', NULL, NULL, 'none', NULL, NULL),
(28, 64, 6, NULL, '2026-04-15 20:05:00', '2026-04-15 20:20:00', 'completed', '', NULL, NULL, '2026-04-04 14:31:55', '2026-04-15 10:47:19', NULL, NULL, 'none', NULL, NULL),
(29, 65, 6, NULL, '2026-04-16 16:21:00', '2026-04-16 16:36:00', 'approved', '', NULL, NULL, '2026-04-15 10:52:05', '2026-04-15 10:52:44', NULL, NULL, 'none', NULL, NULL);

--
-- Triggers `appointments`
--
DELIMITER $$
CREATE TRIGGER `trg_appt_approve_no_overlap` BEFORE UPDATE ON `appointments` FOR EACH ROW BEGIN
  IF NEW.status = 'approved' THEN
    IF EXISTS (
      SELECT 1 FROM appointments a
      WHERE a.id <> NEW.id
        AND a.doctor_id = NEW.doctor_id
        AND a.status = 'approved'
        AND (NEW.starts_at < a.ends_at AND NEW.ends_at > a.starts_at)
    ) THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Overlap with an already approved appointment';
    END IF;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `availability_slots`
--

CREATE TABLE `availability_slots` (
  `id` int(10) NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `slot_start` datetime NOT NULL,
  `slot_end` datetime NOT NULL,
  `is_booked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability_slots`
--

INSERT INTO `availability_slots` (`id`, `doctor_id`, `slot_start`, `slot_end`, `is_booked`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-10-25 09:00:00', '2025-10-25 09:30:00', 1, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(2, 2, '2025-10-25 09:30:00', '2025-10-25 10:00:00', 0, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(3, 5, '2025-10-25 10:00:00', '2025-10-25 10:30:00', 1, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(4, 6, '2025-10-25 11:00:00', '2025-10-25 11:30:00', 1, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(5, 6, '2025-10-25 11:30:00', '2025-10-25 12:00:00', 0, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(6, 2, '2025-10-26 09:00:00', '2025-10-26 09:30:00', 1, '2025-10-24 03:51:02', '2025-10-24 03:51:02'),
(7, 2, '2025-10-26 09:30:00', '2025-10-26 10:00:00', 1, '2025-10-24 03:51:02', '2025-10-24 03:51:02'),
(8, 2, '2025-10-26 10:00:00', '2025-10-26 10:30:00', 0, '2025-10-24 03:51:02', '2025-10-24 03:51:02'),
(9, 5, '2025-10-27 09:00:00', '2025-10-27 09:30:00', 1, '2025-10-24 03:51:20', '2025-10-24 03:51:20'),
(10, 5, '2025-10-27 09:30:00', '2025-10-27 10:00:00', 1, '2025-10-24 03:51:20', '2025-10-24 03:51:20'),
(11, 5, '2025-10-27 10:00:00', '2025-10-27 10:30:00', 0, '2025-10-24 03:51:20', '2025-10-24 03:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `user1_id` int(10) UNSIGNED NOT NULL,
  `user2_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_message_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`conversation_id`, `user1_id`, `user2_id`, `created_at`, `last_message_time`) VALUES
(1, 1, 6, '2026-04-03 19:02:28', '2026-04-16 06:33:37'),
(2, 1, 2, '2026-04-03 19:02:34', '2026-04-15 18:01:45'),
(3, 6, 56, '2026-04-03 19:03:10', '2026-04-03 19:58:37'),
(4, 2, 55, '2026-04-03 19:30:57', '2026-04-14 15:54:40'),
(5, 5, 55, '2026-04-03 19:31:28', '2026-04-03 19:31:28'),
(6, 5, 1, '2026-04-03 19:44:04', '2026-04-03 19:44:07'),
(7, 64, 6, '2026-04-04 20:24:33', '2026-04-14 16:09:16'),
(8, 4, 56, '2026-04-04 20:25:50', '2026-04-04 21:03:21'),
(9, 4, 6, '2026-04-04 20:25:51', '2026-04-15 16:53:45'),
(10, 4, 63, '2026-04-04 20:29:43', '2026-04-04 21:03:21'),
(11, 4, 59, '2026-04-04 20:48:47', '2026-04-04 21:03:21'),
(12, 4, 55, '2026-04-04 20:52:19', '2026-04-04 21:03:21'),
(13, 4, 2, '2026-04-04 20:52:22', '2026-04-14 15:53:45'),
(14, 4, 1, '2026-04-04 20:58:54', '2026-04-15 17:59:00'),
(15, 4, 3, '2026-04-04 21:03:21', '2026-04-04 21:03:21'),
(16, 4, 57, '2026-04-04 21:03:21', '2026-04-15 16:04:59'),
(17, 4, 58, '2026-04-04 21:03:21', '2026-04-04 21:03:21'),
(18, 4, 62, '2026-04-04 21:03:21', '2026-04-04 21:03:21'),
(19, 4, 64, '2026-04-04 21:03:21', '2026-04-15 17:22:06'),
(20, 65, 6, '2026-04-15 16:23:22', '2026-04-15 16:53:27'),
(21, 2, 63, '2026-04-15 18:01:01', '2026-04-15 18:01:08'),
(22, 66, 64, '2026-04-15 19:38:45', '2026-04-15 19:38:45'),
(23, 66, 63, '2026-04-15 19:38:47', '2026-04-15 19:38:47'),
(24, 66, 59, '2026-04-15 19:39:03', '2026-04-15 19:39:03'),
(25, 66, 55, '2026-04-15 19:39:04', '2026-04-15 19:39:04'),
(26, 66, 3, '2026-04-15 19:39:05', '2026-04-15 19:39:05'),
(27, 66, 1, '2026-04-15 19:39:06', '2026-04-15 19:39:06'),
(28, 66, 56, '2026-04-16 05:53:36', '2026-04-16 05:53:36'),
(29, 66, 6, '2026-04-16 05:53:38', '2026-04-16 05:53:38'),
(30, 66, 2, '2026-04-16 05:53:38', '2026-04-16 05:53:38'),
(31, 66, 57, '2026-04-16 05:53:39', '2026-04-16 05:53:39'),
(32, 66, 62, '2026-04-16 05:53:39', '2026-04-16 05:53:39'),
(33, 66, 58, '2026-04-16 05:53:40', '2026-04-16 05:53:40');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_verifications`
--

CREATE TABLE `doctor_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo_path` varchar(500) NOT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores doctor profile verification documents and status';

--
-- Dumping data for table `doctor_verifications`
--

INSERT INTO `doctor_verifications` (`id`, `user_id`, `email`, `photo_path`, `verification_status`, `uploaded_at`, `updated_at`, `verified_at`, `rejection_reason`) VALUES
(56, 2, 'doctor1@mail.com', '/uploads/verifications/doc1.jpg', 'verified', '2025-10-24 09:17:15', '2025-10-24 09:17:15', '2025-10-24 09:17:15', NULL),
(57, 5, 'doctor2@mail.com', '/uploads/verifications/doc2.jpg', 'verified', '2025-10-24 09:17:15', '2025-10-24 09:17:15', '2025-10-24 09:17:15', NULL),
(58, 6, 'docamal@mail.com', '/uploads/verifications/doc3.jpg', 'pending', '2025-10-24 09:17:15', '2025-10-24 09:17:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `conversation_id`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 1, 'hi', 1, '2026-04-03 19:03:14'),
(2, 2, 1, 'hi', 1, '2026-04-03 19:03:21'),
(3, 2, 1, 'hi', 1, '2026-04-03 19:03:21'),
(4, 2, 1, 'hi', 1, '2026-04-03 19:03:36'),
(5, 2, 1, 'hi', 1, '2026-04-03 19:03:36'),
(6, 2, 1, 'hi', 1, '2026-04-03 19:03:56'),
(7, 2, 1, 'hi', 1, '2026-04-03 19:03:56'),
(8, 2, 1, 'hi', 1, '2026-04-03 19:08:06'),
(9, 2, 1, 'hi', 1, '2026-04-03 19:08:12'),
(10, 2, 1, 'hi', 1, '2026-04-03 19:08:12'),
(11, 1, 6, 'hi', 1, '2026-04-03 19:08:29'),
(12, 2, 1, 'hi', 1, '2026-04-03 19:21:30'),
(14, 2, 1, 'hi docter', 1, '2026-04-03 19:21:44'),
(15, 2, 1, 'hi docter', 1, '2026-04-03 19:21:44'),
(16, 1, 6, 'hello patient', 1, '2026-04-03 19:22:06'),
(17, 2, 1, 'hi docter', 1, '2026-04-03 19:22:38'),
(18, 2, 1, 'hi docter', 1, '2026-04-03 19:22:38'),
(19, 1, 1, 'hi docter im patient 1', 1, '2026-04-03 19:23:03'),
(20, 1, 1, 'hi', 1, '2026-04-03 19:39:09'),
(21, 1, 1, 'hi', 1, '2026-04-03 19:39:13'),
(22, 1, 1, 'hi', 1, '2026-04-03 19:39:13'),
(23, 6, 5, 'hlo', 1, '2026-04-03 19:44:07'),
(25, 1, 1, 'hlo amal', 1, '2026-04-03 19:47:57'),
(26, 1, 6, 'fine', 1, '2026-04-03 19:48:51'),
(27, 1, 6, 'so', 1, '2026-04-03 19:49:02'),
(28, 1, 1, 'nothing', 1, '2026-04-03 19:49:10'),
(29, 1, 1, '{\"text\":\"hi\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1775246052_05f01dd69595_Lambda_Calculus_Answers.pdf\",\"path\":\"uploads/chat_attachments/1/chat_1775246052_05f01dd69595_Lambda_Calculus_Answers.pdf\",\"name\":\"Lambda_Calculus_Answers.pdf\",\"type\":\"application/pdf\",\"size\":26432}}', 1, '2026-04-03 19:54:12'),
(30, 1, 1, '{\"text\":\"hi\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1775246078_a8b2d2ae7833_Screenshot_2026-04-04_002804.png\",\"path\":\"uploads/chat_attachments/1/chat_1775246078_a8b2d2ae7833_Screenshot_2026-04-04_002804.png\",\"name\":\"Screenshot 2026-04-04 002804.png\",\"type\":\"image/png\",\"size\":82226}}', 1, '2026-04-03 19:54:38'),
(31, 3, 6, 'hi', 0, '2026-04-03 19:58:37'),
(32, 1, 6, 'hiii', 1, '2026-04-03 19:58:52'),
(33, 1, 6, 'hiii', 1, '2026-04-03 20:01:11'),
(34, 1, 6, 'hlo patieny', 1, '2026-04-03 20:07:21'),
(35, 1, 6, 'gu', 1, '2026-04-03 20:07:29'),
(36, 1, 6, '{\"text\":\"yt\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1775246865_e3860005b1bb_Lambda_Calculus_Practice_Questions.pdf\",\"path\":\"uploads/chat_attachments/6/chat_1775246865_e3860005b1bb_Lambda_Calculus_Practice_Questions.pdf\",\"name\":\"Lambda_Calculus_Practice_Questions.pdf\",\"type\":\"application/pdf\",\"size\":20871}}', 1, '2026-04-03 20:07:45'),
(37, 1, 6, '{\"text\":\"g\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1775246886_b047ec79df58_Screenshot_2026-04-04_013117.png\",\"path\":\"uploads/chat_attachments/6/chat_1775246886_b047ec79df58_Screenshot_2026-04-04_013117.png\",\"name\":\"Screenshot 2026-04-04 013117.png\",\"type\":\"image/png\",\"size\":65890}}', 1, '2026-04-03 20:08:06'),
(38, 1, 6, 'hil', 1, '2026-04-03 20:09:11'),
(39, 1, 6, 'trs', 1, '2026-04-03 20:09:22'),
(40, 1, 6, 'yesss', 1, '2026-04-03 20:09:37'),
(41, 1, 6, 'went', 1, '2026-04-03 20:11:17'),
(42, 1, 6, 'we', 1, '2026-04-03 20:13:01'),
(43, 1, 6, 'go  there', 1, '2026-04-03 20:13:09'),
(45, 1, 1, 'hello', 1, '2026-04-03 20:19:33'),
(46, 1, 6, 'hi', 1, '2026-04-03 20:19:40'),
(47, 1, 6, 'hi', 1, '2026-04-03 20:19:46'),
(48, 1, 1, 'ju', 1, '2026-04-03 20:19:59'),
(49, 1, 6, 'fg', 1, '2026-04-03 20:20:03'),
(50, 1, 1, 'gg', 1, '2026-04-03 20:20:09'),
(51, 1, 1, 'hlo docture', 1, '2026-04-03 20:20:24'),
(52, 1, 6, 'yes how are ty', 1, '2026-04-03 20:20:39'),
(53, 1, 6, 'f', 1, '2026-04-03 20:20:59'),
(54, 1, 6, 'nice', 1, '2026-04-03 20:21:14'),
(55, 1, 1, 'nice', 1, '2026-04-03 20:21:18'),
(56, 1, 1, '{\"text\":\"this mis report\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1775247718_c9c5abb79e65_Screenshot_2024-01-23_174254.png\",\"path\":\"uploads/chat_attachments/1/chat_1775247718_c9c5abb79e65_Screenshot_2024-01-23_174254.png\",\"name\":\"Screenshot 2024-01-23 174254.png\",\"type\":\"image/png\",\"size\":123877}}', 1, '2026-04-03 20:21:58'),
(57, 1, 6, 'ok', 1, '2026-04-03 20:22:03'),
(58, 1, 6, '{\"text\":\"refr\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1775247734_dfa7b98abd5a_Screenshot_2024-04-04_111543.png\",\"path\":\"uploads/chat_attachments/6/chat_1775247734_dfa7b98abd5a_Screenshot_2024-04-04_111543.png\",\"name\":\"Screenshot 2024-04-04 111543.png\",\"type\":\"image/png\",\"size\":18399}}', 1, '2026-04-03 20:22:14'),
(61, 2, 1, 'hlo', 1, '2026-04-03 20:30:36'),
(62, 2, 1, 'hrt', 1, '2026-04-03 20:33:02'),
(63, 2, 1, 'bh', 1, '2026-04-03 20:33:39'),
(64, 1, 1, 'tr', 1, '2026-04-03 20:34:37'),
(65, 1, 6, 'j', 1, '2026-04-03 20:34:44'),
(66, 1, 1, 'j', 1, '2026-04-03 20:34:48'),
(67, 1, 1, 'n', 1, '2026-04-03 20:35:08'),
(68, 1, 1, 'h', 1, '2026-04-03 20:35:10'),
(69, 1, 1, 'n', 1, '2026-04-03 20:35:13'),
(70, 1, 6, 'j', 1, '2026-04-03 20:35:20'),
(71, 1, 1, 'b', 1, '2026-04-03 20:36:56'),
(72, 1, 6, 'j', 1, '2026-04-03 20:37:04'),
(73, 1, 6, 'jjj', 1, '2026-04-03 20:37:16'),
(75, 1, 6, 'hy', 1, '2026-04-03 20:40:08'),
(76, 1, 1, 'hy', 1, '2026-04-03 20:40:17'),
(77, 1, 6, 'c', 1, '2026-04-03 20:40:23'),
(78, 2, 1, 'ju', 1, '2026-04-03 20:42:13'),
(79, 1, 1, 'gg', 1, '2026-04-03 20:42:39'),
(80, 1, 6, 'hy', 1, '2026-04-03 20:42:54'),
(81, 1, 6, 'g', 1, '2026-04-03 20:43:21'),
(82, 1, 6, 'hy', 1, '2026-04-03 20:43:47'),
(83, 1, 6, 'j', 1, '2026-04-03 20:43:54'),
(84, 1, 6, 'b', 1, '2026-04-03 20:44:47'),
(85, 1, 6, 'j', 1, '2026-04-03 20:44:52'),
(86, 1, 6, 'fgg', 1, '2026-04-03 20:45:00'),
(87, 1, 6, 'hlo', 1, '2026-04-03 20:52:12'),
(88, 1, 6, 'hu', 1, '2026-04-03 20:52:27'),
(89, 1, 6, 'j', 1, '2026-04-03 20:52:30'),
(90, 1, 6, '\\', 1, '2026-04-03 20:52:31'),
(91, 1, 1, 'g', 1, '2026-04-03 20:52:49'),
(92, 1, 6, 'h', 1, '2026-04-03 20:52:54'),
(93, 1, 1, 'h', 1, '2026-04-03 20:52:57'),
(94, 1, 1, '{\"text\":\"h\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1775249765_dce2b64fe65d_Screenshot_2024-01-23_174254.png\",\"path\":\"uploads/chat_attachments/1/chat_1775249765_dce2b64fe65d_Screenshot_2024-01-23_174254.png\",\"name\":\"Screenshot 2024-01-23 174254.png\",\"type\":\"image/png\",\"size\":123877}}', 1, '2026-04-03 20:56:05'),
(95, 1, 6, 'g', 1, '2026-04-04 19:51:40'),
(96, 1, 6, 'ff', 1, '2026-04-04 19:51:46'),
(97, 1, 6, 'f', 1, '2026-04-04 19:51:48'),
(98, 1, 6, 'f', 1, '2026-04-04 19:51:50'),
(99, 1, 6, 'jo', 1, '2026-04-04 19:52:30'),
(100, 1, 1, 'hy', 1, '2026-04-04 19:53:08'),
(101, 1, 6, 'hy', 1, '2026-04-04 19:53:15'),
(102, 1, 6, 'g', 1, '2026-04-04 19:53:18'),
(103, 1, 6, 'hhh', 1, '2026-04-04 19:53:25'),
(104, 1, 6, 'f', 1, '2026-04-04 19:53:28'),
(105, 1, 6, 'ff', 1, '2026-04-04 19:53:29'),
(106, 1, 6, 'ffff', 1, '2026-04-04 19:53:33'),
(107, 1, 6, 'f', 1, '2026-04-04 19:53:34'),
(108, 1, 6, 'fffff', 1, '2026-04-04 19:53:35'),
(109, 1, 6, 'hy my name is ishini', 1, '2026-04-04 19:53:41'),
(110, 1, 1, 'hlo ishini', 1, '2026-04-04 19:53:51'),
(111, 1, 6, 'nice to see you', 1, '2026-04-04 19:54:01'),
(112, 1, 1, 'k', 1, '2026-04-04 19:54:35'),
(113, 1, 6, 'j', 1, '2026-04-04 19:54:37'),
(114, 7, 64, 'hlo doctor', 1, '2026-04-04 20:24:41'),
(115, 9, 4, 'hi', 1, '2026-04-04 20:25:54'),
(116, 10, 4, 'kl', 0, '2026-04-04 20:29:46'),
(117, 7, 6, 'c', 1, '2026-04-04 20:33:36'),
(118, 14, 4, 'hlo', 1, '2026-04-04 20:58:57'),
(119, 13, 4, 'hi doctor', 1, '2026-04-04 20:59:14'),
(120, 14, 4, 'hlo', 1, '2026-04-04 21:03:21'),
(121, 13, 4, 'hlo', 1, '2026-04-04 21:03:21'),
(122, 15, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(123, 9, 4, 'hlo', 1, '2026-04-04 21:03:21'),
(124, 12, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(125, 8, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(126, 16, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(127, 17, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(128, 11, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(129, 18, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(130, 10, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(131, 19, 4, 'hlo', 0, '2026-04-04 21:03:21'),
(132, 9, 4, 'hlo doc amal', 1, '2026-04-04 21:06:18'),
(133, 13, 4, 'hi doc1', 1, '2026-04-04 21:06:52'),
(134, 13, 4, 'gm dov22222222222', 1, '2026-04-04 21:12:08'),
(136, 9, 4, 'hi', 1, '2026-04-04 21:25:52'),
(137, 9, 4, 'hii', 1, '2026-04-04 21:25:54'),
(138, 13, 4, 'hlooooooooooo', 1, '2026-04-04 21:30:44'),
(139, 9, 4, 'hllllllllll', 1, '2026-04-04 21:31:02'),
(140, 9, 6, 'hi', 1, '2026-04-04 21:39:39'),
(142, 9, 6, 'hklo', 1, '2026-04-04 21:43:28'),
(143, 1, 6, 'hlo', 1, '2026-04-04 21:49:01'),
(144, 1, 6, 'hlo patient', 1, '2026-04-04 21:49:09'),
(145, 1, 1, 'hyyy', 1, '2026-04-04 21:49:38'),
(146, 1, 6, 'f', 1, '2026-04-04 21:49:47'),
(147, 1, 6, 'gggg', 1, '2026-04-04 21:49:52'),
(148, 9, 4, 'hrr', 1, '2026-04-04 21:50:17'),
(151, 9, 6, 'hy', 1, '2026-04-04 21:55:02'),
(152, 9, 4, 'hy', 1, '2026-04-04 21:55:37'),
(153, 9, 6, 'ggggggggggggggggg', 1, '2026-04-04 21:55:49'),
(154, 9, 4, 'fffffffffffffffffg', 1, '2026-04-04 21:55:57'),
(160, 19, 4, '{\"text\":\"\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/4/chat_1775340335_c3c34091586d_Screenshot_2024-04-04_111543.png\",\"path\":\"uploads/chat_attachments/4/chat_1775340335_c3c34091586d_Screenshot_2024-04-04_111543.png\",\"name\":\"Screenshot 2024-04-04 111543.png\",\"type\":\"image/png\",\"size\":18399}}', 0, '2026-04-04 22:05:35'),
(161, 1, 1, 'hlo doctor happy new year', 1, '2026-04-14 07:06:30'),
(162, 1, 6, 'same to you patient', 1, '2026-04-14 07:06:44'),
(164, 1, 1, 'hlo', 1, '2026-04-14 07:18:26'),
(165, 1, 6, 'hy', 1, '2026-04-14 07:18:35'),
(166, 1, 6, 'jjjjjjjjjjjjjj', 1, '2026-04-14 07:18:41'),
(167, 1, 6, 'hyyyyyyyyyyyyyyy', 1, '2026-04-14 07:18:57'),
(168, 1, 6, 'g', 1, '2026-04-14 07:18:58'),
(169, 1, 6, 'h', 1, '2026-04-14 07:19:08'),
(170, 1, 6, 'h', 1, '2026-04-14 07:19:09'),
(171, 1, 6, 'h', 1, '2026-04-14 07:19:09'),
(172, 1, 6, 'h', 1, '2026-04-14 07:19:09'),
(173, 1, 6, 'h', 1, '2026-04-14 07:19:10'),
(174, 1, 6, 'h', 1, '2026-04-14 07:19:10'),
(175, 1, 1, 'j', 1, '2026-04-14 07:20:45'),
(176, 1, 1, 'h', 1, '2026-04-14 07:20:48'),
(177, 1, 1, 'hy', 1, '2026-04-14 07:20:49'),
(178, 1, 1, 'fgdg', 1, '2026-04-14 07:20:53'),
(179, 1, 6, 'fhdgh', 1, '2026-04-14 07:20:56'),
(180, 1, 6, 'hu doctor', 1, '2026-04-14 15:05:18'),
(182, 1, 1, 'hlo dov', 1, '2026-04-14 15:05:37'),
(184, 1, 6, 'ishini', 1, '2026-04-14 15:14:37'),
(190, 4, 2, 'vgggggg', 0, '2026-04-14 15:49:03'),
(191, 13, 2, 'hhh hlo', 1, '2026-04-14 15:53:45'),
(192, 4, 2, 'hhll llo', 0, '2026-04-14 15:54:22'),
(193, 4, 2, 'hk', 0, '2026-04-14 15:54:40'),
(194, 7, 6, 'hhh', 0, '2026-04-14 15:57:59'),
(195, 7, 6, 'jo', 0, '2026-04-14 15:58:03'),
(196, 7, 6, '\\', 0, '2026-04-14 15:58:05'),
(197, 1, 6, 'hlo', 1, '2026-04-14 16:09:01'),
(198, 1, 6, 'hlo hol', 1, '2026-04-14 16:09:06'),
(199, 7, 6, 'hhi gioo', 0, '2026-04-14 16:09:16'),
(200, 9, 6, 'hlo how are you g', 1, '2026-04-14 16:12:23'),
(201, 9, 6, 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', 1, '2026-04-14 16:18:56'),
(202, 14, 4, 'hlo w', 1, '2026-04-14 16:29:20'),
(203, 16, 4, 'hyyyy fdo', 0, '2026-04-15 16:04:59'),
(206, 14, 4, 'hhhhhi hiii', 1, '2026-04-15 16:06:43'),
(207, 14, 1, 'hhhhh hhhhhhhh', 1, '2026-04-15 16:07:24'),
(208, 14, 1, '{\"text\":\"\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1776269264_fc8fa5f8f0e7_Screenshot_2024-01-23_174254.png\",\"path\":\"uploads/chat_attachments/1/chat_1776269264_fc8fa5f8f0e7_Screenshot_2024-01-23_174254.png\",\"name\":\"Screenshot 2024-01-23 174254.png\",\"type\":\"image/png\",\"size\":123877}}', 1, '2026-04-15 16:07:44'),
(209, 14, 4, 'ok i got it', 1, '2026-04-15 16:07:55'),
(210, 20, 65, 'ddd', 1, '2026-04-15 16:23:28'),
(211, 20, 65, 'hlo', 1, '2026-04-15 16:51:59'),
(212, 20, 65, 'ggg', 1, '2026-04-15 16:52:06'),
(213, 20, 65, 'mm', 1, '2026-04-15 16:52:20'),
(214, 9, 4, 'jj', 1, '2026-04-15 16:53:06'),
(215, 20, 6, 'kk', 0, '2026-04-15 16:53:27'),
(216, 9, 6, 'll', 1, '2026-04-15 16:53:35'),
(217, 9, 4, 'k', 1, '2026-04-15 16:53:45'),
(218, 19, 4, 'hloo', 0, '2026-04-15 17:22:06'),
(219, 14, 4, 'hlo', 1, '2026-04-15 17:58:52'),
(220, 14, 4, '{\"text\":\"\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/4/chat_1776275940_4e0c1cd172fc_Screenshot_2024-01-23_174254.png\",\"path\":\"uploads/chat_attachments/4/chat_1776275940_4e0c1cd172fc_Screenshot_2024-01-23_174254.png\",\"name\":\"Screenshot 2024-01-23 174254.png\",\"type\":\"image/png\",\"size\":123877}}', 1, '2026-04-15 17:59:00'),
(221, 21, 2, 'hlo pati', 0, '2026-04-15 18:01:08'),
(222, 2, 2, 'dd', 1, '2026-04-15 18:01:45'),
(223, 1, 1, 'hallo', 1, '2026-04-16 06:32:08'),
(224, 1, 6, 'bye', 1, '2026-04-16 06:32:40'),
(225, 1, 6, 'test', 1, '2026-04-16 06:33:23'),
(226, 1, 6, '{\"text\":\"doc\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1776321217_1464849f1ed8_MA-CR-09-Transactions-Management.pdf\",\"path\":\"uploads/chat_attachments/6/chat_1776321217_1464849f1ed8_MA-CR-09-Transactions-Management.pdf\",\"name\":\"MA-CR-09-Transactions-Management.pdf\",\"type\":\"application/pdf\",\"size\":387423}}', 1, '2026-04-16 06:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_type` enum('admin','doctor','patient','all') NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `notification_type` enum('info','warning','success','error') DEFAULT 'info',
  `status` enum('sent','read','unread') DEFAULT 'sent',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `recipient_type`, `recipient_id`, `title`, `message`, `notification_type`, `status`, `created_at`, `read_at`) VALUES
(14, 'patient', 1, 'Appointment Approved', 'Your appointment with Dr. Doctor1 has been approved.', 'success', 'sent', '2025-10-24 09:17:15', NULL),
(15, 'doctor', 2, 'New Appointment', 'You have a new appointment request from patient1.', 'info', 'sent', '2025-10-24 09:17:15', NULL),
(16, 'patient', 55, 'Prescription Ready', 'Dr. Doctor2 has issued a prescription for you.', 'info', 'sent', '2025-10-24 09:17:15', NULL),
(17, 'doctor', 6, 'Appointment Completed', 'Your appointment with Dilani Perera was completed.', 'success', 'sent', '2025-10-24 09:17:15', NULL),
(18, 'patient', 57, 'Appointment Cancelled', 'Your appointment with Dr. Amal was cancelled.', 'warning', 'sent', '2025-10-24 09:17:15', NULL),
(19, 'doctor', 2, 'New Appointment Approved', 'Appointment with Roshan Fernando approved.', 'success', 'sent', '2025-10-24 09:21:02', NULL),
(20, 'patient', 55, 'Appointment Approved', 'Your appointment with Dr. Doctor1 is confirmed for Oct 26, 2025, 09:00 AM.', 'success', 'sent', '2025-10-24 09:21:02', NULL),
(21, 'patient', 58, 'Reschedule Pending', 'Your proposed new time for Dr. Doctor1 is awaiting doctor approval.', 'info', 'sent', '2025-10-24 09:21:02', NULL),
(22, 'doctor', 2, 'Reschedule Requested', 'Patient Malini Rajapaksa requested to reschedule the appointment.', 'warning', 'sent', '2025-10-24 09:21:02', NULL),
(23, 'patient', 1, 'Appointment Confirmed', 'Your appointment with Dr. Doctor2 is confirmed for Oct 27, 2025, 09:00 AM.', 'success', 'sent', '2025-10-24 09:21:20', NULL),
(24, 'doctor', 5, 'New Appointment Request', 'Patient1 has requested a new appointment.', 'info', 'sent', '2025-10-24 09:21:20', NULL),
(25, 'patient', 1, 'Reschedule Suggested', 'Dr. Doctor2 proposed a new time for your diet consultation.', 'warning', 'sent', '2025-10-24 09:21:20', NULL),
(26, 'all', NULL, 'Test 1', 'This is a test.', 'info', 'sent', '2025-12-11 14:21:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `drug_name` varchar(255) NOT NULL,
  `formulation` varchar(255) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `brand_substitution` tinyint(1) DEFAULT 0,
  `prn` tinyint(1) DEFAULT 0,
  `max_per_24h` int(11) DEFAULT NULL,
  `prn_indication` varchar(255) DEFAULT NULL,
  `dose_amount` varchar(50) DEFAULT NULL,
  `dose_unit` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `custom_frequency` int(11) DEFAULT NULL,
  `time_of_day` varchar(255) DEFAULT NULL,
  `meal_relation` varchar(100) DEFAULT NULL,
  `duration_value` int(11) DEFAULT NULL,
  `duration_type` varchar(50) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `dispense_quantity` int(11) DEFAULT NULL,
  `unit_type` varchar(100) DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `pharmacy_note` text DEFAULT NULL,
  `doctor_notes` text DEFAULT NULL,
  `is_deleted` enum('not_deleted','deleted') DEFAULT 'not_deleted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `doctor_id`, `patient_id`, `drug_name`, `formulation`, `route`, `brand_substitution`, `prn`, `max_per_24h`, `prn_indication`, `dose_amount`, `dose_unit`, `frequency`, `custom_frequency`, `time_of_day`, `meal_relation`, `duration_value`, `duration_type`, `special_instructions`, `dispense_quantity`, `unit_type`, `diagnosis`, `valid_until`, `pharmacy_note`, `doctor_notes`, `is_deleted`, `created_at`, `updated_at`) VALUES
(7, 2, 1, 'Paracetamol', 'Tablet', 'Oral', 0, 0, NULL, NULL, '500', 'mg', 'Every 6 hours', NULL, NULL, NULL, 3, 'days', 'Take with water', 12, 'Tablet', 'Headache', '2025-11-01', NULL, NULL, 'not_deleted', '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(8, 5, 55, 'Azithromycin', 'Tablet', 'Oral', 0, 0, NULL, NULL, '250', 'mg', 'Once daily', NULL, NULL, NULL, 5, 'days', 'Take after meal', 5, 'Tablet', 'Fever', '2025-11-01', NULL, NULL, 'not_deleted', '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(9, 6, 56, 'Cough Syrup', 'Liquid', 'Oral', 0, 0, NULL, NULL, '10', 'ml', 'Every 8 hours', NULL, NULL, NULL, 7, 'days', 'Shake well before use', 1, 'Bottle', 'Cough', '2025-11-10', NULL, NULL, 'not_deleted', '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(10, 2, 55, 'Ibuprofen', 'Tablet', 'Oral', 0, 0, NULL, NULL, '400', 'mg', 'Every 8 hours', NULL, NULL, NULL, 5, 'days', 'Take after meals', 15, 'Tablet', 'Back pain', '2025-11-02', NULL, NULL, 'deleted', '2025-10-24 03:51:02', '2025-11-17 12:16:05'),
(11, 2, 58, 'Cetirizine', 'Tablet', 'Oral', 0, 0, NULL, NULL, '10', 'mg', 'Once daily', NULL, NULL, NULL, 7, 'days', 'Take at bedtime', 7, 'Tablet', 'Allergy', '2025-11-05', NULL, NULL, 'not_deleted', '2025-10-24 03:51:02', '2025-10-24 03:51:02'),
(12, 5, 1, 'Omeprazole', 'Capsule', 'Oral', 0, 0, NULL, NULL, '20', 'mg', 'Once daily', NULL, NULL, NULL, 7, 'days', 'Take before breakfast', 7, 'Capsule', 'Gastritis', '2025-11-05', NULL, NULL, 'not_deleted', '2025-10-24 03:51:20', '2025-10-24 03:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_type` enum('patient','doctor','admin') NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_type` enum('patient','doctor','admin','system') NOT NULL,
  `reported_id` int(11) DEFAULT NULL,
  `report_type` varchar(100) NOT NULL,
  `reason` text NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','under_review','resolved','dismissed') DEFAULT 'pending',
  `resolution` text DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_type`, `reporter_id`, `reported_type`, `reported_id`, `report_type`, `reason`, `description`, `status`, `resolution`, `resolved_by`, `resolved_at`, `created_at`, `updated_at`) VALUES
(6, 'patient', 1, 'doctor', 2, 'Service Issue', 'Doctor was late', 'Doctor arrived 15 minutes late.', 'resolved', NULL, NULL, NULL, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(7, 'doctor', 5, 'patient', 55, 'No Show', 'Patient missed appointment', 'Patient did not show up for appointment.', 'resolved', NULL, NULL, NULL, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(8, 'admin', 4, 'system', NULL, 'System Check', 'Routine audit', 'Weekly automated system review.', 'under_review', NULL, NULL, NULL, '2025-10-24 03:47:15', '2025-10-24 03:47:15'),
(9, 'patient', 59, 'doctor', 2, 'Professional Conduct', 'Doctor was courteous and thorough.', 'Positive feedback logged for quality tracking.', 'resolved', NULL, NULL, NULL, '2025-10-24 03:51:02', '2025-10-24 03:51:02'),
(10, 'patient', 1, 'doctor', 5, 'Service Feedback', 'Very helpful session', 'Doctor2 provided clear guidance and was very professional.', 'resolved', NULL, NULL, NULL, '2025-10-24 03:51:20', '2025-10-24 03:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `slmc`
--

CREATE TABLE `slmc` (
  `slmc` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slmc`
--

INSERT INTO `slmc` (`slmc`) VALUES
('1234'),
('2234'),
('3234'),
('4234'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('1234'),
('2234'),
('3234'),
('4234'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('1234'),
('2234'),
('3234'),
('4234'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010'),
('00001'),
('00002'),
('00003'),
('00004'),
('00005'),
('00006'),
('00007'),
('00008'),
('00009'),
('00010');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Doctor','Patient') NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'inactive',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `slmc` varchar(10) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`, `slmc`, `last_activity`) VALUES
(1, 'patient1', 'patient1@mail.com', '$2y$10$VhsQ8vaQ51QpL0C51XE2B.rR2REeazkoujd1ohPLL9xBhN7X5O/Da', 'Patient', 'active', '2025-10-23 13:11:25', '2025-10-23 13:19:39', NULL, '2026-04-16 06:25:02'),
(2, 'doctor1', 'doctor1@mail.com', '$2y$10$p7PRv.Ct/uRStd5KR9ehJ.Qcixc63Hg.GZ5YZy6MoSDOIQp7DITcW', 'Doctor', 'active', '2025-10-23 13:12:16', '2025-10-23 18:31:34', '1234', '2026-04-16 06:25:02'),
(3, 'patient2', 'patient2@mail.com', '$2y$10$dru4qaj4KReU6NbYHRVADu2.cQWp4ljYv7jYdoUlpNDc5KRJFE3da', 'Patient', 'inactive', '2025-10-23 13:13:21', '2025-10-23 13:13:21', NULL, '2026-04-16 06:25:02'),
(4, 'admin1', 'admin1@mail.com', '$2y$10$tRcm9ygnMIkFCZVWz/8SweMzux6MyzMq.SHYNTby8kf3qNn/mkije', 'Admin', 'inactive', '2025-10-23 13:36:19', '2025-10-23 13:36:19', NULL, '2026-04-16 06:25:02'),
(5, 'doctor2', 'doctor2@mail.com', '$2y$10$xjQJdj4xwy/33VlfhUiH1OWOyVQBE4nHdCvQCADmXkYOE66J6zQi.', 'Doctor', 'inactive', '2025-10-23 18:33:54', '2025-10-23 18:33:54', '2234', '2026-04-16 06:25:02'),
(6, 'amal', 'docamal@mail.com', '$2y$10$OMiikJlhYqGzdCgrG2BfvOLKCyVg9rTvRjcwABLYFVaDoEGf44Dxi', 'Doctor', 'active', '2025-10-24 02:06:47', '2025-10-24 02:07:31', '3234', '2026-04-16 06:25:02'),
(55, 'Roshan Fernando', 'roshan.fernando@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Yal4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-12 15:30:00', '2025-01-22 09:15:00', NULL, '2026-04-16 06:25:02'),
(56, 'Dilani Perera', 'dilani.perera@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-13 11:45:00', '2025-01-23 16:45:00', NULL, '2026-04-16 06:25:02'),
(57, 'Kumar Dissanayake', 'kumar.dissanayake@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-14 07:30:00', '2025-01-24 13:20:00', NULL, '2026-04-16 06:25:02'),
(58, 'Malini Rajapaksa', 'malini.rajapaksa@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-15 09:45:00', '2025-01-25 15:40:00', NULL, '2026-04-16 06:25:02'),
(59, 'Tharaka Amarasinghe', 'tharaka.amarasinghe@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-16 08:15:00', '2025-01-26 12:30:00', NULL, '2026-04-16 06:25:02'),
(60, 'Chaminda Gunasekara', 'chaminda.gunasekara@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-05 13:20:00', '2025-01-12 15:30:00', NULL, '2026-04-16 06:25:02'),
(61, 'Sunil Wijeratne', 'sunil.wijeratne@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-08 10:30:00', '2025-01-25 12:00:00', NULL, '2026-04-16 06:25:02'),
(62, 'lakmal', 'doclakmal@gmail.com', '$2y$10$Mq8WPQvJJ4A2gjktIAKdAeTF5xeMR9UfWgC9WMVce7mF82ALjQaQS', 'Doctor', 'active', '2025-10-24 10:10:07', '2025-11-17 17:41:29', '00007', '2026-04-16 06:25:02'),
(63, 'ishinipatient', 'ishinipatient@gmail.com', '$2y$10$.IdLQoQ4ndIn00bKd7LdnuOp7ob4.YNG7DDBIlslFvEnF/Zd8fhAm', 'Patient', 'active', '2026-04-04 00:55:57', '2026-04-05 01:49:14', NULL, '2026-04-03 13:55:57'),
(64, 'ishini', 'ishiniayodya9@gmail.com', '$2y$10$ya2.74VTOnFXHg5Ahr91vOP64N4qi9qziRXEwZk60AREDB.oa.zza', 'Patient', 'active', '2026-04-05 01:31:25', '2026-04-05 01:49:09', NULL, '2026-04-04 14:31:25'),
(65, 'ayodya piumandhi', 'ayodya@gmail.com', '$2y$10$LGb24XUcN/o93SBNSF9WD.XjVGAmXruRVwx5xyQqxfz6aUQ7xif3m', 'Patient', 'inactive', '2026-04-15 21:39:41', '2026-04-15 21:39:41', NULL, '2026-04-15 10:39:41'),
(66, 'adnin ish', 'admin2@mail.com', '$2y$10$kIcxq0btPs68WntWvdmg1uST020OXI/VZNmdDyWaXSA2zUeWPjMzq', 'Admin', 'inactive', '2026-04-15 23:32:38', '2026-04-15 23:32:38', NULL, '2026-04-15 12:32:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ap_slot` (`slot_id`),
  ADD KEY `idx_appt_doctor_time` (`doctor_id`,`starts_at`,`ends_at`),
  ADD KEY `idx_appt_patient_time` (`patient_id`,`starts_at`,`ends_at`),
  ADD KEY `idx_appt_status` (`status`);

--
-- Indexes for table `availability_slots`
--
ALTER TABLE `availability_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_availability_doctor_time` (`doctor_id`,`slot_start`,`slot_end`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`),
  ADD KEY `idx_user1` (`user1_id`),
  ADD KEY `idx_user2` (`user2_id`),
  ADD KEY `idx_last_message` (`last_message_time`);

--
-- Indexes for table `doctor_verifications`
--
ALTER TABLE `doctor_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_verification` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`verification_status`),
  ADD KEY `idx_uploaded_at` (`uploaded_at`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_conversation` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipient` (`recipient_type`,`recipient_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prescriptions_doctor` (`doctor_id`),
  ADD KEY `idx_prescriptions_patient` (`patient_id`),
  ADD KEY `idx_prescriptions_drug` (`drug_name`),
  ADD KEY `idx_prescriptions_created` (`created_at`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reporter` (`reporter_type`,`reporter_id`),
  ADD KEY `idx_reported` (`reported_type`,`reported_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_last_activity` (`last_activity`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `availability_slots`
--
ALTER TABLE `availability_slots`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `doctor_verifications`
--
ALTER TABLE `doctor_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_ap_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ap_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ap_slot` FOREIGN KEY (`slot_id`) REFERENCES `availability_slots` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `availability_slots`
--
ALTER TABLE `availability_slots`
  ADD CONSTRAINT `fk_av_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`conversation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `fk_prescriptions_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prescriptions_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

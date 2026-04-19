-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 07:11 AM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 05:59:13'),
(2, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 05:59:25'),
(3, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 05:59:30'),
(4, NULL, 3, 'login_success', 'Login success (patient2@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 05:59:36'),
(5, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 05:59:41'),
(6, NULL, 6, 'login_failed', 'Login failed: invalid_password (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:23:54'),
(7, NULL, 6, 'login_failed', 'Login failed: invalid_password (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:23:56'),
(8, NULL, 6, 'login_failed', 'Login failed: invalid_password (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:23:58'),
(9, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:24:03'),
(10, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:55:10'),
(11, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 11:24:45'),
(12, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 11:26:27'),
(13, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 11:48:27'),
(14, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 11:59:05'),
(15, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 12:02:16'),
(16, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 12:47:11'),
(17, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 12:47:24'),
(18, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 16:15:55'),
(19, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 04:06:23'),
(20, NULL, 3, 'login_success', 'Login success (patient2@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 04:12:06'),
(21, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 08:34:13'),
(22, NULL, NULL, 'login_failed', 'Login failed: user_not_found (patient1@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 08:36:25'),
(23, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 08:44:38'),
(24, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 08:44:45'),
(25, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:29:51'),
(26, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:31:07'),
(27, NULL, NULL, 'login_failed', 'Login failed: user_not_found (patient1@gmail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:36:22'),
(28, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:36:27'),
(29, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:39:29'),
(30, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:15:00'),
(31, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:15:16'),
(32, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:15:59'),
(33, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:19:55'),
(34, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:20:13'),
(35, 4, NULL, 'Profile Updated', 'Updated personal information', NULL, NULL, '2026-04-18 10:28:39'),
(36, 4, NULL, 'Profile Picture Updated', 'Updated profile picture', NULL, NULL, '2026-04-18 10:28:46'),
(37, 4, NULL, 'Profile Updated', 'Updated personal information', NULL, NULL, '2026-04-18 10:28:57'),
(38, 4, NULL, 'Profile Updated', 'Updated personal information', NULL, NULL, '2026-04-18 10:29:04'),
(39, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 10:58:25'),
(40, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:01:21'),
(41, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:04:40'),
(42, NULL, 2, 'login_success', 'Login success (doctor1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:09:56'),
(43, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:20:58'),
(44, NULL, 6, 'login_failed', 'Login failed: invalid_password (docamal@mail.com)', '10.85.22.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-18 11:23:56'),
(45, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:24:13'),
(46, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-18 11:24:55'),
(47, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '10.85.22.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-18 11:28:15'),
(48, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:32:28'),
(49, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:33:10'),
(50, NULL, 2, 'login_success', 'Login success (doctor1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:35:40'),
(51, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 11:45:35'),
(52, NULL, NULL, 'login_success', 'Login success (dulana216@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:25:48'),
(53, NULL, NULL, 'login_failed', 'Login failed: user_not_found (2023cs215@stu.ucsc.cmb.ac.lk)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:36:43'),
(54, NULL, NULL, 'login_success', 'Login success (2023cs215@stu.ucsc.cmb.ac.lk)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:37:16'),
(55, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:47:26'),
(56, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:48:05'),
(57, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:54:18'),
(58, NULL, 5, 'login_success', 'Login success (doctor2@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:54:45'),
(59, NULL, NULL, 'login_success', 'Login success (2023cs215@stu.ucsc.cmb.ac.lk)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:56:56'),
(60, NULL, 71, 'login_success', 'Login success (2023cs215@stu.ucsc.cmb.ac.lk)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:57:42'),
(61, NULL, 72, 'login_success', 'Login success (dulana216@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:58:59'),
(62, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:00:49'),
(63, NULL, 72, 'login_success', 'Login success (dulana216@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:15:41'),
(64, NULL, 73, 'login_success', 'Login success (dulana217@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:22:37'),
(65, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:23:28'),
(66, NULL, 73, 'login_success', 'Login success (dulana217@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:25:37'),
(67, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:19:23'),
(68, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 16:31:50'),
(69, NULL, 71, 'login_success', 'Login success (2023cs215@stu.ucsc.cmb.ac.lk)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 16:36:36'),
(70, NULL, 72, 'login_success', 'Login success (dulana216@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 16:37:13'),
(71, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 16:37:38'),
(72, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-18 18:13:04'),
(73, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-19 04:30:06'),
(74, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-19 04:41:08'),
(75, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:47:33'),
(76, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:47:43'),
(77, NULL, 4, 'login_success', 'Login success (admin1@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:55:47'),
(78, NULL, 6, 'login_success', 'Login success (docamal@mail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:56:20'),
(79, NULL, NULL, 'login_failed', 'Login failed: user_not_found (patient1@gmail.com)', '10.71.51.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:58:27'),
(80, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '10.71.51.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 04:58:33'),
(81, NULL, 1, 'login_success', 'Login success (patient1@mail.com)', '10.71.51.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 05:03:05');

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
  `status_reason` text DEFAULT NULL,
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
(29, 65, 6, NULL, '2026-04-16 16:21:00', '2026-04-16 16:36:00', 'approved', '', NULL, NULL, '2026-04-15 10:52:05', '2026-04-15 10:52:44', NULL, NULL, 'none', NULL, NULL),
(30, 1, 2, NULL, '2026-04-18 08:40:00', '2026-04-18 08:55:00', 'pending', 'Test', NULL, NULL, '2026-04-18 08:34:43', '2026-04-18 08:34:43', NULL, NULL, 'none', NULL, NULL),
(31, 1, 6, NULL, '2026-04-19 12:50:00', '2026-04-19 13:05:00', 'approved', '', NULL, NULL, '2026-04-18 08:46:01', '2026-04-18 08:46:09', NULL, NULL, 'none', NULL, NULL),
(32, 1, 6, NULL, '2026-04-18 11:13:00', '2026-04-18 11:28:00', 'pending', 'adsfas', NULL, NULL, '2026-04-18 11:07:36', '2026-04-18 11:07:36', NULL, NULL, 'none', NULL, NULL),
(33, 1, 6, NULL, '2026-04-18 11:13:00', '2026-04-18 11:28:00', 'pending', 'sfdg', NULL, NULL, '2026-04-18 11:08:40', '2026-04-18 11:08:40', NULL, NULL, 'none', NULL, NULL),
(34, 1, 2, NULL, '2026-04-30 11:35:00', '2026-04-30 11:50:00', 'pending', 'ishini', NULL, NULL, '2026-04-18 11:35:30', '2026-04-18 11:35:30', NULL, NULL, 'none', NULL, NULL);

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
(1, 1, 6, '2026-04-03 19:02:28', '2026-04-17 10:41:27'),
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
(14, 4, 1, '2026-04-04 20:58:54', '2026-04-17 12:22:25'),
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
(27, 66, 1, '2026-04-15 19:39:06', '2026-04-17 12:20:29'),
(28, 66, 56, '2026-04-16 05:53:36', '2026-04-16 05:53:36'),
(29, 66, 6, '2026-04-16 05:53:38', '2026-04-16 05:53:38'),
(30, 66, 2, '2026-04-16 05:53:38', '2026-04-16 05:53:38'),
(31, 66, 57, '2026-04-16 05:53:39', '2026-04-16 05:53:39'),
(32, 66, 62, '2026-04-16 05:53:39', '2026-04-16 05:53:39'),
(33, 66, 58, '2026-04-16 05:53:40', '2026-04-16 05:53:40'),
(34, 4, 5, '2026-04-18 16:33:03', '2026-04-18 16:33:03');

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
(57, 5, 'doctor2@mail.com', '/uploads/verifications/doc2.jpg', 'verified', '2025-10-24 09:17:15', '2026-04-18 17:40:49', '2026-04-18 14:10:49', NULL),
(58, 6, 'docamal@mail.com', '/uploads/verifications/doc3.jpg', 'rejected', '2025-10-24 09:17:15', '2026-04-18 17:44:35', '2026-04-18 14:14:35', 'bad'),
(62, 72, 'dulana216@gmail.com', 'uploads/verifications/72/verification_72_1776518152_56dba1fcfa2de495.jpg', 'verified', '2026-04-18 18:45:52', '2026-04-18 18:46:15', '2026-04-18 15:16:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `isbm_channels`
--

CREATE TABLE `isbm_channels` (
  `uri` varchar(500) NOT NULL,
  `channel_type` varchar(50) NOT NULL DEFAULT 'Publication',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `isbm_messages`
--

CREATE TABLE `isbm_messages` (
  `message_id` varchar(36) NOT NULL,
  `session_id` varchar(36) NOT NULL,
  `channel_uri` varchar(500) NOT NULL,
  `topics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`topics`)),
  `media_type` varchar(255) NOT NULL DEFAULT 'application/json',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `isbm_sessions`
--

CREATE TABLE `isbm_sessions` (
  `session_id` varchar(36) NOT NULL,
  `channel_uri` varchar(500) NOT NULL,
  `session_type` varchar(50) NOT NULL DEFAULT 'PublicationProvider',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `record_name` varchar(255) NOT NULL,
  `record_type` enum('lab','scan','prescription','hospital','vaccination') NOT NULL,
  `doctor_name` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` int(10) UNSIGNED NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `record_name`, `record_type`, `doctor_name`, `description`, `file_name`, `original_name`, `file_size`, `mime_type`, `uploaded_at`) VALUES
(1, 1, 'Scan / Imaging', 'scan', 'ggh', 'asdf', 'rec_69e3641a7ff13_7c3c02b2.jpg', 'jump.jpg', 1353906, 'image/jpeg', '2026-04-18 16:29:38');

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
(226, 1, 6, '{\"text\":\"doc\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1776321217_1464849f1ed8_MA-CR-09-Transactions-Management.pdf\",\"path\":\"uploads/chat_attachments/6/chat_1776321217_1464849f1ed8_MA-CR-09-Transactions-Management.pdf\",\"name\":\"MA-CR-09-Transactions-Management.pdf\",\"type\":\"application/pdf\",\"size\":387423}}', 1, '2026-04-16 06:33:37'),
(228, 1, 6, 'test', 1, '2026-04-17 10:40:41'),
(229, 1, 1, 'received', 1, '2026-04-17 10:41:03'),
(230, 1, 6, '{\"text\":\"test\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/6/chat_1776422487_b10447a76ca8_2025_-_SCS3203_Answer_Script.pdf\",\"path\":\"uploads/chat_attachments/6/chat_1776422487_b10447a76ca8_2025_-_SCS3203_Answer_Script.pdf\",\"name\":\"2025 - SCS3203_Answer_Script.pdf\",\"type\":\"application/pdf\",\"size\":253460}}', 1, '2026-04-17 10:41:27'),
(231, 27, 1, '{\"text\":\"\",\"attachment\":{\"url\":\"http://localhost/MVC/uploads/chat_attachments/1/chat_1776428429_e1ae398e625d_jump.jpg\",\"path\":\"uploads/chat_attachments/1/chat_1776428429_e1ae398e625d_jump.jpg\",\"name\":\"jump.jpg\",\"type\":\"image/jpeg\",\"size\":1353906}}', 0, '2026-04-17 12:20:29'),
(232, 14, 4, 'www.google.com', 1, '2026-04-17 12:22:25');

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
(26, 'all', NULL, 'Test 1', 'This is a test.', 'info', 'sent', '2025-12-11 14:21:35', NULL),
(27, 'all', NULL, 'fuck', 'aaaaaaa', 'info', 'sent', '2026-04-17 21:57:49', NULL),
(28, 'all', NULL, 'aaaa', 'aaa', 'info', 'sent', '2026-04-18 11:04:40', NULL),
(29, 'doctor', NULL, 'Test', 'aaa', 'info', 'sent', '2026-04-18 13:05:48', NULL),
(30, 'all', NULL, 'Test 1234', 'fuck fuck fuck fuck', 'info', 'sent', '2026-04-18 13:06:09', NULL),
(31, 'patient', NULL, 'Testing', 'Sytesm shutdown', 'info', 'sent', '2026-04-18 17:19:02', NULL),
(32, 'doctor', NULL, 'Test Night', 'Actual Notifications', 'info', 'sent', '2026-04-18 21:35:32', NULL),
(33, 'all', NULL, 'System Maintenance', '12345', 'info', 'sent', '2026-04-18 21:55:29', NULL),
(34, 'admin', NULL, 'AA', 'AAA', 'info', 'sent', '2026-04-18 22:39:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `otp`, `expires_at`, `used`, `created_at`) VALUES
(1, 'dulana216@gmail.com', '884113', '2026-04-18 14:39:59', 1, '2026-04-18 17:54:59'),
(2, '2023cs215@stu.ucsc.cmb.ac.lk', '829884', '2026-04-18 14:42:18', 1, '2026-04-18 17:57:18'),
(3, 'dulana217@gmail.com', '899589', '2026-04-18 15:37:04', 1, '2026-04-18 18:52:04'),
(4, 'docamal@mail.com', '745165', '2026-04-19 07:10:08', 0, '2026-04-19 10:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `patient_medical_info`
--

CREATE TABLE `patient_medical_info` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `blood_type` varchar(8) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `emergency_contact` varchar(50) DEFAULT NULL,
  `insurance_provider` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(12, 5, 1, 'Omeprazole', 'Capsule', 'Oral', 0, 0, NULL, NULL, '20', 'mg', 'Once daily', NULL, NULL, NULL, 7, 'days', 'Take before breakfast', 7, 'Capsule', 'Gastritis', '2025-11-05', NULL, NULL, 'not_deleted', '2025-10-24 03:51:20', '2025-10-24 03:51:20'),
(13, 6, 1, 'Paracetamol', '500mg tablet', 'Oral', 0, 0, NULL, NULL, '1', 'tablet', 'OD', NULL, '9:00 AM', 'Irrelevant', 7, 'Days', NULL, NULL, NULL, 'Headache', '2026-04-26', NULL, NULL, 'not_deleted', '2026-04-19 05:02:22', NULL);

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
(10, 'patient', 1, 'doctor', 5, 'Service Feedback', 'Very helpful session', 'Doctor2 provided clear guidance and was very professional.', 'resolved', NULL, NULL, NULL, '2025-10-24 03:51:20', '2025-10-24 03:51:20'),
(11, 'doctor', 6, 'system', NULL, 'Call Report', 'Abusive or offensive communication', 'Context: In call. Appointment #31', 'pending', NULL, NULL, NULL, '2026-04-18 11:29:54', '2026-04-18 11:29:54'),
(12, 'doctor', 6, 'patient', 64, 'User Report', 'Abuse or harassment', 'Context: Conversation #7. Reported user ID: 64. Latest message: hhi gioo', 'pending', NULL, NULL, NULL, '2026-04-18 12:48:38', '2026-04-18 12:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `shared_medical_records`
--

CREATE TABLE `shared_medical_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `record_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `shared_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shared_medical_records`
--

INSERT INTO `shared_medical_records` (`id`, `record_id`, `doctor_id`, `shared_at`) VALUES
(1, 1, 5, '2026-04-18 18:24:32');

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
  `status` enum('active','inactive','suspended','unverified') DEFAULT 'unverified',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `slmc` varchar(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT current_timestamp(),
  `last_notification_seen_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`, `slmc`, `profile_image`, `last_activity`, `last_notification_seen_id`) VALUES
(1, 'patient1', 'patient1@mail.com', '$2y$10$VhsQ8vaQ51QpL0C51XE2B.rR2REeazkoujd1ohPLL9xBhN7X5O/Da', 'Patient', 'active', '2025-10-23 13:11:25', '2026-04-19 10:22:56', NULL, 'uploads/profile_images/1/profile_1_1776574376_d2c65fc3578b.jpg', '2026-04-16 06:25:02', 33),
(2, 'doctor1', 'doctor1@mail.com', '$2y$10$p7PRv.Ct/uRStd5KR9ehJ.Qcixc63Hg.GZ5YZy6MoSDOIQp7DITcW', 'Doctor', 'suspended', '2025-10-23 13:12:16', '2026-04-18 17:15:50', '1234', NULL, '2026-04-16 06:25:02', NULL),
(3, 'patient2', 'patient2@mail.com', '$2y$10$dru4qaj4KReU6NbYHRVADu2.cQWp4ljYv7jYdoUlpNDc5KRJFE3da', 'Patient', 'inactive', '2025-10-23 13:13:21', '2025-10-23 13:13:21', NULL, NULL, '2026-04-16 06:25:02', NULL),
(4, 'admin1', 'admin1@mail.com', '$2y$10$tRcm9ygnMIkFCZVWz/8SweMzux6MyzMq.SHYNTby8kf3qNn/mkije', 'Admin', 'inactive', '2025-10-23 13:36:19', '2026-04-18 22:41:11', NULL, 'uploads/profile_images/4/profile_4_1776508126_9ba9d5fda4bb.jpg', '2026-04-16 06:25:02', 34),
(5, 'doctor2', 'doctor2@mail.com', '$2y$10$xjQJdj4xwy/33VlfhUiH1OWOyVQBE4nHdCvQCADmXkYOE66J6zQi.', 'Doctor', 'active', '2025-10-23 18:33:54', '2026-04-18 17:40:49', '2234', NULL, '2026-04-16 06:25:02', NULL),
(6, 'amal', 'docamal@mail.com', '$2y$10$OMiikJlhYqGzdCgrG2BfvOLKCyVg9rTvRjcwABLYFVaDoEGf44Dxi', 'Doctor', 'active', '2025-10-24 02:06:47', '2026-04-19 10:25:54', '3234', NULL, '2026-04-16 06:25:02', 33),
(55, 'Roshan Fernando', 'roshan.fernando@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Yal4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-12 15:30:00', '2025-01-22 09:15:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(56, 'Dilani Perera', 'dilani.perera@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-13 11:45:00', '2025-01-23 16:45:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(57, 'Kumar Dissanayake', 'kumar.dissanayake@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-14 07:30:00', '2025-01-24 13:20:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(58, 'Malini Rajapaksa', 'malini.rajapaksa@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-15 09:45:00', '2025-01-25 15:40:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(59, 'Tharaka Amarasinghe', 'tharaka.amarasinghe@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'active', '2025-01-16 08:15:00', '2025-01-26 12:30:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(60, 'Chaminda Gunasekara', 'chaminda.gunasekara@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-05 13:20:00', '2025-01-12 15:30:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(61, 'Sunil Wijeratne', 'sunil.wijeratne@patient.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patient', 'suspended', '2025-01-08 10:30:00', '2025-01-25 12:00:00', NULL, NULL, '2026-04-16 06:25:02', NULL),
(62, 'lakmal', 'doclakmal@gmail.com', '$2y$10$Mq8WPQvJJ4A2gjktIAKdAeTF5xeMR9UfWgC9WMVce7mF82ALjQaQS', 'Doctor', 'suspended', '2025-10-24 10:10:07', '2026-04-18 16:00:24', '00007', NULL, '2026-04-16 06:25:02', NULL),
(63, 'ishinipatient', 'ishinipatient@gmail.com', '$2y$10$.IdLQoQ4ndIn00bKd7LdnuOp7ob4.YNG7DDBIlslFvEnF/Zd8fhAm', 'Patient', 'active', '2026-04-04 00:55:57', '2026-04-05 01:49:14', NULL, NULL, '2026-04-03 13:55:57', NULL),
(64, 'ishini', 'ishiniayodya9@gmail.com', '$2y$10$ya2.74VTOnFXHg5Ahr91vOP64N4qi9qziRXEwZk60AREDB.oa.zza', 'Patient', 'active', '2026-04-05 01:31:25', '2026-04-05 01:49:09', NULL, NULL, '2026-04-04 14:31:25', NULL),
(65, 'ayodya piumandhi', 'ayodya@gmail.com', '$2y$10$LGb24XUcN/o93SBNSF9WD.XjVGAmXruRVwx5xyQqxfz6aUQ7xif3m', 'Patient', 'inactive', '2026-04-15 21:39:41', '2026-04-15 21:39:41', NULL, NULL, '2026-04-15 10:39:41', NULL),
(66, 'adnin ish', 'admin2@mail.com', '$2y$10$kIcxq0btPs68WntWvdmg1uST020OXI/VZNmdDyWaXSA2zUeWPjMzq', 'Admin', 'inactive', '2026-04-15 23:32:38', '2026-04-15 23:32:38', NULL, NULL, '2026-04-15 12:32:38', NULL),
(71, 'Dulana', '2023cs215@stu.ucsc.cmb.ac.lk', '$2y$10$CrhxREIk6t3l.ay18ZMDd.p626CQCu9n8Za0mvZ/MF8y2uCbjiWnm', 'Patient', 'unverified', '2026-04-18 18:27:40', '2026-04-18 22:06:38', NULL, NULL, '2026-04-18 12:57:40', 33),
(72, 'Dulana', 'dulana216@gmail.com', '$2y$10$Igpxn0uFz7H3RCjuOh7PvufgptdPaQdyy6Z8Kjld4xsjx2tRmtluy', 'Doctor', 'active', '2026-04-18 18:28:52', '2026-04-18 22:01:33', '00010', NULL, '2026-04-18 12:58:52', 33),
(73, 'Dulana', 'dulana217@gmail.com', '$2y$10$yhm5UjDgSqgbIJoUHVJ56O7g0Ti98Ap6Cd8no/zCULY2N8ch.FF2u', 'Patient', 'active', '2026-04-18 18:52:31', '2026-04-18 18:52:31', NULL, NULL, '2026-04-18 13:22:31', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

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
-- Indexes for table `isbm_channels`
--
ALTER TABLE `isbm_channels`
  ADD PRIMARY KEY (`uri`);

--
-- Indexes for table `isbm_messages`
--
ALTER TABLE `isbm_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `idx_channel_unread` (`channel_uri`,`is_read`,`expires_at`);

--
-- Indexes for table `isbm_sessions`
--
ALTER TABLE `isbm_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `channel_uri` (`channel_uri`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_patient_id` (`patient_id`),
  ADD KEY `idx_record_type` (`record_type`),
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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `patient_medical_info`
--
ALTER TABLE `patient_medical_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`),
  ADD KEY `idx_patient_medical_info_patient_id` (`patient_id`);

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
-- Indexes for table `shared_medical_records`
--
ALTER TABLE `shared_medical_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_share` (`record_id`,`doctor_id`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_doctor_id` (`doctor_id`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `availability_slots`
--
ALTER TABLE `availability_slots`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `doctor_verifications`
--
ALTER TABLE `doctor_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient_medical_info`
--
ALTER TABLE `patient_medical_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shared_medical_records`
--
ALTER TABLE `shared_medical_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Constraints for table `isbm_messages`
--
ALTER TABLE `isbm_messages`
  ADD CONSTRAINT `isbm_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `isbm_sessions` (`session_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `isbm_messages_ibfk_2` FOREIGN KEY (`channel_uri`) REFERENCES `isbm_channels` (`uri`) ON DELETE CASCADE;

--
-- Constraints for table `isbm_sessions`
--
ALTER TABLE `isbm_sessions`
  ADD CONSTRAINT `isbm_sessions_ibfk_1` FOREIGN KEY (`channel_uri`) REFERENCES `isbm_channels` (`uri`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `fk_mr_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `shared_medical_records`
--
ALTER TABLE `shared_medical_records`
  ADD CONSTRAINT `fk_shared_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_shared_medical_record` FOREIGN KEY (`record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

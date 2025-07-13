-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 08:59 PM
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
-- Database: `car_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `iqama` varchar(255) NOT NULL,
  `contract_number` varchar(255) NOT NULL,
  `contract_name` varchar(255) NOT NULL,
  `contract_date` date NOT NULL,
  `start_date` date NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `remaining_value` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `client_name`, `mobile`, `iqama`, `contract_number`, `contract_name`, `contract_date`, `start_date`, `total_value`, `remaining_value`, `created_at`, `updated_at`) VALUES
(1, 'aman shah', '0551981751', '1212353737', '12222', '1222', '2025-05-10', '2025-05-17', 12221.00, 12222.00, '2025-05-31 04:30:11', '2025-05-31 04:30:11'),
(2, 'khan', '055526265262', '02536363522', '25353', 'sure anem', '2025-05-02', '2025-05-31', 122221.00, 12221.00, '2025-05-31 04:32:48', '2025-05-31 04:32:48'),
(3, 'Aman shah', '0551626262', '2453728282', '24334', 'system', '2025-05-17', '2025-05-23', 200000.00, 22222.00, '2025-05-31 14:24:24', '2025-05-31 14:24:24'),
(4, 'atta', '1324252', '2344144', '11333', 'car', '2025-05-01', '2025-05-14', 20000.00, 0.00, '2025-05-31 15:48:55', '2025-05-31 15:48:55'),
(5, 'أحمد محمد', '0551234567', '1234567890', 'AC001', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:05:36', '2025-06-01 15:05:36'),
(6, 'أحمد محمد', '0551234567', '1234567890', 'AC001', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:06:18', '2025-06-01 15:06:18'),
(7, 'أحمد محمد', '0551234567', '1234567890', 'AC001', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:11:53', '2025-06-01 15:11:53'),
(8, 'أحمد محمد', '0551234567', '1234567890', 'AC001', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(9, 'أحمد محمد', '2344535533', '3555335335', '33555', 'عقد صيانة', '2024-02-12', '2024-02-12', 23000.00, 3300.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(10, 'أحمد محمد', '0551234567', '1234567890', 'AC003', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(11, 'أحمد محمد', '0551234567', '1234567890', 'AC004', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(12, 'أحمد محمد', '0551234567', '1234567890', 'AC005', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(13, 'أحمد محمد', '0551234567', '1234567890', 'AC006', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(14, 'أحمد محمد', '0551234567', '1234567890', 'AC007', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(15, 'أحمد محمد', '0551234567', '1234567890', 'AC008', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(16, 'أحمد محمد', '0551234567', '1234567890', 'AC009', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(17, 'أحمد محمد', '0551234567', '1234567890', 'AC010', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:13:35', '2025-06-01 15:13:35'),
(18, 'للب بببب', '0551234567', '1234567890', 'AC001', 'عقد صيانة', '2023-02-12', '2023-02-12', 15000.00, 5000.00, '2025-06-01 15:32:04', '2025-06-01 15:32:04'),
(19, 'لللللل لسيسس', '2344535533', '3555335335', '33555', 'عقد صيانة', '2024-02-12', '2024-02-12', 23000.00, 3300.00, '2025-06-01 15:32:04', '2025-06-01 15:32:04'),
(20, 'ايلس سسلس', '03344444', '3444443', '334433', '22434', '2025-06-13', '2025-06-25', 424432.00, 243243.00, '2025-06-01 15:33:09', '2025-06-01 15:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_number` varchar(255) NOT NULL,
  `vehicle_color` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `vehicle_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vehicle_images`)),
  `parts_select` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`parts_select`)),
  `parts_manual` text DEFAULT NULL,
  `authorized_personnel` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `user_id`, `vehicle_number`, `vehicle_color`, `vehicle_model`, `vehicle_images`, `parts_select`, `parts_manual`, `authorized_personnel`, `created_at`, `updated_at`) VALUES
(1, 3, '2334', 'black', '2000', '[\"1748717241_Screenshot 2025-05-16 212756.png\"]', '[\"\\u0627\\u0631\\u062a\\u0641\\u0627\\u0639 \\u062d\\u0631\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0645\\u062d\\u0631\\u0643\"]', 'dhdgdgd', 'dafaffaf', '2025-05-31 15:47:21', '2025-05-31 15:47:21'),
(2, 4, '34443', 'black', '2011', '[\"1748802464_Screenshot 2025-05-16 194042.png\"]', '[\"\\u062e\\u0644\\u0644 \\u0641\\u064a \\u0646\\u0638\\u0627\\u0645 \\u0627\\u0644\\u0643\\u0647\\u0631\\u0628\\u0627\\u0621\"]', 'dddsas', 'sddsa', '2025-06-01 15:27:44', '2025-06-01 15:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_05_25_141955_create_maintenance_requests_table', 1),
(3, '2025_05_28_185934_create_representatives_table', 1),
(4, '2025_05_29_053513_create_cache_table', 1),
(5, '2025_05_31_052005_create_contracts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `representatives`
--

CREATE TABLE `representatives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `representatives`
--

INSERT INTO `representatives` (`id`, `name`, `email`, `phone`, `region`, `avatar`, `password`, `address`, `skills`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Bayt Atiq', 'iamaur.kk99@gmail.com', '0551981751', 'Abu Dhabi', 'avatars/KuHshTaWYhhaEXG3BaHzQi79vlQLSfsKqpkbOfXD.png', '$2y$12$iGwbQq/UgRMEutWg52VvsuP8ev6YcVoShGAtNZKj5x5V2Yzo4rjwe', 'al riyadh saudi Arabia', 'addddfff', 'sdsdsdffsf', '2025-05-31 14:51:28', '2025-05-31 14:51:28'),
(2, 'atta ur rehman', 'att@gmail.com', '2773763636', 'riyadh', 'avatars/klRRQwJLXp0CiOAY20yxNSWSGV1sfn5QnirVeAp9.jpg', '$2y$12$PoDgSMMxZpXf51xYrkP3wuKC.WKRhYAe5HeDxQ/LYZ5UQGrYJocmu', 'dgfaffda', 'ahhhth', 'fhshshsh', '2025-05-31 15:45:23', '2025-05-31 15:45:23'),
(3, 'zulqarnain', 'zul@gmail.com', '65376327676', 'riyah', 'avatars/67rE72i0E7liAO2Wx7T8tqNWT2Kz5u7WnL738hu9.png', '$2y$12$ZUZyb7.1DCLaIoEn71rT4eC7VA6kCkwFiDjwdcfIVnWAWvdVx2WrK', 'fhjhfjfh', 'addddee', 'fdarsfsggs', '2025-06-01 15:24:23', '2025-06-01 15:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `iqama` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `address` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `mobile`, `iqama`, `role`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-05-31 04:02:20', '$2y$12$dSQZJzUt9eJua7ouayhRX.wAcxAtVMH2./tzRA0PDrd46mkI4r8/.', NULL, NULL, 'user', NULL, '9Q6tsV15Gk', '2025-05-31 04:02:20', '2025-05-31 04:02:20'),
(2, 'Admin', 'admin@site.com', NULL, '$2y$12$6TdcLefPwGON0K77tAeUmOc9jp5Ynu95uZHMAG/4SicIvvqhKdxrm', '0500000000', NULL, 'admin', NULL, NULL, '2025-05-31 04:03:26', '2025-05-31 04:03:26'),
(3, 'AMAN zanib', 'baytatiq.web@gmail.com', NULL, '$2y$12$zlPQ4zZ2JOGIdpGtD5.nHOqdKY7pgc1jso/w6BbVGMajKR2nzZt8G', '0551981752', '2427807371', 'user', 'amana', NULL, '2025-05-31 15:43:16', '2025-05-31 15:43:16'),
(4, 'zul', 'zyul@gmail.com', NULL, '$2y$12$yMMY4F1CHzBelaZFtdTPaevF56fY8XtKrfCGN6W/g.2CiILe/t9xC', '434343', '444444', 'user', 'wwww', NULL, '2025-06-01 15:26:10', '2025-06-01 15:26:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `representatives_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

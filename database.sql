-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 18, 2025 at 09:17 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ghartution`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('parent','tutor','admin') DEFAULT 'parent',
  `cv` varchar(255) DEFAULT NULL,
  `tutor_location` enum('Kathmandu','Bhaktapur','Lalitpur') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `age`, `gender`, `email`, `phone_number`, `password`, `role`, `cv`, `tutor_location`, `profile_picture`, `created_at`) VALUES
(1, 'Sandesh', 'Shrestha', 21, 'male', 'sandesh@gmail.com', '+977 9812345678', '$2y$10$CFcg4soa6U4kZlct5FZyCefXIfdA3KD2GeQD8iDlwDLQrjHGkGSRy', 'parent', NULL, NULL, NULL, '2025-02-12 11:33:29'),
(2, 'Tutor', 'Nepal', 21, 'male', 'tutor@gmail.com', '+977 9812389484', '$2y$10$.P2t.4IHdKbpU5erkEIRlugIr5gKs/e7xKdS9jJ1iAkzWV1lhiE96', 'tutor', 'uploads/67ad9bf562d68.pdf', 'Kathmandu', NULL, '2025-02-13 07:15:01'),
(3, 'Admin', 'User', 30, 'male', 'admin@example.com', '+977 9876543210', '$2y$10$NgW29s67RKKtk1gJnF7CZe2M1ke97fa4OLGb57URuj3EUew9.d2Kq', 'admin', NULL, NULL, NULL, '2025-02-13 07:17:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

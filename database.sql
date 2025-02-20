-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2025 at 04:19 PM
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
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `tutor_id` int NOT NULL,
  `applied_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `post_id`, `tutor_id`, `applied_at`, `status`) VALUES
(1, 1, 2, '2025-02-13 07:20:18', 'accepted'),
(2, 4, 2, '2025-02-17 06:40:54', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_posts`
--

CREATE TABLE `tuition_posts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tuition_type` enum('online','offline') NOT NULL,
  `gender_preferred` enum('any','male','female') DEFAULT 'any',
  `grade` varchar(20) NOT NULL,
  `subjects` varchar(255) NOT NULL,
  `class_start_time` varchar(50) NOT NULL,
  `class_duration` int NOT NULL,
  `no_of_students` int NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `post_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tuition_posts`
--

INSERT INTO `tuition_posts` (`id`, `user_id`, `title`, `description`, `tuition_type`, `gender_preferred`, `grade`, `subjects`, `class_start_time`, `class_duration`, `no_of_students`, `category`, `price`, `status`, `post_date`) VALUES
(1, 1, 'Tutor Wanted for Grade 12 Samajik and Business Management in Kupondole, Lalitpur', 'Tutor Wanted for Grade 12 Samajik and Business Management in Kupondole, Lalitpur.', 'online', 'any', '+2', 'All Subjects', '5 pm', 1, 2, 'For whole year', 9750.00, 'accepted', '2025-02-12 11:35:35'),
(2, 1, 'Grade 11', 'This is grade 11', 'offline', 'male', '+2', 'All Subjects', '6pm', 1, 1, 'For 3 months', 7800.00, 'accepted', '2025-02-16 09:16:53'),
(3, 1, 'sadsad', 'asdsad', 'online', 'any', 'Grade 9-10', 'All Subjects', '5 pm', 2, 2, 'For 3 months', 12000.00, 'rejected', '2025-02-16 09:29:15'),
(4, 1, 'sad', 'adsa', 'offline', 'male', '+2', 'All Subjects', '5 pm', 2, 1, 'For exam only', 12480.00, 'accepted', '2025-02-16 09:29:50'),
(6, 1, 'Tution Needed for grade 12 students. Urgent....!!!!!!', 'I want a teacher for grade 12 students that is passionate about teaching. Shankar is a bit shy and tends to avoid talking with stangers.', 'offline', 'female', '+2', 'Maths. Chemistry', '6pm', 2, 1, 'For exam only', 12480.00, 'pending', '2025-02-17 06:47:04');

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
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `tuition_posts`
--
ALTER TABLE `tuition_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tuition_posts`
--
ALTER TABLE `tuition_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tuition_posts` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tuition_posts`
--
ALTER TABLE `tuition_posts`
  ADD CONSTRAINT `tuition_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2023 at 12:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poll_maker`
--
CREATE DATABASE IF NOT EXISTS `poll_maker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `poll_maker`;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(20) NOT NULL,
  `answer` varchar(250) NOT NULL,
  `num_votes` int(50) NOT NULL,
  `poll_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `answer`, `num_votes`, `poll_id`) VALUES
(65, '18-20', 1, 52),
(66, '20-25', 1, 52),
(67, '25-30', 0, 52),
(68, 'Bahrain', 1, 53),
(69, 'KSA', 1, 53),
(70, 'CS', 0, 54),
(71, 'NE', 0, 54),
(72, 'SE', 0, 54),
(81, 'cdcd', 1, 61),
(82, 'dddd', 0, 61);

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE `poll` (
  `poll_id` int(20) NOT NULL,
  `question` varchar(250) NOT NULL,
  `total_votes` int(50) NOT NULL,
  `user_id` int(20) NOT NULL,
  `poll_status` varchar(15) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll`
--

INSERT INTO `poll` (`poll_id`, `question`, `total_votes`, `user_id`, `poll_status`, `start_date`, `end_date`) VALUES
(52, 'How old are you?', 2, 14, 'active', '2023-12-13', '2023-12-29'),
(53, 'Where are you from?', 2, 14, 'inactive', '0000-00-00', '0000-00-00'),
(54, 'Your major', 0, 14, 'active', '0000-00-00', '0000-00-00'),
(61, 'sbshdcds', 1, 14, 'active', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(14, 'Zeshan Ahmed', 'zeshanbh@gmail.com', '$2y$10$E0VWK1AB3rb5PtwkeBtZ1OiPzrKpem2BoqMxY4kzkbTpyIC1BdEv.', ''),
(15, 'Hesham Ahmed', 'xxxx@gmail.com', '$2y$10$QJQ.ml8PnjUI5HNgfybXj.xsNZn.SrkVBA5sDahB9vmWeIsrMhOiy', '');

-- --------------------------------------------------------

--
-- Table structure for table `voted`
--

CREATE TABLE `voted` (
  `vote_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `answer_id` int(20) NOT NULL,
  `poll_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voted`
--

INSERT INTO `voted` (`vote_id`, `user_id`, `answer_id`, `poll_id`) VALUES
(5, 15, 65, 52),
(6, 15, 69, 53),
(7, 14, 66, 52),
(8, 14, 68, 53),
(9, 14, 81, 61);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `Test1` (`poll_id`);

--
-- Indexes for table `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`poll_id`),
  ADD KEY `Test` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `voted`
--
ALTER TABLE `voted`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `test3` (`user_id`),
  ADD KEY `test4` (`answer_id`),
  ADD KEY `test5` (`poll_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `poll`
--
ALTER TABLE `poll`
  MODIFY `poll_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `voted`
--
ALTER TABLE `voted`
  MODIFY `vote_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `Test1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`poll_id`);

--
-- Constraints for table `poll`
--
ALTER TABLE `poll`
  ADD CONSTRAINT `Test` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `voted`
--
ALTER TABLE `voted`
  ADD CONSTRAINT `test3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `test4` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`answer_id`),
  ADD CONSTRAINT `test5` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`poll_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
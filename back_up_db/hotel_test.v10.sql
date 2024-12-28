-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 09:00 AM
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
-- Database: `hotel_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `reservation_id`, `amount`, `payment_date`, `payment_time`) VALUES
(18, 147, 150, '2024-12-21', '15:55:29'),
(19, 159, 500, '2024-12-22', '08:31:05'),
(20, 161, 100, '2024-12-22', '08:31:25'),
(21, 160, 100, '2024-12-22', '08:31:37'),
(22, 162, 100, '2024-12-22', '08:32:40'),
(23, 163, 100, '2024-12-22', '08:32:51'),
(24, 164, 100, '2024-12-22', '08:33:02'),
(25, 165, 100, '2024-12-22', '08:39:42'),
(26, 166, 100, '2024-12-22', '15:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Unpaid',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `total_cost`, `status`, `created_at`, `rating`) VALUES
(147, 8, 150, 'Paid', '2024-12-22 15:16:11', NULL),
(149, 8, 1000, 'Unpaid', '2024-12-22 15:16:11', NULL),
(150, 8, 450, 'Unpaid', '2024-12-22 15:16:11', NULL),
(159, 9, 500, 'Paid', '2024-12-22 08:30:14', NULL),
(160, 9, 100, 'Paid', '2024-12-22 08:30:19', NULL),
(161, 9, 100, 'Paid', '2024-12-22 08:30:24', NULL),
(162, 9, 100, 'Paid', '2024-12-22 08:32:19', NULL),
(163, 9, 100, 'Paid', '2024-12-22 08:32:24', NULL),
(164, 9, 100, 'Paid', '2024-12-22 08:32:27', NULL),
(165, 9, 100, 'Paid', '2024-12-22 08:39:27', NULL),
(166, 9, 100, 'Paid', '2024-12-22 08:41:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation_line`
--

CREATE TABLE `reservation_line` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation_line`
--

INSERT INTO `reservation_line` (`id`, `room_id`, `reservation_id`, `check_in`, `check_out`, `subtotal`) VALUES
(142, 3, 147, '2024-12-21', '2024-12-22', 150),
(144, 4, 149, '2024-12-22', '2024-12-27', 1000),
(145, 3, 150, '2024-12-23', '2024-12-26', 450),
(154, 5, 159, '2024-12-22', '2024-12-23', 500),
(155, 1, 160, '2024-12-22', '2024-12-23', 100),
(156, 1, 161, '2024-12-22', '2024-12-23', 100),
(157, 1, 162, '2024-12-22', '2024-12-23', 100),
(158, 1, 163, '2024-12-22', '2024-12-23', 100),
(159, 1, 164, '2024-12-22', '2024-12-23', 100),
(160, 1, 165, '2024-12-22', '2024-12-23', 100),
(161, 1, 166, '2024-12-22', '2024-12-23', 100);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_type` varchar(11) NOT NULL,
  `price` int(11) NOT NULL,
  `availability_status` varchar(11) NOT NULL DEFAULT 'available',
  `number_of_rooms` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type`, `price`, `availability_status`, `number_of_rooms`) VALUES
(1, 'Stuites', 2000, 'available', 7),
(3, 'Standard', 1200, 'available', 10),
(4, 'Famlily', 3000, 'available', 10),
(5, 'FamlilyBig', 2300, 'available', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `number`, `password`, `user_type`, `created_at`) VALUES
(7, 'admin', 'admin', 'admin@gmail.com', 912902901, '123', 'admin', '2024-12-14 13:02:49'),
(8, 'rhod', 'sol', 'sol@gmail.com', 2147483647, '123', 'user', '2024-12-14 13:08:35'),
(9, 'customer', 'number1', 'customer1@gmail.com', 909990, '123', 'user', '2024-12-20 09:45:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `reservation_line`
--
ALTER TABLE `reservation_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id_fk` (`reservation_id`),
  ADD KEY `room_id_fk` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `reservation_line`
--
ALTER TABLE `reservation_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_line`
--
ALTER TABLE `reservation_line`
  ADD CONSTRAINT `reservation_id_fk` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_id_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

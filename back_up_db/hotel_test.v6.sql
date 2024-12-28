-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 12:48 AM
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
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `total_cost`, `status`) VALUES
(86, 3, 500, 'Fully Paid'),
(87, 3, 1000, 'Unpaid'),
(88, 3, 1500, 'Unpaid'),
(89, 3, 1000, 'Unpaid'),
(90, 3, 300, 'Unpaid'),
(91, 3, 300, 'Unpaid'),
(92, 3, 100, 'Unpaid'),
(93, 3, 100, 'Unpaid'),
(94, 3, 200, 'Unpaid'),
(95, 3, 150, 'Unpaid'),
(96, 3, 150, 'Unpaid'),
(97, 3, 150, 'Unpaid'),
(98, 3, 150, 'Unpaid'),
(99, 3, 150, 'Unpaid'),
(100, 3, 200, 'Unpaid'),
(101, 3, 200, 'Unpaid'),
(102, 3, 400, 'Unpaid'),
(103, 3, 600, 'Unpaid'),
(104, 3, 400, 'Unpaid');

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
(81, 5, 86, '2024-11-25', '2024-11-26', 500),
(82, 5, 87, '2024-11-24', '2024-11-26', 1000),
(83, 5, 88, '2024-11-24', '2024-11-27', 1500),
(84, 5, 89, '2024-11-24', '2024-11-26', 1000),
(85, 1, 90, '2024-11-24', '2024-11-27', 300),
(86, 1, 91, '2024-11-24', '2024-11-27', 300),
(87, 1, 92, '2024-11-28', '2024-11-29', 100),
(88, 1, 93, '2024-11-28', '2024-11-29', 100),
(89, 1, 94, '2024-11-24', '2024-11-26', 200),
(90, 3, 95, '2024-11-24', '2024-11-25', 150),
(91, 3, 96, '2024-11-24', '2024-11-25', 150),
(92, 3, 97, '2024-11-24', '2024-11-25', 150),
(93, 3, 98, '2024-11-24', '2024-11-25', 150),
(94, 3, 99, '2024-11-24', '2024-11-25', 150),
(95, 4, 100, '2024-11-24', '2024-11-25', 200),
(96, 4, 101, '2024-11-24', '2024-11-25', 200),
(97, 4, 102, '2024-11-27', '2024-11-29', 400),
(98, 4, 103, '2024-11-24', '2024-11-27', 600),
(99, 4, 104, '2024-11-24', '2024-11-26', 400);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_type` varchar(11) NOT NULL,
  `price` int(11) NOT NULL,
  `availability_status` varchar(11) NOT NULL,
  `number_of_rooms` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type`, `price`, `availability_status`, `number_of_rooms`) VALUES
(1, 'Stuites', 100, 'available', 0),
(3, 'Standard', 150, 'available', 0),
(4, 'Famlily', 200, 'available', 0),
(5, 'FamlilyBig', 500, 'available', 5);

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
(3, 'Rhod Celister', 'Sol', 'sol@gmail.com', 2147483647, '$2y$10$GT7xRrdUj9457vmsBxceB.JNuswalVDfU3rm15ubpuAHReDFOt9cq', 'user', '2024-11-01 12:55:57'),
(4, 'admin', 'admin', 'admin@gmail.com', 2147483647, '$2y$10$a.eNqng6YCYVS8YBg6M/TuaguPUlXN/08QMiDInw86Ux8oM9JH.Uu', 'admin', '2024-11-01 14:08:22'),
(5, 'not', 'not', 'not@gmail.com', 2147483647, '$2y$10$GTX6nr4ZWWmuSyd4agNvuee9Pjyma.hPDBj8YIhBy.G/ffxPtsU/e', 'user', '2024-11-02 02:30:38'),
(6, 'kernel', 'gocotano', 'kernel@gmail.com', 909909, '$2y$10$Tcn20PeYCy6SpDE4cjG9seM6e/kQS5aHR6gH3xy18gwwEOiZOC6/m', 'user', '2024-11-10 08:16:59');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `reservation_line`
--
ALTER TABLE `reservation_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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

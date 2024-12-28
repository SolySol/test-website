-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 05:58 AM
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
-- Database: `hotel_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rooms` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `adults` int(11) NOT NULL,
  `childs` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `rooms`, `check_in`, `check_out`, `adults`, `childs`, `created_at`) VALUES
(9, 3, 1, '2222-02-22', '2222-02-22', 2, 2, '2024-11-01 13:01:00'),
(11, 3, 2, '3333-03-31', '3333-03-31', 2, 1, '2024-11-01 14:04:38'),
(12, 3, 1, '0000-00-00', '0000-00-00', 2, 0, '2024-11-01 14:06:14'),
(13, 3, 5, '3333-03-31', '3333-03-31', 2, 3, '2024-11-01 14:06:28'),
(14, 3, 6, '3333-03-31', '3333-03-31', 2, 1, '2024-11-01 14:06:40'),
(15, 3, 6, '3333-03-31', '3333-03-31', 3, 6, '2024-11-01 14:06:51'),
(16, 3, 6, '3333-03-31', '3333-03-31', 1, 0, '2024-11-01 14:07:02'),
(17, 3, 1, '3333-03-31', '3333-03-31', 1, 0, '2024-11-01 14:07:20'),
(18, 3, 4, '3231-02-01', '1232-03-12', 1, 0, '2024-11-01 14:07:42'),
(20, 3, 1, '1111-11-11', '1111-11-11', 1, 0, '2024-11-02 11:09:28'),
(37, 5, 1, '1111-11-11', '2222-12-11', 1, 0, '2024-11-06 12:15:23'),
(38, 5, 2, '0000-00-00', '1221-12-12', 1, 0, '2024-11-06 12:15:49'),
(39, 5, 1, '0000-00-00', '1212-12-12', 2, 0, '2024-11-06 12:17:19'),
(40, 3, 1, '0000-00-00', '1212-12-12', 1, 0, '2024-11-07 04:51:23'),
(41, 3, 1, '0000-00-00', '0000-00-00', 1, 0, '2024-11-07 04:51:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

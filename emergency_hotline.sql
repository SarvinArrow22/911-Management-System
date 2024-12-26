-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 06:00 AM
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
-- Database: `emergency_hotline`
--

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type_of_service` varchar(50) DEFAULT NULL,
  `call_type` varchar(50) DEFAULT NULL,
  `call_date` date DEFAULT curdate(),
  `call_time` time DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT 1,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_types`
--

CREATE TABLE `call_types` (
  `id` int(11) NOT NULL,
  `call_type` varchar(255) NOT NULL,
  `service_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `call_types`
--

INSERT INTO `call_types` (`id`, `call_type`, `service_type_id`) VALUES
(7, 'Riot', 1),
(8, 'Police Assistance', 1),
(9, 'Illegal Activity', 1),
(10, 'Bush Fire', 2),
(11, 'Residential Fire', 2),
(12, 'Vehicular Fire', 2),
(13, 'Bike Incident', 3),
(14, 'Conduction', 3),
(15, 'Human Error (Self Accident)', 3),
(16, 'Medical Emergency', 3),
(17, 'Vehicular Accident', 4),
(18, 'Drowning Incident', 4),
(19, 'Shooting Incident', 1),
(20, 'Prank Call', 5),
(21, 'Erroneous Inbound Call', 5),
(22, 'Erroneous Outbound Call', 5),
(23, 'General Inquiry', 5);

-- --------------------------------------------------------

--
-- Table structure for table `client_feedback`
--

CREATE TABLE `client_feedback` (
  `id` int(11) NOT NULL,
  `feedback_date` date DEFAULT NULL,
  `feedback_time` time DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `agency_address` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `reference` enum('Trauma','Medical','Conduction','Others') DEFAULT NULL,
  `p1_rating` int(11) DEFAULT NULL,
  `p2_rating` int(11) DEFAULT NULL,
  `p3_rating` int(11) DEFAULT NULL,
  `p4_rating` int(11) DEFAULT NULL,
  `p5_rating` int(11) DEFAULT NULL,
  `overall_rating` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` int(11) NOT NULL,
  `service_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `service_type`) VALUES
(1, 'Police'),
(2, 'Fire'),
(3, 'EMS-Emergency Medical Service'),
(4, 'Rescue'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `team` enum('Alpha','Bravo','Charlie') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_initial`, `last_name`, `address`, `mobile_number`, `email`, `team`, `username`, `password`, `role`) VALUES
(1, 'Emmanuel', 'D', 'Delgado', 'San Lorenzo', '09478287110', 'emandelgado1996@gmail.com', '', 'Administrative', '$2y$10$dN7PYZ0dYyxL0dR4i/RpdubOaEhNyJuOWIdW.HZi44s72hPeHxV9e', 'admin'),
(2, 'Mau', 'M', 'Monedo', 'Irosin Sorsogon', '09478254458', 'user@user.com', 'Alpha', 'UserAgent', '$2y$10$bCnrHwAVviuGdmPkrBM4OOXjjTNTujvMXQ6JtD2rBlpipVJ5O92Pm', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `call_types`
--
ALTER TABLE `call_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_type_id` (`service_type_id`);

--
-- Indexes for table `client_feedback`
--
ALTER TABLE `client_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_types`
--
ALTER TABLE `call_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `client_feedback`
--
ALTER TABLE `client_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD CONSTRAINT `call_logs_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `call_types`
--
ALTER TABLE `call_types`
  ADD CONSTRAINT `call_types_ibfk_1` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

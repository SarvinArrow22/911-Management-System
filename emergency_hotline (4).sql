-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 11:45 AM
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
-- Table structure for table `alpha_tbl`
--

CREATE TABLE `alpha_tbl` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type_of_service` varchar(50) DEFAULT NULL,
  `call_type` varchar(50) DEFAULT NULL,
  `call_date` date DEFAULT curdate(),
  `call_time` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT 1,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending_case','closed_case','') DEFAULT NULL,
  `team` enum('alpha','bravo','charlie','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alpha_tbl`
--

INSERT INTO `alpha_tbl` (`id`, `agent_id`, `type_of_service`, `call_type`, `call_date`, `call_time`, `contact_number`, `call_count`, `name`, `age`, `location`, `reason_of_call`, `actions_taken`, `remarks`, `status`, `team`) VALUES
(1, 2, '1', '7', '2024-12-30', '11:30', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha'),
(2, 2, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha'),
(3, 2, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', '', 'alpha'),
(4, 2, '1', '8', '0000-00-00', '11:30', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha'),
(5, 2, '1', '7', '0000-00-00', '11:30', '09092235329', 1, '', 0, '', '', '', '', '', 'alpha'),
(6, 2, '1', '7', '2024-12-30', '11:30', '09092235329', 1, 'Goldie', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha'),
(7, 2, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', '', 'alpha'),
(8, 2, '1', '7', '2024-12-29', '11:30', '09092235329', 1, 'AddQS', 33, '', '', '', '', '', 'alpha');

-- --------------------------------------------------------

--
-- Table structure for table `bravo_tbl`
--

CREATE TABLE `bravo_tbl` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type_of_service` varchar(50) DEFAULT NULL,
  `call_type` varchar(50) DEFAULT NULL,
  `call_date` date DEFAULT NULL,
  `call_time` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT 1,
  `NAME` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending_case','closed_case','') DEFAULT NULL,
  `team` enum('alpha','bravo','charlie','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bravo_tbl`
--

INSERT INTO `bravo_tbl` (`id`, `agent_id`, `type_of_service`, `call_type`, `call_date`, `call_time`, `contact_number`, `call_count`, `NAME`, `age`, `location`, `reason_of_call`, `actions_taken`, `remarks`, `status`, `team`) VALUES
(1, 3, '4', '17', NULL, '11:30', '09092235329', NULL, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'bravo'),
(2, 3, '2', '10', NULL, '11:30', '111', NULL, '', 0, '', '', '', '', '', 'bravo'),
(3, 3, '1', '7', NULL, '11:30', '09092235329', NULL, 'AddQS', 11, 'Gatbo', 'nONE', 'asa', 'NONE', '', 'bravo'),
(4, 3, '1', '7', NULL, '11:30', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(5, 3, '1', '7', NULL, '11:30', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(6, 3, '1', '7', NULL, '11:30', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(7, 3, '1', '7', NULL, '', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(8, 3, '2', '10', NULL, '', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(9, 3, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(10, 3, '1', '7', '2024-12-30', '11:30', '09092235329', NULL, '', 0, '', '', '', '', '', 'bravo'),
(11, 3, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(12, 3, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(13, 3, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(14, 4, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(15, 3, '1', '7', '2024-12-30', '', '111', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(16, 3, '1', '7', '2024-12-30', '', '111', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(17, 3, '1', '7', '2024-12-30', '', '09092235329', NULL, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(18, 3, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo'),
(19, 3, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'closed_case', 'bravo'),
(20, 3, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'closed_case', 'bravo'),
(21, 3, '1', '7', '2024-12-30', '11:30', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', 'pending_case', 'bravo'),
(22, 3, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo');

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
  `call_time` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT 1,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending_case','closed_case','','') NOT NULL,
  `team` enum('alpha','charlie','bravo','') NOT NULL,
  `alpha_id` int(11) DEFAULT NULL,
  `bravo_id` int(11) DEFAULT NULL,
  `charlie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `call_logs`
--

INSERT INTO `call_logs` (`id`, `agent_id`, `type_of_service`, `call_type`, `call_date`, `call_time`, `contact_number`, `call_count`, `name`, `age`, `location`, `reason_of_call`, `actions_taken`, `remarks`, `status`, `team`, `alpha_id`, `bravo_id`, `charlie_id`) VALUES
(1, NULL, '1', '7', '2024-12-18', '11:30', '1234567890', 1, 'AddQS', 24, 'Gatbo', 'asa', 'asa', 'asa', '', 'alpha', NULL, NULL, NULL),
(2, NULL, '4', '17', '2024-12-18', '09:48:11', '1234567890', 2, 'AddQS', 30, 'Gatbo', 'asa', 'asa', 'asa', '', 'alpha', NULL, NULL, NULL),
(3, NULL, '2', '10', '2024-12-18', NULL, '1234567890', 1, 'Q1', 23, 'Gatbo', 'asa', 'asa', 'asa', '', 'alpha', NULL, NULL, NULL),
(4, NULL, '1', '8', '2024-12-18', NULL, '1234567890', 1, 'AddQS', 23, 'Gatbo', 'asa', 'asa', 'asa', '', 'alpha', NULL, NULL, NULL),
(5, NULL, '1', '7', '2024-12-18', NULL, '1234567890', 1, 'AddQS', 23, 'bACON', 'asa', 'asa', 'NONE', '', 'alpha', NULL, NULL, NULL),
(6, NULL, '1', '8', '2024-12-18', NULL, '09092235329', 1, 'AddQS', 24, 'Gatbo', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(7, NULL, '4', '17', '2024-12-18', '11:30', '09478287110', 1, 'Goldie', 35, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(8, NULL, '1', '7', '2024-12-18', NULL, '09478287110', 1, 'Goldie', 35, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(9, NULL, '1', '8', '2024-12-18', NULL, '09092235329', 1, 'AddQS', 23, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(10, NULL, '2', '11', '2024-12-18', NULL, '1234567890', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(11, NULL, '1', '7', '2024-12-18', '09:48:11', '09092235329', 3, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(12, 2, 'Police', 'Riot', '2024-12-19', '', '1234567890', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(13, 1, '4', '17', '2024-12-19', NULL, '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'asa', 'NONE', '', 'alpha', NULL, NULL, NULL),
(14, 2, 'Others', 'ASA', '2024-12-19', '11:30', '09092235329', 1, 'AddQS', 33, 'Gatbo', 'nONE', 'NONE', 'asa', '', 'alpha', NULL, NULL, NULL),
(15, 1, '1', '7', '2024-12-19', '09:46:36', '1234567890', 1, 'AddQS', 33, 'Gatbo', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(16, 2, 'Fire', 'Bush Fire', '2024-12-19', '09:48:11', '1234567890', 2, 'Goldie', 33, 'Gatbo', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(17, 2, '1', '7', '2024-01-19', '11:30', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(18, 2, 'Police', 'Bush Fire', '2024-12-23', '11:30dsd', '09092235329', 3, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(19, 2, '5', '20', '2024-12-23', '11:30', '09092235329', 1, 'AddQS', 33, 'Gatbo', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(20, 2, '1', '7', '2024-12-26', '09:48:11', '111', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(21, 2, '1', '7', '2024-12-26', '09:48:11', '09092235329', 1, 'AddQS', 33, 'bACON', 'asa', 'asa', 'asa', '', 'alpha', NULL, NULL, NULL),
(22, 2, '1', '7', '2024-12-26', '09:48:11', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(23, 2, '1', '7', '2024-12-26', '09:48:11', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', '', 'alpha', NULL, NULL, NULL),
(24, 2, '1', '', '2024-12-26', '11:30M', '09092235329', 1, 'AddQS', 33, 'Gatbo', 'asa', 'asa', 'asa', 'pending_case', 'alpha', NULL, NULL, NULL),
(25, 2, '2', '10', '2024-12-26', '11:30', '1234567890', 1, 'Q2.php', 33, 'bACON', 'nONE', 'NONE', 'NONE', 'closed_case', 'alpha', NULL, NULL, NULL),
(26, 2, '1', '7', '2024-12-26', '11:30', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', 'closed_case', 'alpha', NULL, NULL, NULL),
(27, 2, '1', '7', '2024-12-26', '11:30', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(28, 3, '1', '7', '2024-12-27', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(29, 3, '1', '7', '2024-12-27', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(30, 2, '1', '7', '2024-12-28', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(31, 2, '1', '7', '2024-12-28', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(32, 3, '1', '7', '2024-12-29', '11:30', '09092235329', 1, 'AddQS', 33, '', 'none', 'none', 'none', 'closed_case', 'alpha', NULL, NULL, NULL),
(33, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(34, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(35, 2, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(36, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(37, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(38, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(39, 3, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'closed_case', 'bravo', NULL, NULL, NULL),
(40, 4, '1', '7', '2024-12-29', '', '09092235329', 1, '', 0, '', '', '', '', 'closed_case', 'bravo', NULL, NULL, NULL),
(41, 3, '1', '7', '2024-12-30', '', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(42, 3, '2', '10', '2024-12-30', '11:30asa', '09092235329', 6, 'AddQS', 0, 'pending_case', '', '', '', 'pending_case', 'bravo', NULL, NULL, NULL),
(43, 2, '1', '7', '2024-12-30', '11:30', '09092235329', 1, '', 0, '', '', '', '', 'pending_case', 'alpha', NULL, NULL, NULL),
(44, 2, '1', '7', '2024-12-30', '11:30', '09092235329', 1, 'AddQS', 33, 'bACON', 'nONE', 'NONE', 'NONE', 'pending_case', 'alpha', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `call_logs_tbl`
--

CREATE TABLE `call_logs_tbl` (
  `id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type_of_services` varchar(50) DEFAULT NULL,
  `call_type` varchar(50) DEFAULT NULL,
  `call_date` date DEFAULT NULL,
  `call_time` time DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `call_type_id` varchar(10) NOT NULL
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
-- Table structure for table `call_types_tbl`
--

CREATE TABLE `call_types_tbl` (
  `id` int(11) DEFAULT NULL,
  `call_type` varchar(255) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `call_type_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charlie_tbl`
--

CREATE TABLE `charlie_tbl` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type_of_service` varchar(50) DEFAULT NULL,
  `call_type` varchar(50) DEFAULT NULL,
  `call_date` date DEFAULT NULL,
  `call_time` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `call_count` int(11) DEFAULT NULL,
  `NAME` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reason_of_call` text DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending_case','closed_case','') DEFAULT NULL,
  `team` enum('alpha','bravo','charlie','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'EMS'),
(4, 'Rescue'),
(5, 'Other'),
(6, 'Others'),
(7, 'ASA');

-- --------------------------------------------------------

--
-- Table structure for table `service_types_tbl`
--

CREATE TABLE `service_types_tbl` (
  `id` int(11) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `call_type_id` varchar(10) NOT NULL,
  `call_type_var` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `team` enum('alpha','bravo','charlie') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_initial`, `last_name`, `address`, `mobile_number`, `email`, `team`, `username`, `password`, `role`) VALUES
(1, 'Emmanuel1', 'D', 'Delgado', 'San Lorenzo', '09478287110', 'emandelgado1996@gmail.com', '', 'Administrative', '$2y$10$dN7PYZ0dYyxL0dR4i/RpdubOaEhNyJuOWIdW.HZi44s72hPeHxV9e', 'admin'),
(2, 'Mau', 'M', 'Monedo', 'Irosin Sorsogon', '09478254458', 'user@user.com', 'alpha', 'UserAgent', '$2y$10$bCnrHwAVviuGdmPkrBM4OOXjjTNTujvMXQ6JtD2rBlpipVJ5O92Pm', 'user'),
(3, 'Melvin1', 'D', 'Esquivel', 'Gatbo', '09090', 'melvinesquivel21@gmail.com', 'bravo', 'lime', '$2y$10$sQUvStdBkO23ZuKo7IJRcOfdM2TrnczyXG6E3pnUh0lcdUMB4.y6O', 'user'),
(4, 'Maybelle', 'D', 'Esquivel', 'Gatbo', '09090', 'melvinesquivel21@gmail.com', 'bravo', 'may', '$2y$10$5XGfIdAPsIRnIoVCGChMcun3GmWLq1ux.1R5CV2sJkuY3DNeF9TIe', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alpha_tbl`
--
ALTER TABLE `alpha_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `bravo_tbl`
--
ALTER TABLE `bravo_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `alpha_id` (`alpha_id`),
  ADD KEY `bravo_id` (`bravo_id`),
  ADD KEY `charlie_id` (`charlie_id`);

--
-- Indexes for table `call_logs_tbl`
--
ALTER TABLE `call_logs_tbl`
  ADD PRIMARY KEY (`call_type_id`);

--
-- Indexes for table `call_types`
--
ALTER TABLE `call_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_type_id` (`service_type_id`);

--
-- Indexes for table `call_types_tbl`
--
ALTER TABLE `call_types_tbl`
  ADD KEY `FK_call_logs_tbl_TO_call_types_tbl` (`call_type_id`);

--
-- Indexes for table `charlie_tbl`
--
ALTER TABLE `charlie_tbl`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `service_types_tbl`
--
ALTER TABLE `service_types_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_call_logs_tbl_TO_service_types` (`call_type_id`);

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
-- AUTO_INCREMENT for table `alpha_tbl`
--
ALTER TABLE `alpha_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bravo_tbl`
--
ALTER TABLE `bravo_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `call_types`
--
ALTER TABLE `call_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `charlie_tbl`
--
ALTER TABLE `charlie_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_feedback`
--
ALTER TABLE `client_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_types_tbl`
--
ALTER TABLE `service_types_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alpha_tbl`
--
ALTER TABLE `alpha_tbl`
  ADD CONSTRAINT `alpha_tbl_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`);

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

--
-- Constraints for table `call_types_tbl`
--
ALTER TABLE `call_types_tbl`
  ADD CONSTRAINT `FK_call_logs_tbl_TO_call_types_tbl` FOREIGN KEY (`call_type_id`) REFERENCES `call_logs_tbl` (`call_type_id`);

--
-- Constraints for table `service_types_tbl`
--
ALTER TABLE `service_types_tbl`
  ADD CONSTRAINT `FK_call_logs_tbl_TO_service_types` FOREIGN KEY (`call_type_id`) REFERENCES `call_logs_tbl` (`call_type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

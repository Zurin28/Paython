-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 03:32 PM
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
-- Database: `pms1`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `StudentID` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `MI` varchar(11) NOT NULL,
  `WmsuEmail` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `is_staff` smallint(6) NOT NULL,
  `is_admin` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`StudentID`, `first_name`, `last_name`, `MI`, `WmsuEmail`, `Password`, `role`, `is_staff`, `is_admin`) VALUES
(202300269, 'Jose Miguel', 'Esperat', 'A', 'hz202300269@wmsu.edu.ph', '202300269', 'student', 0, 0),
(202301253, 'Justine Carl', 'Morgia', 'S', 'hz202301253@wmsu.edu.ph', '202301253', 'staff', 1, 0),
(202301283, 'Joel Josh', 'Que', 'G', 'hz2023012832@wmsu.edu.ph', '202301283', 'Admin', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD UNIQUE KEY `StudentID` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

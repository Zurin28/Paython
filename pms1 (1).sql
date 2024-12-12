-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 03:28 PM
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
  `role` enum('student','staff','admin','') NOT NULL,
  `Course` enum('Computer Science','Information Technology','Associate in Computer Technology','Application Development') NOT NULL,
  `Year` enum('1st','2nd','3rd','4th','Over 4 years','') NOT NULL,
  `Section` varchar(255) NOT NULL,
  `isstaff` smallint(6) NOT NULL,
  `isadmin` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`StudentID`, `first_name`, `last_name`, `MI`, `WmsuEmail`, `Password`, `role`, `Course`, `Year`, `Section`, `isstaff`, `isadmin`) VALUES
(123123, 'Novi', 'Diaz', 'A', '123123@gmail.com', '$2y$10$F1EPo1Py3/Ww5Pn6A0Vv8OyCx4aawoM12hruVS46Z06dpvyurY5ki', 'student', 'Computer Science', '1st', 'A', 0, 0),
(201902571, 'Eliezer', 'Villa', 'O', 'gt201902571@wmsu.edu.ph', '$2y$10$ZOljFH.hHEnsNLHOmnRDS.f.pC6DRk40J/Ux3riDYc8NfQgua6pci', 'student', 'Computer Science', 'Over 4 years', 'A', 0, 0),
(202300269, 'Jose Miguel', 'Esperat', 'A', 'hz202300269@wmsu.edu.ph', '$2y$10$CMTZJxqRmIT/u05VwE.dgeA61piU6662vIxxGjETiS5xK6LIRVnKC', 'admin', 'Computer Science', '2nd', 'A', 1, 1),
(202301253, 'Justine Carl', 'Morgia', 'S', 'hz202301253@wmsu.edu.ph', 'staff', 'staff', 'Computer Science', '2nd', '2A', 1, 0),
(202301283, 'Joel josh', 'Que', 'G', 'hz202301283@wmsu.edu.ph', 'admin', 'admin', 'Computer Science', '2nd', '2A', 1, 1),
(202400269, 'adwad', 'afasf', 'a', 'hz202400269@wmsu.edu.ph', '$2y$10$i/7JD8rIH.CXebi9qTMXee2j8tAB4/tfk9m5rbXcndcwHfRttpQ5O', 'staff', 'Computer Science', '1st', 'a', 1, 0),
(202500269, 'adwadaw', 'dawdawd', 'a', 'hz202500269@wmsu.edu.ph', '$2y$10$YsLkKv4PqzWY.JgkOeIHFOl8UAjIgfM7V0e1QiixoOFJIA0MzsMJi', 'student', 'Associate in Computer Technology', '4th', 'a', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `FeeID` int(11) NOT NULL,
  `OrgID` varchar(100) NOT NULL,
  `FeeName` varchar(255) NOT NULL,
  `Amount` int(6) NOT NULL,
  `DueDate` date NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`FeeID`, `OrgID`, `FeeName`, `Amount`, `DueDate`, `Description`) VALUES
(12312, 'CSC\r\n', 'Palaro Fee', 500, '2024-12-04', 'adadsawdawwasdw'),
(12313, 'VENOM', 'VENOM FEE', 200, '2025-01-31', 'This is for the venom fee');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `OrganizationID` varchar(100) NOT NULL,
  `OrgName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`OrganizationID`, `OrgName`) VALUES
('CSC\r\n', 'CCS College Student Council'),
('VENOM', 'Venom Publication');

-- --------------------------------------------------------

--

--

--

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `StudentID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `FeeName` varchar(255) NOT NULL,
  `PaymentType` varchar(100) NOT NULL,
  `DatePaid` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees`
--

CREATE TABLE `student_fees` (
  `studentID` int(11) NOT NULL,
  `feeID` int(11) NOT NULL,
  `paymentStatus` enum('Paid','Not Paid','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_fees`
--

INSERT INTO `student_fees` (`studentID`, `feeID`, `paymentStatus`) VALUES
(201902571, 12312, 'Not Paid'),
(201902571, 12313, 'Not Paid'),
(202400269, 12312, 'Paid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`FeeID`),
  ADD KEY `Org_id_fk` (`OrgID`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`OrganizationID`),
  ADD UNIQUE KEY `idx_org_id` (`OrganizationID`),
  ADD UNIQUE KEY `idx_org_name` (`OrgName`);

--
-- Indexes for table `pms1_venom_publication`
--
ALTER TABLE `pms1_venom_publication`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student` (`OrganizationID`,`StudentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD KEY `stud_id_fk` (`StudentID`);

--
-- Indexes for table `student_fees`
--
ALTER TABLE `student_fees`
  ADD PRIMARY KEY (`studentID`,`feeID`),
  ADD KEY `fk_fee` (`feeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pms1_venom_publication`
--
ALTER TABLE `pms1_venom_publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `orgID_fk` FOREIGN KEY (`OrgID`) REFERENCES `organizations` (`OrganizationID`);

--
-- Constraints for table `pms1_venom_publication`
--
ALTER TABLE `pms1_venom_publication`
  ADD CONSTRAINT `pms1_venom_publication_ibfk_1` FOREIGN KEY (`OrganizationID`) REFERENCES `organizations` (`OrganizationID`) ON DELETE CASCADE,
  ADD CONSTRAINT `pms1_venom_publication_ibfk_2` FOREIGN KEY (`StudentID`) REFERENCES `account` (`StudentID`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `studID_fk` FOREIGN KEY (`StudentID`) REFERENCES `account` (`StudentID`);

--
-- Constraints for table `student_fees`
--
ALTER TABLE `student_fees`
  ADD CONSTRAINT `fk_fee` FOREIGN KEY (`feeID`) REFERENCES `fees` (`FeeID`),
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`studentID`) REFERENCES `account` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS student_fees;
DROP TABLE IF EXISTS staff_activity;
DROP TABLE IF EXISTS fee_creation_requests;
DROP TABLE IF EXISTS staff_login_history;
DROP TABLE IF EXISTS fees;
DROP TABLE IF EXISTS organizations;
DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS academic_periods;

-- Create tables in order of dependencies
CREATE TABLE `academic_periods` (
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `is_current` BOOLEAN DEFAULT FALSE,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  PRIMARY KEY (`school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert one academic period
INSERT INTO academic_periods (school_year, semester, is_current, start_date, end_date) VALUES
('2023-2024', '2nd', TRUE, '2024-01-01', '2024-05-31');

CREATE TABLE `organizations` (
  `OrganizationID` varchar(100) NOT NULL,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `OrgName` varchar(255) NOT NULL,
  PRIMARY KEY (`OrganizationID`, `school_year`, `semester`),
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert one organization
INSERT INTO organizations (OrganizationID, school_year, semester, OrgName) VALUES
('CSC', '2023-2024', '2nd', 'CCS College Student Council');

CREATE TABLE `account` (
  `StudentID` int(11) NOT NULL,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
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
  `isadmin` smallint(6) NOT NULL,
  PRIMARY KEY (`StudentID`, `school_year`, `semester`),
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert one account
INSERT INTO account (StudentID, school_year, semester, first_name, last_name, MI, WmsuEmail, Password, role, Course, Year, Section, isstaff, isadmin) VALUES
(202300269, '2023-2024', '2nd', 'Jose Miguel', 'Esperat', 'A', 'hz202300269@wmsu.edu.ph', '$2y$10$CMTZJxqRmIT/u05VwE.dgeA61piU6662vIxxGjETiS5xK6LIRVnKC', 'admin', 'Computer Science', '2nd', 'A', 1, 1);

CREATE TABLE `fees` (
  `FeeID` int(11) NOT NULL,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `OrgID` varchar(100) NOT NULL,
  `FeeName` varchar(255) NOT NULL,
  `Amount` int(6) NOT NULL,
  `DueDate` date NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`FeeID`, `school_year`, `semester`),
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`),
  FOREIGN KEY (`OrgID`, `school_year`, `semester`) REFERENCES `organizations`(`OrganizationID`, `school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert one fee
INSERT INTO fees (FeeID, school_year, semester, OrgID, FeeName, Amount, DueDate, Description) VALUES
(12312, '2023-2024', '2nd', 'CSC', 'Palaro Fee', 500, '2024-12-04', 'adadsawdawwasdw');

CREATE TABLE `student_fees` (
  `studentID` int(11) NOT NULL,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `feeID` int(11) NOT NULL,
  `paymentStatus` enum('Paid','Not Paid','Pending') NOT NULL,
  PRIMARY KEY (`studentID`, `feeID`, `school_year`, `semester`),
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`),
  FOREIGN KEY (`studentID`, `school_year`, `semester`) REFERENCES `account`(`StudentID`, `school_year`, `semester`),
  FOREIGN KEY (`feeID`, `school_year`, `semester`) REFERENCES `fees`(`FeeID`, `school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert one student_fee record
INSERT INTO student_fees (studentID, school_year, semester, feeID, paymentStatus) VALUES
(202300269, '2023-2024', '2nd', 12312, 'Paid');

-- Create monitoring tables (these don't need test data yet)
CREATE TABLE `staff_login_history` (
  `login_id` INT AUTO_INCREMENT PRIMARY KEY,
  `StudentID` int(11) NOT NULL,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `organization` varchar(100) NOT NULL,
  `login_datetime` DATETIME NOT NULL,
  `logout_datetime` DATETIME,
  FOREIGN KEY (`StudentID`, `school_year`, `semester`) REFERENCES `account`(`StudentID`, `school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `fee_creation_requests` (
  `request_id` INT AUTO_INCREMENT PRIMARY KEY,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `organization` varchar(100) NOT NULL,
  `fee_id` varchar(100) NOT NULL,
  `fee_name` varchar(255) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `description` TEXT NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `submit_date` DATETIME NOT NULL,
  `status` ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `staff_activity` (
  `activity_id` INT AUTO_INCREMENT PRIMARY KEY,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `organization` varchar(100) NOT NULL,
  `fee_id` varchar(100) NOT NULL,
  `fee_name` varchar(255) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `status` ENUM('Accepted', 'Rejected') NOT NULL,
  `reason` TEXT,
  `action_date` DATETIME NOT NULL,
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`),
  FOREIGN KEY (`student_id`, `school_year`, `semester`) REFERENCES `account`(`StudentID`, `school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payment_requests` (
  `RequestID` int(11) NOT NULL AUTO_INCREMENT,
  `school_year` VARCHAR(9) NOT NULL,
  `semester` ENUM('1st', '2nd', 'Summer') NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `FeeName` varchar(255) NOT NULL,
  `PaymentType` varchar(100) NOT NULL,
  `DatePaid` date NOT NULL,
  `Status` enum('Pending','Accepted','Rejected') NOT NULL DEFAULT 'Pending',
  `ProofOfPayment` varchar(255) NOT NULL,
  PRIMARY KEY (`RequestID`),
  FOREIGN KEY (`school_year`, `semester`) REFERENCES `academic_periods`(`school_year`, `semester`),
  FOREIGN KEY (`StudentID`, `school_year`, `semester`) REFERENCES `account`(`StudentID`, `school_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

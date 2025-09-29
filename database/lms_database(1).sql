-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 12:08 PM
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
-- Database: `lms_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `session_status` varchar(20) DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `RollNo`, `checkin_time`, `checkout_time`, `attendance_date`, `session_status`, `created_at`) VALUES
(1, '2021001', '2025-09-27 09:15:00', '2025-09-27 16:30:00', '2025-09-27', 'closed', '2025-09-27 03:29:28'),
(2, '2021002', '2025-09-27 10:00:00', '2025-09-27 15:45:00', '2025-09-27', 'closed', '2025-09-27 03:29:28'),
(3, '2021003', '2025-09-27 08:30:00', '2025-09-27 17:00:00', '2025-09-27', 'closed', '2025-09-27 03:29:28'),
(4, '2021004', '2025-09-27 11:15:00', NULL, '2025-09-27', 'open', '2025-09-27 03:29:28'),
(5, '2021005', '2025-09-27 14:20:00', '2025-09-27 18:45:00', '2025-09-27', 'closed', '2025-09-27 03:29:28'),
(6, '2021001', '2025-09-26 09:30:00', '2025-09-26 14:20:00', '2025-09-26', 'closed', '2025-09-27 03:29:28'),
(7, '2021006', '2025-09-26 10:15:00', '2025-09-26 16:30:00', '2025-09-26', 'closed', '2025-09-27 03:29:28'),
(8, '2021007', '2025-09-26 13:00:00', '2025-09-26 17:45:00', '2025-09-26', 'closed', '2025-09-27 03:29:28'),
(9, '2021002', '2025-09-26 08:45:00', '2025-09-26 15:20:00', '2025-09-26', 'closed', '2025-09-27 03:29:28'),
(10, '2021008', '2025-09-25 10:30:00', '2025-09-25 16:15:00', '2025-09-25', 'closed', '2025-09-27 03:29:28'),
(11, '2021009', '2025-09-25 09:00:00', '2025-09-25 14:30:00', '2025-09-25', 'closed', '2025-09-27 03:29:28'),
(12, '2021010', '2025-09-25 11:45:00', '2025-09-25 17:20:00', '2025-09-25', 'closed', '2025-09-27 03:29:28'),
(13, '2021003', '2025-09-25 12:00:00', '2025-09-25 18:00:00', '2025-09-25', 'closed', '2025-09-27 03:29:28'),
(14, '2021008', '2025-09-27 20:33:58', NULL, '2025-09-27', 'open', '2025-09-27 18:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `BookId` int(11) NOT NULL,
  `Title` varchar(200) DEFAULT NULL,
  `Author` varchar(200) DEFAULT NULL,
  `Publisher` varchar(200) DEFAULT NULL,
  `Year` varchar(10) DEFAULT NULL,
  `Availability` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`BookId`, `Title`, `Author`, `Publisher`, `Year`, `Availability`) VALUES
(1, 'Operating Systems Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 10),
(2, 'Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 3),
(3, 'Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 4),
(4, 'Data Structures and Algorithms', 'Michael T. Goodrich', 'John Wiley & Sons', '2014', 10),
(5, 'Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 8),
(6, 'Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 6),
(7, 'Artificial Intelligence: A Modern Approach', 'Stuart Russell', 'Pearson', '2020', 7),
(8, 'Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 8),
(9, 'Operating System Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 6),
(10, 'Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 7),
(11, 'Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 5),
(12, 'Data Structures and Algorithms in Java', 'Robert Lafore', 'Sams Publishing', '2017', 4),
(13, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 6),
(14, 'Python Programming', 'Mark Lutz', 'O\'Reilly Media', '2019', 8),
(15, 'JavaScript: The Good Parts', 'Douglas Crockford', 'Yahoo Press', '2008', 5),
(16, 'Java: The Complete Reference', 'Herbert Schildt', 'McGraw-Hill', '2020', 7),
(17, 'C++ Programming Language', 'Bjarne Stroustrup', 'Addison-Wesley', '2013', 4),
(18, 'HTML and CSS', 'Jon Duckett', 'Wiley', '2014', 9),
(19, 'React: Up & Running', 'Stoyan Stefanov', 'O\'Reilly', '2016', 6),
(20, 'Node.js in Action', 'Mike Cantelon', 'Manning', '2017', 5),
(21, 'Vue.js Guide', 'Evan You', 'Vue Press', '2019', 4),
(22, 'Machine Learning', 'Tom Mitchell', 'McGraw-Hill', '1997', 3),
(23, 'Artificial Intelligence', 'Stuart Russell', 'Prentice Hall', '2016', 5),
(25, 'Deep Learning', 'Ian Goodfellow', 'MIT Press', '2016', 3),
(26, 'Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 6),
(27, 'Design Patterns', 'Gang of Four', 'Addison-Wesley', '1994', 4),
(28, 'Refactoring', 'Martin Fowler', 'Addison-Wesley', '1999', 5),
(29, 'The Pragmatic Programmer', 'Andrew Hunt', 'Addison-Wesley', '2019', 7),
(30, 'Discrete Mathematics', 'Kenneth Rosen', 'McGraw-Hill', '2018', 8),
(31, 'Linear Algebra', 'Gilbert Strang', 'Wellesley-Cambridge', '2016', 6),
(32, 'Computer Architecture', 'David Patterson', 'Morgan Kaufmann', '2017', 4),
(33, 'Compiler Design', 'Alfred Aho', 'Pearson', '2006', 3),
(34, 'life dairy', 'Timo', 'Modern Technology', '2025', 40);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `ID` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `Msg_Date` date DEFAULT NULL,
  `Msg_Time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`ID`, `RollNo`, `Message`, `Msg_Date`, `Msg_Time`) VALUES
(1, '2021001', 'Welcome to the Library Management System! Please return books on time to avoid fines.', '2025-09-15', '12:00:00'),
(2, '2021002', 'Your book \"Operating System Concepts\" is due tomorrow. Please renew or return it.', '2025-09-19', '12:00:00'),
(3, '2021003', 'Thank you for returning the book on time! Your account is in good standing.', '2025-09-18', '12:00:00'),
(4, '2021004', 'New books have arrived in the Computer Science section. Check them out!', '2025-09-25', '12:00:00'),
(5, '2021005', 'Library timing has been updated: Now open 8 AM to 8 PM on weekdays.', '2025-09-20', '12:00:00'),
(6, '2021006', 'Reminder: Library will be closed on October 2nd for Gandhi Jayanti.', '2025-09-28', '12:00:00'),
(7, '2021007', 'Your requested book \"Clean Code\" is now available for pickup.', '2025-09-22', '12:00:00'),
(8, '2021008', 'Late return fee of Rs. 10 has been waived as a first-time courtesy.', '2025-09-24', '12:00:00'),
(9, '2021009', 'Study room booking is now available online. Book your slot today!', '2025-09-26', '12:00:00'),
(10, '2021010', 'Congratulations on completing your reading challenge! Keep it up.', '2025-09-27', '12:00:00'),
(11, '2021002', 'hi', '2025-09-27', '12:00:00'),
(12, '2021001', 'Your request for renewal of BookId: 1 has been accepted. Extended by 60 days.', '2025-09-27', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `ID` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `Book_Name` varchar(200) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`ID`, `RollNo`, `Book_Name`, `Description`, `created_at`) VALUES
(1, '2021001', 'Artificial Intelligence: A Modern Approach', 'Comprehensive guide to AI with practical examples and case studies.', '2025-09-27 03:29:28'),
(2, '2021002', 'Spring Boot in Action', 'Excellent resource for learning Spring framework with hands-on projects.', '2025-09-27 03:29:28'),
(3, '2021003', 'React: Up & Running', 'Best book for learning React.js from basics to advanced concepts.', '2025-09-27 03:29:28'),
(4, '2021004', 'Docker Deep Dive', 'Essential reading for understanding containerization and DevOps practices.', '2025-09-27 03:29:28'),
(5, '2021005', 'Blockchain Revolution', 'Insightful book about blockchain technology and its future applications.', '2025-09-27 03:29:28'),
(6, '2021006', 'Kubernetes in Action', 'Perfect guide for container orchestration and cloud-native development.', '2025-09-27 03:29:28'),
(7, '2021007', 'GraphQL in Action', 'Modern approach to API development with GraphQL and best practices.', '2025-09-27 03:29:28'),
(8, '2021008', 'Microservices Patterns', 'Architectural patterns for building scalable distributed systems.', '2025-09-27 03:29:28'),
(9, '2021009', 'Cloud Native Patterns', 'Design patterns for building resilient cloud applications.', '2025-09-27 03:29:28'),
(10, '2021010', 'DevOps Handbook', 'Complete guide to DevOps practices and continuous delivery.', '2025-09-27 03:29:28'),
(11, '2021008', 'life dairy', 'life book', '2025-09-27 17:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `id` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `BookId` int(11) DEFAULT NULL,
  `IssueDate` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Issued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`id`, `RollNo`, `BookId`, `IssueDate`, `ReturnDate`, `Status`) VALUES
(1, '2021001', 1, '2025-09-27', '2025-10-15', 'Issued'),
(2, '2021002', 2, '2025-09-20', '2025-10-20', 'Issued'),
(3, '2021003', 3, '2025-09-10', '2025-10-10', 'Returned'),
(4, '2021004', 4, '2025-09-25', '2025-10-25', 'Issued'),
(5, '2021005', 5, '2025-09-18', '2025-10-18', 'Issued'),
(6, '2021006', 6, '2025-09-12', '2025-10-12', 'Returned'),
(7, '2021007', 7, '2025-09-22', '2025-10-22', 'Issued'),
(8, '2021008', 8, '2025-09-14', '2025-10-14', 'Issued'),
(9, '2021009', 9, '2025-09-16', '2025-10-16', 'Returned'),
(10, '2021010', 10, '2025-09-26', '2025-10-26', 'Issued'),
(11, '2021001', 1, '2025-09-27', NULL, 'Issued');

-- --------------------------------------------------------

--
-- Table structure for table `renew`
--

CREATE TABLE `renew` (
  `id` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `BookId` int(11) DEFAULT NULL,
  `IssueDate` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Requested'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `renew`
--

INSERT INTO `renew` (`id`, `RollNo`, `BookId`, `IssueDate`, `ReturnDate`, `Status`) VALUES
(2, '2021002', 2, '2025-09-20', '2025-11-20', 'Approved'),
(3, '2021004', 4, '2025-09-25', '2025-11-25', 'Requested'),
(4, '2021005', 5, '2025-09-18', '2025-11-18', 'Approved'),
(5, '2021007', 7, '2025-09-22', '2025-11-22', 'Requested');

-- --------------------------------------------------------

--
-- Table structure for table `return_req`
--

CREATE TABLE `return_req` (
  `id` int(11) NOT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  `BookId` int(11) DEFAULT NULL,
  `requested_date` date DEFAULT curdate(),
  `Status` varchar(20) DEFAULT 'Requested'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `return_req`
--

INSERT INTO `return_req` (`id`, `RollNo`, `BookId`, `requested_date`, `Status`) VALUES
(2, '2021006', 6, '2025-09-26', 'Approved'),
(4, '2021008', 8, '2025-09-24', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `RollNo` varchar(50) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `MobNo` varchar(20) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'images/user.png',
  `ProfilePic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`RollNo`, `Name`, `Type`, `Category`, `EmailId`, `MobNo`, `Password`, `profile_picture`, `ProfilePic`) VALUES
('2021001', 'John Smith', 'Student', 'GEN', 'john.smith@student.edu', '9876543210', 'student123', 'images/user.png', NULL),
('2021002', 'Sarah Johnson', 'Student', 'OBC', 'sarah.j@student.edu', '8765432109', 'student456', 'images/user.png', NULL),
('2021003', 'Mike Chen', 'Student', 'GEN', 'mike.chen@student.edu', '7654321098', 'student789', 'images/user.png', NULL),
('2021004', 'Priya Patel', 'Student', 'SC', 'priya.p@student.edu', '6543210987', 'student321', 'images/user.png', NULL),
('2021005', 'Ahmed Ali', 'Student', 'GEN', 'ahmed.ali@student.edu', '5432109876', 'student555', 'images/user.png', NULL),
('2021006', 'Emma Wilson', 'Student', 'OBC', 'emma.w@student.edu', '4321098765', 'student666', 'images/user.png', NULL),
('2021007', 'David Brown', 'Student', 'GEN', 'david.b@student.edu', '3210987654', 'student777', 'images/user.png', NULL),
('2021008', 'Lisa Garcia', 'Student', 'ST', 'lisa.g@student.edu', '2109876543', 'student888', 'images/user.png', 'uploads/profiles/student_2021008_1758998585.jpg'),
('2021009', 'Ryan Kumar', 'Student', 'GEN', 'ryan.k@student.edu', '1098765432', 'student999', 'images/user.png', NULL),
('2021010', 'Anna Lee', 'Student', 'SC', 'anna.l@student.edu', '9087654321', 'student000', 'images/user.png', NULL),
('ADMIN', 'Administrator', 'Admin', NULL, 'admin@library.com', '0548859296', 'admin123', 'uploads/profile_ADMIN_1758968586.jpg', NULL),
('LIB001', 'Library Assistant', 'Admin', 'GEN', 'assistant@library.com', '5432109876', 'libassist123', 'images/user.png', NULL),
('LIB002', 'Senior Librarian', 'Admin', 'GEN', 'senior@library.com', '6543210987', 'senior123', 'images/user.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RollNo` (`RollNo`),
  ADD KEY `attendance_date` (`attendance_date`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RollNo` (`RollNo`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RollNo` (`RollNo`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RollNo` (`RollNo`),
  ADD KEY `BookId` (`BookId`);

--
-- Indexes for table `renew`
--
ALTER TABLE `renew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RollNo` (`RollNo`),
  ADD KEY `BookId` (`BookId`);

--
-- Indexes for table `return_req`
--
ALTER TABLE `return_req`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RollNo` (`RollNo`),
  ADD KEY `BookId` (`BookId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`RollNo`),
  ADD UNIQUE KEY `EmailId` (`EmailId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `BookId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `renew`
--
ALTER TABLE `renew`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `return_req`
--
ALTER TABLE `return_req`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE;

--
-- Constraints for table `record`
--
ALTER TABLE `record`
  ADD CONSTRAINT `record_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `record_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`) ON DELETE CASCADE;

--
-- Constraints for table `renew`
--
ALTER TABLE `renew`
  ADD CONSTRAINT `renew_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `renew_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`) ON DELETE CASCADE;

--
-- Constraints for table `return_req`
--
ALTER TABLE `return_req`
  ADD CONSTRAINT `return_req_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_req_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

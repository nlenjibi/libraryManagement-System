-- =====================================================
-- MANUAL SQL INSERT STATEMENTS FOR LIBRARY MANAGEMENT SYSTEM
-- Run these commands in your MySQL database (lms_database)
-- =====================================================

-- First, let's clear existing sample data (keep ADMIN)
DELETE FROM attendance WHERE RollNo != 'ADMIN';
DELETE FROM return_req WHERE RollNo != 'ADMIN';
DELETE FROM renew WHERE RollNo != 'ADMIN';
DELETE FROM recommendations WHERE RollNo != 'ADMIN';
DELETE FROM message WHERE RollNo != 'ADMIN';
DELETE FROM record WHERE RollNo != 'ADMIN';
DELETE FROM user WHERE RollNo != 'ADMIN';

-- =====================================================
-- 1. INSERT USERS
-- =====================================================
INSERT INTO user (RollNo, Name, Type, Category, EmailId, MobNo, Password) VALUES
('2021001', 'John Smith', 'Student', 'GEN', 'john.smith@student.edu', '9876543210', 'student123'),
('2021002', 'Sarah Johnson', 'Student', 'OBC', 'sarah.j@student.edu', '8765432109', 'student456'),
('2021003', 'Mike Chen', 'Student', 'GEN', 'mike.chen@student.edu', '7654321098', 'student789'),
('2021004', 'Priya Patel', 'Student', 'SC', 'priya.p@student.edu', '6543210987', 'student321'),
('2021005', 'Ahmed Ali', 'Student', 'GEN', 'ahmed.ali@student.edu', '5432109876', 'student555'),
('2021006', 'Emma Wilson', 'Student', 'OBC', 'emma.w@student.edu', '4321098765', 'student666'),
('2021007', 'David Brown', 'Student', 'GEN', 'david.b@student.edu', '3210987654', 'student777'),
('2021008', 'Lisa Garcia', 'Student', 'ST', 'lisa.g@student.edu', '2109876543', 'student888'),
('2021009', 'Ryan Kumar', 'Student', 'GEN', 'ryan.k@student.edu', '1098765432', 'student999'),
('2021010', 'Anna Lee', 'Student', 'SC', 'anna.l@student.edu', '9087654321', 'student000'),
('LIB001', 'Library Assistant', 'Admin', 'GEN', 'assistant@library.com', '5432109876', 'libassist123'),
('LIB002', 'Senior Librarian', 'Admin', 'GEN', 'senior@library.com', '6543210987', 'senior123');

-- =====================================================
-- 2. INSERT BOOKS
-- =====================================================
INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES
('Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 8),
('Operating System Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 6),
('Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 7),
('Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 5),
('Data Structures and Algorithms in Java', 'Robert Lafore', 'Sams Publishing', '2017', 4),
('Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 6),
('Python Programming', 'Mark Lutz', 'O\'Reilly Media', '2019', 8),
('JavaScript: The Good Parts', 'Douglas Crockford', 'Yahoo Press', '2008', 5),
('Java: The Complete Reference', 'Herbert Schildt', 'McGraw-Hill', '2020', 7),
('C++ Programming Language', 'Bjarne Stroustrup', 'Addison-Wesley', '2013', 4),
('HTML and CSS', 'Jon Duckett', 'Wiley', '2014', 9),
('React: Up & Running', 'Stoyan Stefanov', 'O\'Reilly', '2016', 6),
('Node.js in Action', 'Mike Cantelon', 'Manning', '2017', 5),
('Vue.js Guide', 'Evan You', 'Vue Press', '2019', 4),
('Machine Learning', 'Tom Mitchell', 'McGraw-Hill', '1997', 3),
('Artificial Intelligence', 'Stuart Russell', 'Prentice Hall', '2016', 5),
('Data Science from Scratch', 'Joel Grus', 'O\'Reilly', '2019', 4),
('Deep Learning', 'Ian Goodfellow', 'MIT Press', '2016', 3),
('Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 6),
('Design Patterns', 'Gang of Four', 'Addison-Wesley', '1994', 4),
('Refactoring', 'Martin Fowler', 'Addison-Wesley', '1999', 5),
('The Pragmatic Programmer', 'Andrew Hunt', 'Addison-Wesley', '2019', 7),
('Discrete Mathematics', 'Kenneth Rosen', 'McGraw-Hill', '2018', 8),
('Linear Algebra', 'Gilbert Strang', 'Wellesley-Cambridge', '2016', 6),
('Computer Architecture', 'David Patterson', 'Morgan Kaufmann', '2017', 4),
('Compiler Design', 'Alfred Aho', 'Pearson', '2006', 3);

-- =====================================================
-- 3. INSERT ISSUE RECORDS
-- =====================================================
INSERT INTO record (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES
('2021001', 1, '2025-09-15', '2025-10-15', 'Issued'),
('2021002', 2, '2025-09-20', '2025-10-20', 'Issued'),
('2021003', 3, '2025-09-10', '2025-10-10', 'Returned'),
('2021004', 4, '2025-09-25', '2025-10-25', 'Issued'),
('2021005', 5, '2025-09-18', '2025-10-18', 'Issued'),
('2021006', 6, '2025-09-12', '2025-10-12', 'Returned'),
('2021007', 7, '2025-09-22', '2025-10-22', 'Issued'),
('2021008', 8, '2025-09-14', '2025-10-14', 'Issued'),
('2021009', 9, '2025-09-16', '2025-10-16', 'Returned'),
('2021010', 10, '2025-09-26', '2025-10-26', 'Issued');

-- =====================================================
-- 4. INSERT MESSAGES
-- =====================================================
INSERT INTO message (RollNo, Message, Msg_Date) VALUES
('2021001', 'Welcome to the Library Management System! Please return books on time to avoid fines.', '2025-09-15'),
('2021002', 'Your book "Operating System Concepts" is due tomorrow. Please renew or return it.', '2025-09-19'),
('2021003', 'Thank you for returning the book on time! Your account is in good standing.', '2025-09-18'),
('2021004', 'New books have arrived in the Computer Science section. Check them out!', '2025-09-25'),
('2021005', 'Library timing has been updated: Now open 8 AM to 8 PM on weekdays.', '2025-09-20'),
('2021006', 'Reminder: Library will be closed on October 2nd for Gandhi Jayanti.', '2025-09-28'),
('2021007', 'Your requested book "Clean Code" is now available for pickup.', '2025-09-22'),
('2021008', 'Late return fee of Rs. 10 has been waived as a first-time courtesy.', '2025-09-24'),
('2021009', 'Study room booking is now available online. Book your slot today!', '2025-09-26'),
('2021010', 'Congratulations on completing your reading challenge! Keep it up.', '2025-09-27');

-- =====================================================
-- 5. INSERT RECOMMENDATIONS
-- =====================================================
INSERT INTO recommendations (RollNo, Book_Name, Description) VALUES
('2021001', 'Artificial Intelligence: A Modern Approach', 'Comprehensive guide to AI with practical examples and case studies.'),
('2021002', 'Spring Boot in Action', 'Excellent resource for learning Spring framework with hands-on projects.'),
('2021003', 'React: Up & Running', 'Best book for learning React.js from basics to advanced concepts.'),
('2021004', 'Docker Deep Dive', 'Essential reading for understanding containerization and DevOps practices.'),
('2021005', 'Blockchain Revolution', 'Insightful book about blockchain technology and its future applications.'),
('2021006', 'Kubernetes in Action', 'Perfect guide for container orchestration and cloud-native development.'),
('2021007', 'GraphQL in Action', 'Modern approach to API development with GraphQL and best practices.'),
('2021008', 'Microservices Patterns', 'Architectural patterns for building scalable distributed systems.'),
('2021009', 'Cloud Native Patterns', 'Design patterns for building resilient cloud applications.'),
('2021010', 'DevOps Handbook', 'Complete guide to DevOps practices and continuous delivery.');

-- =====================================================
-- 6. INSERT RENEWAL REQUESTS
-- =====================================================
INSERT INTO renew (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES
('2021001', 1, '2025-09-15', '2025-11-15', 'Requested'),
('2021002', 2, '2025-09-20', '2025-11-20', 'Approved'),
('2021004', 4, '2025-09-25', '2025-11-25', 'Requested'),
('2021005', 5, '2025-09-18', '2025-11-18', 'Approved'),
('2021007', 7, '2025-09-22', '2025-11-22', 'Requested');

-- =====================================================
-- 7. INSERT RETURN REQUESTS
-- =====================================================
INSERT INTO return_req (RollNo, BookId, requested_date, Status) VALUES
('2021003', 3, '2025-09-25', 'Requested'),
('2021006', 6, '2025-09-26', 'Approved'),
('2021009', 9, '2025-09-27', 'Requested'),
('2021008', 8, '2025-09-24', 'Approved');

-- =====================================================
-- 8. INSERT ATTENDANCE RECORDS
-- =====================================================
INSERT INTO attendance (RollNo, checkin_time, checkout_time, attendance_date, session_status) VALUES
-- Today's attendance (September 27, 2025)
('2021001', '2025-09-27 09:15:00', '2025-09-27 16:30:00', '2025-09-27', 'closed'),
('2021002', '2025-09-27 10:00:00', '2025-09-27 15:45:00', '2025-09-27', 'closed'),
('2021003', '2025-09-27 08:30:00', '2025-09-27 17:00:00', '2025-09-27', 'closed'),
('2021004', '2025-09-27 11:15:00', NULL, '2025-09-27', 'open'),
('2021005', '2025-09-27 14:20:00', '2025-09-27 18:45:00', '2025-09-27', 'closed'),

-- Yesterday's attendance (September 26, 2025)
('2021001', '2025-09-26 09:30:00', '2025-09-26 14:20:00', '2025-09-26', 'closed'),
('2021006', '2025-09-26 10:15:00', '2025-09-26 16:30:00', '2025-09-26', 'closed'),
('2021007', '2025-09-26 13:00:00', '2025-09-26 17:45:00', '2025-09-26', 'closed'),
('2021002', '2025-09-26 08:45:00', '2025-09-26 15:20:00', '2025-09-26', 'closed'),

-- Day before yesterday (September 25, 2025)
('2021008', '2025-09-25 10:30:00', '2025-09-25 16:15:00', '2025-09-25', 'closed'),
('2021009', '2025-09-25 09:00:00', '2025-09-25 14:30:00', '2025-09-25', 'closed'),
('2021010', '2025-09-25 11:45:00', '2025-09-25 17:20:00', '2025-09-25', 'closed'),
('2021003', '2025-09-25 12:00:00', '2025-09-25 18:00:00', '2025-09-25', 'closed');

-- =====================================================
-- VERIFICATION QUERIES (Run these to check your data)
-- =====================================================

-- Check total counts in each table
SELECT 'user' as table_name, COUNT(*) as total_rows FROM user
UNION ALL
SELECT 'book' as table_name, COUNT(*) as total_rows FROM book
UNION ALL
SELECT 'record' as table_name, COUNT(*) as total_rows FROM record
UNION ALL
SELECT 'message' as table_name, COUNT(*) as total_rows FROM message
UNION ALL
SELECT 'recommendations' as table_name, COUNT(*) as total_rows FROM recommendations
UNION ALL
SELECT 'renew' as table_name, COUNT(*) as total_rows FROM renew
UNION ALL
SELECT 'return_req' as table_name, COUNT(*) as total_rows FROM return_req
UNION ALL
SELECT 'attendance' as table_name, COUNT(*) as total_rows FROM attendance;

-- Check if all users were inserted
SELECT RollNo, Name, Type FROM user ORDER BY RollNo;

-- Check if books were inserted
SELECT BookId, Title, Author, Availability FROM book ORDER BY BookId LIMIT 10;

-- Check recent attendance
SELECT RollNo, checkin_time, checkout_time, session_status 
FROM attendance 
WHERE attendance_date = '2025-09-27' 
ORDER BY checkin_time;

-- =====================================================
-- LOGIN CREDENTIALS FOR TESTING
-- =====================================================
/*
ADMIN USERS:
- ADMIN / admin123 (Main Administrator)
- LIB001 / libassist123 (Library Assistant)
- LIB002 / senior123 (Senior Librarian)

STUDENT USERS:
- 2021001 / student123 (John Smith)
- 2021002 / student456 (Sarah Johnson)
- 2021003 / student789 (Mike Chen)
- 2021004 / student321 (Priya Patel)
- 2021005 / student555 (Ahmed Ali)
- 2021006 / student666 (Emma Wilson)
- 2021007 / student777 (David Brown)
- 2021008 / student888 (Lisa Garcia)
- 2021009 / student999 (Ryan Kumar)
- 2021010 / student000 (Anna Lee)
*/
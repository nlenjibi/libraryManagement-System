<?php
// Force insert sample data script
echo "<h2>Force Insert Sample Data</h2>";
echo "<hr>";

// Database configuration
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'lms_database';
$db_user = 'root';
$db_pass = '';

try {
    // Connect to database
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p><strong>Forcing sample data insertion...</strong></p>";

    // Clear existing data first (except admin)
    echo "<p>Clearing old data...</p>";
    $conn->exec("DELETE FROM attendance WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM return_req WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM renew WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM recommendations WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM message WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM record WHERE RollNo != 'ADMIN'");
    $conn->exec("DELETE FROM user WHERE RollNo != 'ADMIN'");

    // Reset book availability
    $conn->exec("UPDATE book SET Availability = 5 WHERE Availability < 5");

    echo "<p style='color: green;'>✓ Cleared old data</p>";

    // Insert sample users
    echo "<p><strong>Adding sample users...</strong></p>";
    $users = [
        ['2021001', 'John Smith', 'Student', 'GEN', 'john.smith@student.edu', '9876543210', 'student123'],
        ['2021002', 'Sarah Johnson', 'Student', 'OBC', 'sarah.j@student.edu', '8765432109', 'student456'],
        ['2021003', 'Mike Chen', 'Student', 'GEN', 'mike.chen@student.edu', '7654321098', 'student789'],
        ['2021004', 'Priya Patel', 'Student', 'SC', 'priya.p@student.edu', '6543210987', 'student321'],
        ['LIB001', 'Library Assistant', 'Admin', 'GEN', 'assistant@library.com', '5432109876', 'libassist123']
    ];

    $sql = "INSERT INTO user (RollNo, Name, Type, Category, EmailId, MobNo, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $users_added = 0;
    foreach ($users as $user) {
        try {
            $stmt->execute($user);
            $users_added++;
        } catch (PDOException $e) {
            // User might already exist, skip
        }
    }
    echo "<p style='color: green;'>✓ Added $users_added users</p>";

    // Insert sample books (ensure we have books)
    echo "<p><strong>Ensuring sample books exist...</strong></p>";
    $stmt = $conn->query("SELECT COUNT(*) FROM book");
    $book_count = $stmt->fetchColumn();

    if ($book_count < 10) {
        $books = [
            ['Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 5],
            ['Operating System Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 8],
            ['Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 4],
            ['Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 6],
            ['Data Structures in Java', 'Robert Lafore', 'Sams Publishing', '2017', 7],
            ['Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 5],
            ['Python Programming', 'Mark Lutz', 'O\'Reilly Media', '2019', 6],
            ['Web Development HTML CSS', 'Jon Duckett', 'Wiley', '2014', 8],
            ['JavaScript Good Parts', 'Douglas Crockford', 'Yahoo Press', '2008', 4],
            ['Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 5]
        ];

        $sql = "INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $books_added = 0;
        foreach ($books as $book) {
            try {
                $stmt->execute($book);
                $books_added++;
            } catch (PDOException $e) {
                // Book might exist, skip
            }
        }
        echo "<p style='color: green;'>✓ Added $books_added books</p>";
    } else {
        echo "<p style='color: blue;'>✓ Books already exist ($book_count books)</p>";
    }

    // Insert sample issue records
    echo "<p><strong>Adding sample issue records...</strong></p>";
    $records = [
        ['2021001', 1, '2025-09-15', '2025-10-15', 'Issued'],
        ['2021002', 2, '2025-09-20', '2025-10-20', 'Issued'],
        ['2021003', 3, '2025-09-10', '2025-10-10', 'Returned'],
        ['2021001', 4, '2025-09-25', '2025-10-25', 'Issued'],
        ['2021004', 5, '2025-09-18', '2025-10-18', 'Issued']
    ];

    $sql = "INSERT INTO record (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $records_added = 0;
    foreach ($records as $record) {
        try {
            $stmt->execute($record);
            $records_added++;
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Record error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p style='color: green;'>✓ Added $records_added issue records</p>";

    // Insert sample messages
    echo "<p><strong>Adding sample messages...</strong></p>";
    $messages = [
        ['2021001', 'Welcome to the Library Management System! Please return books on time.', '2025-09-15'],
        ['2021002', 'Your book is due tomorrow. Please renew or return.', '2025-09-19'],
        ['2021003', 'Thank you for returning the book on time!', '2025-09-18'],
        ['2021001', 'New books have arrived in Computer Science section.', '2025-09-25'],
        ['2021004', 'Library timing: 8 AM to 8 PM on weekdays.', '2025-09-20']
    ];

    $sql = "INSERT INTO message (RollNo, Message, Msg_Date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $messages_added = 0;
    foreach ($messages as $message) {
        try {
            $stmt->execute($message);
            $messages_added++;
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Message error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p style='color: green;'>✓ Added $messages_added messages</p>";

    // Insert sample recommendations
    echo "<p><strong>Adding sample recommendations...</strong></p>";
    $recommendations = [
        ['2021001', 'Artificial Intelligence Modern Approach', 'Excellent book for AI fundamentals.'],
        ['2021002', 'Spring Boot in Action', 'Great resource for Spring framework.'],
        ['2021003', 'React Up and Running', 'Comprehensive guide to React.js.'],
        ['2021001', 'Docker Deep Dive', 'Essential for containerization.'],
        ['2021004', 'Blockchain Basics', 'Introduction to blockchain technology.']
    ];

    $sql = "INSERT INTO recommendations (RollNo, Book_Name, Description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $recommendations_added = 0;
    foreach ($recommendations as $recommendation) {
        try {
            $stmt->execute($recommendation);
            $recommendations_added++;
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Recommendation error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p style='color: green;'>✓ Added $recommendations_added recommendations</p>";

    // Insert sample attendance
    echo "<p><strong>Adding sample attendance records...</strong></p>";
    $attendance = [
        ['2021001', '2025-09-27 09:15:00', '2025-09-27 16:30:00', '2025-09-27', 'closed'],
        ['2021002', '2025-09-27 10:00:00', '2025-09-27 15:45:00', '2025-09-27', 'closed'],
        ['2021003', '2025-09-27 08:30:00', '2025-09-27 17:00:00', '2025-09-27', 'closed'],
        ['2021001', '2025-09-26 09:30:00', '2025-09-26 14:20:00', '2025-09-26', 'closed'],
        ['2021004', '2025-09-27 11:15:00', null, '2025-09-27', 'open']
    ];

    $sql = "INSERT INTO attendance (RollNo, checkin_time, checkout_time, attendance_date, session_status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $attendance_added = 0;
    foreach ($attendance as $record) {
        try {
            $stmt->execute($record);
            $attendance_added++;
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Attendance error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p style='color: green;'>✓ Added $attendance_added attendance records</p>";

    echo "<hr>";
    echo "<h3 style='color: green;'>🎉 Sample Data Force Inserted!</h3>";

    // Show final counts
    $tables = ['user', 'book', 'record', 'message', 'recommendations', 'renew', 'return_req', 'attendance'];
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Table</th><th>Total Rows</th></tr>";

    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "<tr><td>$table</td><td><strong>$count</strong></td></tr>";
    }
    echo "</table>";

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='admin/index.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Test Admin Panel</a>";
    echo "<a href='index.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Test Login</a>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Error</h3>";
    echo "<p><strong>Database Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
}
?>
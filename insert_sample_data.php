<?php
// Check current data and insert sample data for empty tables
echo "<h2>Database Sample Data Insertion</h2>";
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

    echo "<p><strong>Step 1:</strong> Checking current data...</p>";

    // Check each table for existing data
    $tables = ['user', 'book', 'record', 'message', 'recommendations', 'renew', 'return_req', 'attendance'];
    $empty_tables = [];

    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>Table</th><th>Current Rows</th><th>Status</th></tr>";

    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $empty_tables[] = $table;
            echo "<tr><td>$table</td><td>$count</td><td style='color: orange;'>EMPTY - Will add data</td></tr>";
        } else {
            echo "<tr><td>$table</td><td>$count</td><td style='color: green;'>HAS DATA</td></tr>";
        }
    }
    echo "</table>";

    if (empty($empty_tables)) {
        echo "<h3 style='color: green;'>✅ All tables have data!</h3>";
        echo "<p>No sample data insertion needed.</p>";
    } else {
        echo "<p><strong>Step 2:</strong> Inserting sample data for empty tables...</p>";

        // Insert sample users (if empty)
        if (in_array('user', $empty_tables)) {
            echo "<p><strong>Adding sample users...</strong></p>";
            $users = [
                ['ADMIN', 'Administrator', 'Admin', 'GEN', 'admin@library.com', '1234567890', 'admin123'],
                ['2021001', 'John Smith', 'Student', 'GEN', 'john.smith@student.edu', '9876543210', 'student123'],
                ['2021002', 'Sarah Johnson', 'Student', 'OBC', 'sarah.j@student.edu', '8765432109', 'student456'],
                ['2021003', 'Mike Chen', 'Student', 'GEN', 'mike.chen@student.edu', '7654321098', 'student789'],
                ['2021004', 'Priya Patel', 'Student', 'SC', 'priya.p@student.edu', '6543210987', 'student321'],
                ['LIB001', 'Library Assistant', 'Admin', 'GEN', 'assistant@library.com', '5432109876', 'libassist123']
            ];

            $sql = "INSERT INTO user (RollNo, Name, Type, Category, EmailId, MobNo, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($users as $user) {
                $stmt->execute($user);
            }
            echo "<p style='color: green;'>✓ Added " . count($users) . " users</p>";
        }

        // Insert sample books (if empty)
        if (in_array('book', $empty_tables)) {
            echo "<p><strong>Adding sample books...</strong></p>";
            $books = [
                ['Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 5],
                ['Operating System Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 8],
                ['Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 4],
                ['Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 6],
                ['Data Structures and Algorithms in Java', 'Robert Lafore', 'Sams Publishing', '2017', 7],
                ['The C Programming Language', 'Brian Kernighan', 'Prentice Hall', '1988', 3],
                ['Design Patterns', 'Gang of Four', 'Addison-Wesley', '1994', 4],
                ['Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 5],
                ['Introduction to Machine Learning', 'Alpaydin Ethem', 'MIT Press', '2020', 3],
                ['Python Programming', 'Mark Lutz', 'O\'Reilly Media', '2019', 6],
                ['Web Development with HTML & CSS', 'Jon Duckett', 'Wiley', '2014', 8],
                ['JavaScript: The Good Parts', 'Douglas Crockford', 'Yahoo Press', '2008', 4],
                ['Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 5],
                ['Computer Architecture', 'David Patterson', 'Morgan Kaufmann', '2017', 3],
                ['Linear Algebra', 'Gilbert Strang', 'Wellesley-Cambridge', '2016', 7]
            ];

            $sql = "INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($books as $book) {
                $stmt->execute($book);
            }
            echo "<p style='color: green;'>✓ Added " . count($books) . " books</p>";
        }

        // Insert sample records (if empty)
        if (in_array('record', $empty_tables)) {
            echo "<p><strong>Adding sample issue records...</strong></p>";
            $records = [
                ['2021001', 1, '2025-09-15', '2025-10-15', 'Issued'],
                ['2021002', 3, '2025-09-20', '2025-10-20', 'Issued'],
                ['2021003', 5, '2025-09-10', '2025-10-10', 'Returned'],
                ['2021001', 7, '2025-09-25', '2025-10-25', 'Issued'],
                ['2021004', 2, '2025-09-18', '2025-10-18', 'Issued']
            ];

            $sql = "INSERT INTO record (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($records as $record) {
                $stmt->execute($record);
            }
            echo "<p style='color: green;'>✓ Added " . count($records) . " issue records</p>";
        }

        // Insert sample messages (if empty)
        if (in_array('message', $empty_tables)) {
            echo "<p><strong>Adding sample messages...</strong></p>";
            $messages = [
                ['2021001', 'Welcome to the Library Management System! Please return books on time.', '2025-09-15'],
                ['2021002', 'Your book "Database System Concepts" is due tomorrow. Please renew or return.', '2025-09-19'],
                ['2021003', 'Thank you for returning the book on time!', '2025-09-18'],
                ['2021001', 'New books have arrived in Computer Science section.', '2025-09-25'],
                ['2021004', 'Library timing changed: Now open 8 AM to 8 PM on weekdays.', '2025-09-20']
            ];

            $sql = "INSERT INTO message (RollNo, Message, Msg_Date) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($messages as $message) {
                $stmt->execute($message);
            }
            echo "<p style='color: green;'>✓ Added " . count($messages) . " messages</p>";
        }

        // Insert sample recommendations (if empty)
        if (in_array('recommendations', $empty_tables)) {
            echo "<p><strong>Adding sample recommendations...</strong></p>";
            $recommendations = [
                ['2021001', 'Artificial Intelligence: A Modern Approach', 'Excellent book for AI fundamentals and advanced concepts.'],
                ['2021002', 'Spring Boot in Action', 'Great resource for learning Spring framework with practical examples.'],
                ['2021003', 'React: Up & Running', 'Comprehensive guide to React.js development for web applications.'],
                ['2021001', 'Docker Deep Dive', 'Essential book for understanding containerization and DevOps.'],
                ['2021004', 'Blockchain Basics', 'Introduction to blockchain technology and cryptocurrency concepts.']
            ];

            $sql = "INSERT INTO recommendations (RollNo, Book_Name, Description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($recommendations as $recommendation) {
                $stmt->execute($recommendation);
            }
            echo "<p style='color: green;'>✓ Added " . count($recommendations) . " recommendations</p>";
        }

        // Insert sample renew requests (if empty)
        if (in_array('renew', $empty_tables)) {
            echo "<p><strong>Adding sample renew requests...</strong></p>";
            $renewals = [
                ['2021001', 1, '2025-09-15', '2025-11-15', 'Requested'],
                ['2021002', 3, '2025-09-20', '2025-11-20', 'Approved'],
                ['2021004', 2, '2025-09-18', '2025-11-18', 'Requested']
            ];

            $sql = "INSERT INTO renew (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($renewals as $renewal) {
                $stmt->execute($renewal);
            }
            echo "<p style='color: green;'>✓ Added " . count($renewals) . " renewal requests</p>";
        }

        // Insert sample return requests (if empty)
        if (in_array('return_req', $empty_tables)) {
            echo "<p><strong>Adding sample return requests...</strong></p>";
            $returns = [
                ['2021003', 5, '2025-09-25', 'Requested'],
                ['2021001', 7, '2025-09-26', 'Approved']
            ];

            $sql = "INSERT INTO return_req (RollNo, BookId, requested_date, Status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($returns as $return) {
                $stmt->execute($return);
            }
            echo "<p style='color: green;'>✓ Added " . count($returns) . " return requests</p>";
        }

        // Insert sample attendance (if empty)
        if (in_array('attendance', $empty_tables)) {
            echo "<p><strong>Adding sample attendance records...</strong></p>";
            $attendance = [
                ['2021001', '2025-09-27 09:15:00', '2025-09-27 16:30:00', '2025-09-27', 'closed'],
                ['2021002', '2025-09-27 10:00:00', '2025-09-27 15:45:00', '2025-09-27', 'closed'],
                ['2021003', '2025-09-27 08:30:00', '2025-09-27 17:00:00', '2025-09-27', 'closed'],
                ['2021001', '2025-09-26 09:30:00', '2025-09-26 14:20:00', '2025-09-26', 'closed'],
                ['2021004', '2025-09-27 11:15:00', null, '2025-09-27', 'open'],
                ['2021002', '2025-09-25 10:45:00', '2025-09-25 16:10:00', '2025-09-25', 'closed']
            ];

            $sql = "INSERT INTO attendance (RollNo, checkin_time, checkout_time, attendance_date, session_status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($attendance as $record) {
                $stmt->execute($record);
            }
            echo "<p style='color: green;'>✓ Added " . count($attendance) . " attendance records</p>";
        }
    }

    echo "<hr>";
    echo "<h3 style='color: green;'>🎉 Sample Data Insertion Complete!</h3>";

    // Final summary
    echo "<p><strong>Final Database Status:</strong></p>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>Table</th><th>Total Rows</th></tr>";

    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "<tr><td>$table</td><td style='font-weight: bold;'>$count</td></tr>";
    }
    echo "</table>";

    echo "<p><strong>Sample Users Created:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> ADMIN / admin123</li>";
    echo "<li><strong>Library Assistant:</strong> LIB001 / libassist123</li>";
    echo "<li><strong>Students:</strong> 2021001, 2021002, 2021003, 2021004 (password: student123, student456, etc.)</li>";
    echo "</ul>";

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='index.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Test Login Page</a>";
    echo "<a href='admin/index.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Admin Panel</a>";
    echo "<a href='check_database.php' style='background: #6f42c1; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Check Database</a>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Error</h3>";
    echo "<p><strong>Database Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Database Setup</a></p>";
}
?>
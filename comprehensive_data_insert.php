<?php
// Comprehensive Sample Data Insertion Script
echo "<h2>📊 Comprehensive Sample Data Insertion</h2>";
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

    echo "<p><strong>✅ Database connection successful</strong></p>";

    // ==========================
    // 1. INSERT MORE USERS
    // ==========================
    echo "<h3>👥 Adding More Users</h3>";

    $users = [
        // Students
        ['2021001', 'John Smith', 'Student', 'GEN', 'john.smith@student.edu', '9876543210', 'student123'],
        ['2021002', 'Sarah Johnson', 'Student', 'OBC', 'sarah.j@student.edu', '8765432109', 'student456'],
        ['2021003', 'Mike Chen', 'Student', 'GEN', 'mike.chen@student.edu', '7654321098', 'student789'],
        ['2021004', 'Priya Patel', 'Student', 'SC', 'priya.p@student.edu', '6543210987', 'student321'],
        ['2021005', 'Ahmed Ali', 'Student', 'GEN', 'ahmed.ali@student.edu', '5432109876', 'student555'],
        ['2021006', 'Emma Wilson', 'Student', 'OBC', 'emma.w@student.edu', '4321098765', 'student666'],
        ['2021007', 'David Brown', 'Student', 'GEN', 'david.b@student.edu', '3210987654', 'student777'],
        ['2021008', 'Lisa Garcia', 'Student', 'ST', 'lisa.g@student.edu', '2109876543', 'student888'],
        ['2021009', 'Ryan Kumar', 'Student', 'GEN', 'ryan.k@student.edu', '1098765432', 'student999'],
        ['2021010', 'Anna Lee', 'Student', 'SC', 'anna.l@student.edu', '9087654321', 'student000'],
        // Staff
        ['LIB001', 'Library Assistant', 'Admin', 'GEN', 'assistant@library.com', '5432109876', 'libassist123'],
        ['LIB002', 'Senior Librarian', 'Admin', 'GEN', 'senior@library.com', '6543210987', 'senior123']
    ];

    $sql = "INSERT IGNORE INTO user (RollNo, Name, Type, Category, EmailId, MobNo, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $users_added = 0;

    foreach ($users as $user) {
        if ($stmt->execute($user)) {
            $users_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added user: {$user[0]} - {$user[1]}</p>";
        }
    }
    echo "<p><strong>Total users added: $users_added</strong></p>";

    // ==========================
    // 2. INSERT MORE BOOKS
    // ==========================
    echo "<h3>📚 Adding More Books</h3>";

    $books = [
        // Computer Science
        ['Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 8],
        ['Operating System Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 6],
        ['Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 7],
        ['Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 5],
        ['Data Structures and Algorithms in Java', 'Robert Lafore', 'Sams Publishing', '2017', 4],

        // Programming
        ['Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 6],
        ['Python Programming', 'Mark Lutz', 'O\'Reilly Media', '2019', 8],
        ['JavaScript: The Good Parts', 'Douglas Crockford', 'Yahoo Press', '2008', 5],
        ['Java: The Complete Reference', 'Herbert Schildt', 'McGraw-Hill', '2020', 7],
        ['C++ Programming Language', 'Bjarne Stroustrup', 'Addison-Wesley', '2013', 4],

        // Web Development
        ['HTML and CSS', 'Jon Duckett', 'Wiley', '2014', 9],
        ['React: Up & Running', 'Stoyan Stefanov', 'O\'Reilly', '2016', 6],
        ['Node.js in Action', 'Mike Cantelon', 'Manning', '2017', 5],
        ['Vue.js Guide', 'Evan You', 'Vue Press', '2019', 4],

        // Data Science & AI
        ['Machine Learning', 'Tom Mitchell', 'McGraw-Hill', '1997', 3],
        ['Artificial Intelligence', 'Stuart Russell', 'Prentice Hall', '2016', 5],
        ['Data Science from Scratch', 'Joel Grus', 'O\'Reilly', '2019', 4],
        ['Deep Learning', 'Ian Goodfellow', 'MIT Press', '2016', 3],

        // Software Engineering
        ['Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 6],
        ['Design Patterns', 'Gang of Four', 'Addison-Wesley', '1994', 4],
        ['Refactoring', 'Martin Fowler', 'Addison-Wesley', '1999', 5],
        ['The Pragmatic Programmer', 'Andrew Hunt', 'Addison-Wesley', '2019', 7],

        // Mathematics & Theory
        ['Discrete Mathematics', 'Kenneth Rosen', 'McGraw-Hill', '2018', 8],
        ['Linear Algebra', 'Gilbert Strang', 'Wellesley-Cambridge', '2016', 6],
        ['Computer Architecture', 'David Patterson', 'Morgan Kaufmann', '2017', 4],
        ['Compiler Design', 'Alfred Aho', 'Pearson', '2006', 3]
    ];

    $sql = "INSERT IGNORE INTO book (Title, Author, Publisher, Year, Availability) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $books_added = 0;

    foreach ($books as $book) {
        if ($stmt->execute($book)) {
            $books_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added book: {$book[0]} by {$book[1]}</p>";
        }
    }
    echo "<p><strong>Total books added: $books_added</strong></p>";

    // ==========================
    // 3. INSERT RECORDS
    // ==========================
    echo "<h3>📋 Adding Issue Records</h3>";

    $records = [
        ['2021001', 1, '2025-09-15', '2025-10-15', 'Issued'],
        ['2021002', 2, '2025-09-20', '2025-10-20', 'Issued'],
        ['2021003', 3, '2025-09-10', '2025-10-10', 'Returned'],
        ['2021004', 4, '2025-09-25', '2025-10-25', 'Issued'],
        ['2021005', 5, '2025-09-18', '2025-10-18', 'Issued'],
        ['2021006', 6, '2025-09-12', '2025-10-12', 'Returned'],
        ['2021007', 7, '2025-09-22', '2025-10-22', 'Issued'],
        ['2021008', 8, '2025-09-14', '2025-10-14', 'Issued'],
        ['2021009', 9, '2025-09-16', '2025-10-16', 'Returned'],
        ['2021010', 10, '2025-09-26', '2025-10-26', 'Issued']
    ];

    $sql = "INSERT INTO record (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $records_added = 0;

    foreach ($records as $record) {
        try {
            $stmt->execute($record);
            $records_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added record: {$record[0]} borrowed BookID {$record[1]}</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Record {$record[0]}-{$record[1]}: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total records added: $records_added</strong></p>";

    // ==========================
    // 4. INSERT MESSAGES
    // ==========================
    echo "<h3>💬 Adding Messages</h3>";

    $messages = [
        ['2021001', 'Welcome to the Library Management System! Please return books on time to avoid fines.', '2025-09-15'],
        ['2021002', 'Your book "Operating System Concepts" is due tomorrow. Please renew or return it.', '2025-09-19'],
        ['2021003', 'Thank you for returning the book on time! Your account is in good standing.', '2025-09-18'],
        ['2021004', 'New books have arrived in the Computer Science section. Check them out!', '2025-09-25'],
        ['2021005', 'Library timing has been updated: Now open 8 AM to 8 PM on weekdays.', '2025-09-20'],
        ['2021006', 'Reminder: Library will be closed on October 2nd for Gandhi Jayanti.', '2025-09-28'],
        ['2021007', 'Your requested book "Clean Code" is now available for pickup.', '2025-09-22'],
        ['2021008', 'Late return fee of Rs. 10 has been waived as a first-time courtesy.', '2025-09-24'],
        ['2021009', 'Study room booking is now available online. Book your slot today!', '2025-09-26'],
        ['2021010', 'Congratulations on completing your reading challenge! Keep it up.', '2025-09-27']
    ];

    $sql = "INSERT INTO message (RollNo, Message, Msg_Date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $messages_added = 0;

    foreach ($messages as $message) {
        try {
            $stmt->execute($message);
            $messages_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added message for {$message[0]}</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Message error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total messages added: $messages_added</strong></p>";

    // ==========================
    // 5. INSERT RECOMMENDATIONS
    // ==========================
    echo "<h3>⭐ Adding Book Recommendations</h3>";

    $recommendations = [
        ['2021001', 'Artificial Intelligence: A Modern Approach', 'Comprehensive guide to AI with practical examples and case studies.'],
        ['2021002', 'Spring Boot in Action', 'Excellent resource for learning Spring framework with hands-on projects.'],
        ['2021003', 'React: Up & Running', 'Best book for learning React.js from basics to advanced concepts.'],
        ['2021004', 'Docker Deep Dive', 'Essential reading for understanding containerization and DevOps practices.'],
        ['2021005', 'Blockchain Revolution', 'Insightful book about blockchain technology and its future applications.'],
        ['2021006', 'Kubernetes in Action', 'Perfect guide for container orchestration and cloud-native development.'],
        ['2021007', 'GraphQL in Action', 'Modern approach to API development with GraphQL and best practices.'],
        ['2021008', 'Microservices Patterns', 'Architectural patterns for building scalable distributed systems.'],
        ['2021009', 'Cloud Native Patterns', 'Design patterns for building resilient cloud applications.'],
        ['2021010', 'DevOps Handbook', 'Complete guide to DevOps practices and continuous delivery.']
    ];

    $sql = "INSERT INTO recommendations (RollNo, Book_Name, Description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $recommendations_added = 0;

    foreach ($recommendations as $recommendation) {
        try {
            $stmt->execute($recommendation);
            $recommendations_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added recommendation by {$recommendation[0]}: {$recommendation[1]}</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Recommendation error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total recommendations added: $recommendations_added</strong></p>";

    // ==========================
    // 6. INSERT RENEW REQUESTS
    // ==========================
    echo "<h3>🔄 Adding Renewal Requests</h3>";

    $renewals = [
        ['2021001', 1, '2025-09-15', '2025-11-15', 'Requested'],
        ['2021002', 2, '2025-09-20', '2025-11-20', 'Approved'],
        ['2021004', 4, '2025-09-25', '2025-11-25', 'Requested'],
        ['2021005', 5, '2025-09-18', '2025-11-18', 'Approved'],
        ['2021007', 7, '2025-09-22', '2025-11-22', 'Requested']
    ];

    $sql = "INSERT INTO renew (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $renewals_added = 0;

    foreach ($renewals as $renewal) {
        try {
            $stmt->execute($renewal);
            $renewals_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added renewal request: {$renewal[0]} for BookID {$renewal[1]}</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Renewal error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total renewals added: $renewals_added</strong></p>";

    // ==========================
    // 7. INSERT RETURN REQUESTS
    // ==========================
    echo "<h3>↩️ Adding Return Requests</h3>";

    $returns = [
        ['2021003', 3, '2025-09-25', 'Requested'],
        ['2021006', 6, '2025-09-26', 'Approved'],
        ['2021009', 9, '2025-09-27', 'Requested'],
        ['2021008', 8, '2025-09-24', 'Approved']
    ];

    $sql = "INSERT INTO return_req (RollNo, BookId, requested_date, Status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $returns_added = 0;

    foreach ($returns as $return) {
        try {
            $stmt->execute($return);
            $returns_added++;
            echo "<p style='color: green; margin: 2px 0;'>✓ Added return request: {$return[0]} for BookID {$return[1]}</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Return error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total return requests added: $returns_added</strong></p>";

    // ==========================
    // 8. INSERT ATTENDANCE
    // ==========================
    echo "<h3>📅 Adding Attendance Records</h3>";

    $attendance = [
        // Today's attendance
        ['2021001', '2025-09-27 09:15:00', '2025-09-27 16:30:00', '2025-09-27', 'closed'],
        ['2021002', '2025-09-27 10:00:00', '2025-09-27 15:45:00', '2025-09-27', 'closed'],
        ['2021003', '2025-09-27 08:30:00', '2025-09-27 17:00:00', '2025-09-27', 'closed'],
        ['2021004', '2025-09-27 11:15:00', null, '2025-09-27', 'open'],
        ['2021005', '2025-09-27 14:20:00', '2025-09-27 18:45:00', '2025-09-27', 'closed'],

        // Yesterday's attendance
        ['2021001', '2025-09-26 09:30:00', '2025-09-26 14:20:00', '2025-09-26', 'closed'],
        ['2021006', '2025-09-26 10:15:00', '2025-09-26 16:30:00', '2025-09-26', 'closed'],
        ['2021007', '2025-09-26 13:00:00', '2025-09-26 17:45:00', '2025-09-26', 'closed'],
        ['2021002', '2025-09-26 08:45:00', '2025-09-26 15:20:00', '2025-09-26', 'closed'],

        // Day before yesterday
        ['2021008', '2025-09-25 10:30:00', '2025-09-25 16:15:00', '2025-09-25', 'closed'],
        ['2021009', '2025-09-25 09:00:00', '2025-09-25 14:30:00', '2025-09-25', 'closed'],
        ['2021010', '2025-09-25 11:45:00', '2025-09-25 17:20:00', '2025-09-25', 'closed'],
        ['2021003', '2025-09-25 12:00:00', '2025-09-25 18:00:00', '2025-09-25', 'closed']
    ];

    $sql = "INSERT INTO attendance (RollNo, checkin_time, checkout_time, attendance_date, session_status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $attendance_added = 0;

    foreach ($attendance as $record) {
        try {
            $stmt->execute($record);
            $attendance_added++;
            $status = $record[4] == 'open' ? '(Still in library)' : '(Session completed)';
            echo "<p style='color: green; margin: 2px 0;'>✓ Added attendance: {$record[0]} on {$record[3]} $status</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange; margin: 2px 0;'>⚠ Attendance error: " . $e->getMessage() . "</p>";
        }
    }
    echo "<p><strong>Total attendance records added: $attendance_added</strong></p>";

    // ==========================
    // FINAL SUMMARY
    // ==========================
    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 Sample Data Insertion Complete!</h2>";

    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th>Table</th><th>Total Rows</th><th>Status</th></tr>";

    $tables = ['user', 'book', 'record', 'message', 'recommendations', 'renew', 'return_req', 'attendance'];
    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        $status = $count > 0 ? '✅ Has Data' : '❌ Empty';
        $color = $count > 0 ? 'green' : 'red';
        echo "<tr><td>$table</td><td style='font-weight: bold;'>$count</td><td style='color: $color;'>$status</td></tr>";
    }
    echo "</table>";

    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>📋 Sample Login Credentials:</h4>";
    echo "<ul style='margin: 10px 0;'>";
    echo "<li><strong>Admin:</strong> ADMIN / admin123</li>";
    echo "<li><strong>Library Staff:</strong> LIB001 / libassist123</li>";
    echo "<li><strong>Students:</strong> 2021001 to 2021010 (passwords: student123, student456, etc.)</li>";
    echo "</ul>";
    echo "</div>";

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='admin/index.php' style='background: #007bff; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; font-weight: bold;'>🔧 Test Admin Panel</a>";
    echo "<a href='index.php' style='background: #28a745; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; font-weight: bold;'>🔐 Test Login Page</a>";
    echo "<a href='check_database.php' style='background: #6f42c1; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>📊 Check Database</a>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Database Connection Error</h3>";
    echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
    echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🔧 Run Database Setup</a></p>";
}
?>
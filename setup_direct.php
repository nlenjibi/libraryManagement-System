<?php
// Direct database setup script
echo "<h2>Direct MySQL Database Setup</h2>";
echo "<hr>";

// Database configuration
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'lms_database';
$db_user = 'root';
$db_pass = '';

try {
    echo "<p><strong>Step 1:</strong> Testing MySQL connection...</p>";

    // Test basic connection
    $dsn = "mysql:host=$db_host;port=$db_port;charset=utf8";
    $conn_test = new PDO($dsn, $db_user, $db_pass);
    $conn_test->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ MySQL server is running</p>";

    echo "<p><strong>Step 2:</strong> Creating database...</p>";
    $conn_test->exec("DROP DATABASE IF EXISTS $db_name");
    $conn_test->exec("CREATE DATABASE $db_name CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "<p style='color: green;'>✓ Database '$db_name' created fresh</p>";

    echo "<p><strong>Step 3:</strong> Connecting to database...</p>";
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to '$db_name'</p>";

    echo "<p><strong>Step 4:</strong> Creating tables...</p>";

    // User table
    $sql = "CREATE TABLE user (
        RollNo VARCHAR(50) PRIMARY KEY,
        Name VARCHAR(100) NOT NULL,
        Type VARCHAR(50) NOT NULL,
        Category VARCHAR(50),
        EmailId VARCHAR(100),
        MobNo VARCHAR(20),
        Password VARCHAR(100) NOT NULL,
        profile_picture VARCHAR(255) DEFAULT 'images/user.png'
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ User table created</p>";

    // Book table
    $sql = "CREATE TABLE book (
        BookId INT AUTO_INCREMENT PRIMARY KEY,
        Title VARCHAR(200) NOT NULL,
        Author VARCHAR(200),
        Publisher VARCHAR(200),
        Year VARCHAR(10),
        Availability INT DEFAULT 0
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Book table created</p>";

    // Record table
    $sql = "CREATE TABLE record (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        IssueDate DATE,
        ReturnDate DATE,
        Status VARCHAR(20) DEFAULT 'Issued'
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Record table created</p>";

    // Message table
    $sql = "CREATE TABLE message (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        Message TEXT,
        Msg_Date DATE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Message table created</p>";

    // Recommendations table
    $sql = "CREATE TABLE recommendations (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        Book_Name VARCHAR(200),
        Description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Recommendations table created</p>";

    // Renew table
    $sql = "CREATE TABLE renew (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        IssueDate DATE,
        ReturnDate DATE,
        Status VARCHAR(20) DEFAULT 'Requested'
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Renew table created</p>";

    // Return request table
    $sql = "CREATE TABLE return_req (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        requested_date DATE,
        Status VARCHAR(20) DEFAULT 'Requested'
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Return request table created</p>";

    // Attendance table
    $sql = "CREATE TABLE attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        checkin_time DATETIME,
        checkout_time DATETIME,
        attendance_date DATE,
        session_status VARCHAR(20) DEFAULT 'open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Attendance table created</p>";

    echo "<p><strong>Step 5:</strong> Inserting default data...</p>";

    // Insert admin user
    $sql = "INSERT INTO user (RollNo, Name, Type, EmailId, MobNo, Password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['ADMIN', 'Administrator', 'Admin', 'admin@library.com', '1234567890', 'admin123']);
    echo "<p style='color: green;'>✓ Admin user created</p>";

    // Insert sample books
    $books = [
        ['Operating Systems Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 5],
        ['Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 3],
        ['Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 4],
        ['Data Structures and Algorithms', 'Michael T. Goodrich', 'John Wiley & Sons', '2014', 10],
        ['Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 8]
    ];

    $sql = "INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($books as $book) {
        $stmt->execute($book);
    }
    echo "<p style='color: green;'>✓ Sample books inserted</p>";

    echo "<p><strong>Step 6:</strong> Verifying setup...</p>";

    // Test the exact query that was failing
    $sql = "SELECT Type FROM user WHERE RollNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['ADMIN']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "<p style='color: green;'>✓ Admin query test successful - Type: {$result['Type']}</p>";
    } else {
        echo "<p style='color: red;'>✗ Admin query failed</p>";
    }

    echo "<hr>";
    echo "<h3 style='color: green;'>🎉 Database Setup Complete!</h3>";
    echo "<p><strong>Ready to use:</strong></p>";
    echo "<ul>";
    echo "<li>Database: $db_name</li>";
    echo "<li>Tables: 8 tables created</li>";
    echo "<li>Admin User: ADMIN / admin123</li>";
    echo "<li>Sample Books: 5 books available</li>";
    echo "</ul>";

    echo "<p><a href='index.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Login to System</a>";
    echo "<a href='admin/index.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Admin Panel</a></p>";

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Setup Failed</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";

    if ($e->getCode() == 2002) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>MySQL Server Not Running</h4>";
        echo "<p><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li>Open XAMPP Control Panel</li>";
        echo "<li>Click 'Start' button next to MySQL</li>";
        echo "<li>Wait for status to show 'Running'</li>";
        echo "<li>Refresh this page</li>";
        echo "</ol>";
        echo "</div>";
    } elseif ($e->getCode() == 1045) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>Access Denied</h4>";
        echo "<p>MySQL username or password incorrect.</p>";
        echo "<p>Default XAMPP MySQL has no password for 'root' user.</p>";
        echo "</div>";
    }
}
?>
<?php
// Database initialization script for MySQL XAMPP
echo "<h2>MySQL Database Setup for Library Management System</h2>";
echo "<hr>";

// Database configuration
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'lms_database';
$db_user = 'root';
$db_pass = '';

try {
    echo "<p><strong>Step 1:</strong> Connecting to MySQL server...</p>";

    // Connect to MySQL server (without specifying database)
    $conn_setup = new PDO("mysql:host=$db_host;port=$db_port;charset=utf8", $db_user, $db_pass);
    $conn_setup->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p style='color: green;'>✓ Connected to MySQL server successfully</p>";

    echo "<p><strong>Step 2:</strong> Creating database '$db_name'...</p>";

    // Create database
    $conn_setup->exec("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8 COLLATE utf8_general_ci");

    echo "<p style='color: green;'>✓ Database '$db_name' created successfully</p>";

    echo "<p><strong>Step 3:</strong> Connecting to database '$db_name'...</p>";

    // Connect to the specific database
    $conn = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p style='color: green;'>✓ Connected to database '$db_name' successfully</p>";

    echo "<p><strong>Step 4:</strong> Creating tables...</p>";

    // Create user table
    $conn->exec("CREATE TABLE IF NOT EXISTS user (
        RollNo VARCHAR(50) PRIMARY KEY,
        Name VARCHAR(100),
        Type VARCHAR(50),
        Category VARCHAR(50),
        EmailId VARCHAR(100) UNIQUE,
        MobNo VARCHAR(20),
        Password VARCHAR(100),
        profile_picture VARCHAR(255) DEFAULT 'images/user.png'
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ User table created</p>";

    // Create book table
    $conn->exec("CREATE TABLE IF NOT EXISTS book (
        BookId INT AUTO_INCREMENT PRIMARY KEY,
        Title VARCHAR(200),
        Author VARCHAR(200),
        Publisher VARCHAR(200),
        Year VARCHAR(10),
        Availability INT DEFAULT 0
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Book table created</p>";

    // Create record table
    $conn->exec("CREATE TABLE IF NOT EXISTS record (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        IssueDate DATE,
        ReturnDate DATE,
        Status VARCHAR(20) DEFAULT 'Issued',
        INDEX(RollNo),
        INDEX(BookId),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE,
        FOREIGN KEY(BookId) REFERENCES book(BookId) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Record table created</p>";

    // Create message table
    $conn->exec("CREATE TABLE IF NOT EXISTS message (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        Message TEXT,
        Msg_Date DATE,
        INDEX(RollNo),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Message table created</p>";

    // Create recommendations table
    $conn->exec("CREATE TABLE IF NOT EXISTS recommendations (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        Book_Name VARCHAR(200),
        Description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(RollNo),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Recommendations table created</p>";

    // Create renew table
    $conn->exec("CREATE TABLE IF NOT EXISTS renew (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        IssueDate DATE,
        ReturnDate DATE,
        Status VARCHAR(20) DEFAULT 'Requested',
        INDEX(RollNo),
        INDEX(BookId),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE,
        FOREIGN KEY(BookId) REFERENCES book(BookId) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Renew table created</p>";

    // Create return_req table
    $conn->exec("CREATE TABLE IF NOT EXISTS return_req (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        BookId INT,
        requested_date DATE DEFAULT (CURRENT_DATE),
        Status VARCHAR(20) DEFAULT 'Requested',
        INDEX(RollNo),
        INDEX(BookId),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE,
        FOREIGN KEY(BookId) REFERENCES book(BookId) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Return request table created</p>";

    // Create attendance table
    $conn->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        RollNo VARCHAR(50),
        checkin_time DATETIME,
        checkout_time DATETIME,
        attendance_date DATE,
        session_status VARCHAR(20) DEFAULT 'open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(RollNo),
        INDEX(attendance_date),
        FOREIGN KEY(RollNo) REFERENCES user(RollNo) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "<p style='color: green;'>✓ Attendance table created</p>";

    echo "<p><strong>Step 5:</strong> Inserting default data...</p>";

    // Insert default admin user
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE RollNo = 'ADMIN'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $conn->exec("INSERT INTO user (RollNo, Name, Type, EmailId, MobNo, Password) 
                     VALUES ('ADMIN', 'Administrator', 'Admin', 'admin@library.com', '1234567890', 'admin123')");
        echo "<p style='color: green;'>✓ Default admin user created (ADMIN / admin123)</p>";
    } else {
        echo "<p style='color: blue;'>✓ Default admin user already exists</p>";
    }

    // Insert sample books
    $stmt = $conn->prepare("SELECT COUNT(*) FROM book");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $conn->exec("INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES 
                     ('Operating Systems Concepts', 'Abraham Silberschatz', 'John Wiley & Sons', '2018', 5),
                     ('Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', '2019', 3),
                     ('Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', '2009', 4),
                     ('Data Structures and Algorithms', 'Michael T. Goodrich', 'John Wiley & Sons', '2014', 10),
                     ('Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', '2011', 8),
                     ('Software Engineering', 'Ian Sommerville', 'Pearson', '2016', 6),
                     ('Artificial Intelligence: A Modern Approach', 'Stuart Russell', 'Pearson', '2020', 7)");
        echo "<p style='color: green;'>✓ Sample books inserted</p>";
    } else {
        echo "<p style='color: blue;'>✓ Sample books already exist</p>";
    }

    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Database Setup Complete!</h3>";
    echo "<p><strong>Database Details:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Host:</strong> $db_host</li>";
    echo "<li><strong>Port:</strong> $db_port</li>";
    echo "<li><strong>Database:</strong> $db_name</li>";
    echo "<li><strong>Engine:</strong> MySQL/MariaDB</li>";
    echo "</ul>";

    echo "<p><strong>Admin Login:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> ADMIN</li>";
    echo "<li><strong>Password:</strong> admin123</li>";
    echo "</ul>";

    echo "<hr>";
    echo "<p><a href='index.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Go to Library System</a>";
    echo "<a href='setup.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View System Status</a></p>";

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Setup Failed</h3>";

    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<p style='color: red;'><strong>Error:</strong> MySQL server is not running.</p>";
        echo "<p><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li>Open XAMPP Control Panel</li>";
        echo "<li>Click 'Start' next to MySQL</li>";
        echo "<li>Wait for MySQL to start (should show 'Running')</li>";
        echo "<li>Refresh this page</li>";
        echo "</ol>";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<p style='color: red;'><strong>Error:</strong> Access denied to MySQL.</p>";
        echo "<p><strong>Solution:</strong> Check your MySQL username/password settings in dbconn.php</p>";
    } else {
        echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
}
?>
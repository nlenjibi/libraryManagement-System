<?php
session_start();

// Use SQLite for local development
$db_path = 'lms_database.sqlite';

try {
    $conn = new PDO("sqlite:$db_path");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS user (
        RollNo VARCHAR(50) PRIMARY KEY,
        Name VARCHAR(50),
        Type VARCHAR(50),
        Category VARCHAR(50),
        EmailId VARCHAR(50) UNIQUE,
        MobNo INTEGER,
        Password VARCHAR(50),
        ProfileImage VARCHAR(255)
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS book (
        BookId INTEGER PRIMARY KEY AUTOINCREMENT,
        Title VARCHAR(50),
        Publisher VARCHAR(50),
        Year VARCHAR(50),
        Availability INTEGER DEFAULT 0
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS record (
        RollNo VARCHAR(50),
        BookId INTEGER,
        IssueDate DATE,
        ReturnDate DATE,
        Status VARCHAR(20) DEFAULT 'Issued',
        FOREIGN KEY(RollNo) REFERENCES user(RollNo),
        FOREIGN KEY(BookId) REFERENCES book(BookId)
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS message (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        RollNo VARCHAR(50),
        Message TEXT,
        Msg_Date DATE,
        FOREIGN KEY(RollNo) REFERENCES user(RollNo)
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS recommendations (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        RollNo VARCHAR(50),
        Title VARCHAR(100),
        Author VARCHAR(100),
        BookId INTEGER,
        FOREIGN KEY(RollNo) REFERENCES user(RollNo)
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS renew (
        RollNo VARCHAR(50),
        BookId INTEGER,
        IssueDate DATE,
        ReturnDate DATE,
        FOREIGN KEY(RollNo) REFERENCES user(RollNo),
        FOREIGN KEY(BookId) REFERENCES book(BookId)
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS return_req (
        RollNo VARCHAR(50),
        BookId INTEGER,
        FOREIGN KEY(RollNo) REFERENCES user(RollNo),
        FOREIGN KEY(BookId) REFERENCES book(BookId)
    )");
    
    // New table for attendance tracking - supports multiple sessions per day
    $conn->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        RollNo VARCHAR(50),
        checkin_time DATETIME,
        checkout_time DATETIME,
        attendance_date DATE,
        session_status VARCHAR(20) DEFAULT 'open',
        FOREIGN KEY(RollNo) REFERENCES user(RollNo)
    )");
    
    // Insert default admin user if not exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE RollNo = 'ADMIN'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $conn->exec("INSERT INTO user (RollNo, Name, Type, EmailId, MobNo, Password) 
                     VALUES ('ADMIN', 'admin', 'Admin', 'admin@gmail.com', 123456789, 'admin')");
    }
    
    // Insert some sample books if none exist
    $stmt = $conn->prepare("SELECT COUNT(*) FROM book");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $conn->exec("INSERT INTO book (Title, Publisher, Year, Availability) VALUES 
                     ('Operating Systems', 'PEARSON', '2006', 5),
                     ('Database Management Systems', 'TARGET67', '2010', 3),
                     ('Theory of Computation', 'NITC', '2018', 4),
                     ('Data Structures and Algorithms', 'X', '2010', 10),
                     ('Discrete Structures', 'Pearson', '2010', 8)");
    }
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
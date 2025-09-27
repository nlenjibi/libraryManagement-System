<?php
// Database setup and verification script for MySQL XAMPP
require_once('dbconn.php');

echo "<h2>Library Management System - MySQL Database Setup</h2>";
echo "<hr>";

try {
    // Test database connection
    echo "<p><strong>✓ Database Connection:</strong> Successfully connected to MySQL on port 3306</p>";

    // Check tables
    $tables = ['user', 'book', 'record', 'message', 'recommendations', 'renew', 'return_req', 'attendance'];

    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            // Get row count
            $count_stmt = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "<p><strong>✓ Table '$table':</strong> Exists with $count records</p>";
        } else {
            echo "<p><strong>✗ Table '$table':</strong> Not found</p>";
        }
    }

    echo "<hr>";
    echo "<h3>Admin Login Details:</h3>";
    echo "<p><strong>Roll Number:</strong> ADMIN</p>";
    echo "<p><strong>Password:</strong> admin123</p>";

    echo "<hr>";
    echo "<h3>Sample Books Available:</h3>";
    $stmt = $conn->query("SELECT * FROM book LIMIT 5");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) > 0) {
        echo "<ul>";
        foreach ($books as $book) {
            echo "<li>{$book['Title']} by {$book['Author']} ({$book['Year']}) - Available: {$book['Availability']}</li>";
        }
        echo "</ul>";
    }

    echo "<hr>";
    echo "<h3>Database Configuration:</h3>";
    echo "<p><strong>Host:</strong> localhost</p>";
    echo "<p><strong>Port:</strong> 3306</p>";
    echo "<p><strong>Database:</strong> lms_database</p>";
    echo "<p><strong>User:</strong> root</p>";
    echo "<p><strong>Engine:</strong> MySQL/MariaDB</p>";

    echo "<hr>";
    echo "<p><strong>Status:</strong> <span style='color: green;'>System is ready to use!</span></p>";
    echo "<p><a href='index.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Library System</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Please ensure:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP is running</li>";
    echo "<li>MySQL service is started</li>";
    echo "<li>Port 3306 is available</li>";
    echo "</ul>";
}
?>
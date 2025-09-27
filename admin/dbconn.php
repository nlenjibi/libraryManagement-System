<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MySQL database configuration for XAMPP
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'lms_database';
$db_user = 'root';
$db_pass = '';

try {
    // Connect to the database
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Quick test to see if the user table exists and is accessible
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user LIMIT 1");
    $stmt->execute();

} catch (PDOException $e) {
    // Database or table doesn't exist - redirect to setup
    if ($e->getCode() == 1049 || strpos($e->getMessage(), 'no such table') !== false || strpos($e->getMessage(), "doesn't exist") !== false) {
        // Redirect to setup script
        header("Location: ../setup_direct.php");
        exit();
    } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
        die("<div style='padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px; font-family: Arial, sans-serif;'>
             <h3>❌ MySQL Server Not Running</h3>
             <p>Please start XAMPP MySQL service and try again.</p>
             <p><strong>Error:</strong> " . $e->getMessage() . "</p>
             </div>");
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        die("<div style='padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px; font-family: Arial, sans-serif;'>
             <h3>❌ Access Denied</h3>
             <p>MySQL username or password incorrect.</p>
             <p><strong>Error:</strong> " . $e->getMessage() . "</p>
             </div>");
    } else {
        die("<div style='padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px; font-family: Arial, sans-serif;'>
             <h3>❌ Database Error</h3>
             <p><strong>Error:</strong> " . $e->getMessage() . "</p>
             <p><a href='../setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Database Setup</a></p>
             </div>");
    }
}
?>
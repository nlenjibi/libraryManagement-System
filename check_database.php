<?php
// Database diagnostic script
echo "<h2>Database Diagnostic Check</h2>";
echo "<hr>";

// Database configuration
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'lms_database';
$db_user = 'root';
$db_pass = '';

try {
    echo "<p><strong>Step 1:</strong> Checking MySQL connection...</p>";
    $dsn = "mysql:host=$db_host;port=$db_port;charset=utf8";
    $conn_test = new PDO($dsn, $db_user, $db_pass);
    $conn_test->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ MySQL server is running</p>";

    echo "<p><strong>Step 2:</strong> Checking if database exists...</p>";
    $stmt = $conn_test->query("SHOW DATABASES LIKE '$db_name'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Database '$db_name' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Database '$db_name' does NOT exist</p>";
        echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Setup Script</a></p>";
        exit;
    }

    echo "<p><strong>Step 3:</strong> Connecting to database...</p>";
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to '$db_name'</p>";

    echo "<p><strong>Step 4:</strong> Checking tables...</p>";
    $expected_tables = ['user', 'book', 'record', 'message', 'recommendations', 'renew', 'return_req', 'attendance'];
    $stmt = $conn->query("SHOW TABLES");
    $existing_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "<p>Tables found: " . count($existing_tables) . "</p>";
    echo "<ul>";

    $missing_tables = [];
    foreach ($expected_tables as $table) {
        if (in_array($table, $existing_tables)) {
            echo "<li style='color: green;'>✓ $table</li>";
        } else {
            echo "<li style='color: red;'>✗ $table (MISSING)</li>";
            $missing_tables[] = $table;
        }
    }
    echo "</ul>";

    if (!empty($missing_tables)) {
        echo "<p style='color: red;'><strong>Problem:</strong> " . count($missing_tables) . " tables are missing!</p>";
        echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Setup Script</a></p>";
    }

    echo "<p><strong>Step 5:</strong> Testing user table access...</p>";
    if (in_array('user', $existing_tables)) {
        try {
            $stmt = $conn->query("SELECT COUNT(*) FROM user");
            $count = $stmt->fetchColumn();
            echo "<p style='color: green;'>✓ User table accessible - Contains $count users</p>";

            // Test the specific query that's failing
            $stmt = $conn->prepare("SELECT Type FROM user WHERE RollNo = ?");
            $stmt->execute(['ADMIN']);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<p style='color: green;'>✓ Admin user found - Type: {$result['Type']}</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Admin user not found - You may need to run setup</p>";
            }

        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Error accessing user table: " . $e->getMessage() . "</p>";
        }
    }

    echo "<p><strong>Step 6:</strong> Database summary...</p>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Table</th><th>Status</th><th>Row Count</th></tr>";

    foreach ($expected_tables as $table) {
        if (in_array($table, $existing_tables)) {
            try {
                $stmt = $conn->query("SELECT COUNT(*) FROM $table");
                $count = $stmt->fetchColumn();
                echo "<tr><td>$table</td><td style='color: green;'>EXISTS</td><td>$count</td></tr>";
            } catch (PDOException $e) {
                echo "<tr><td>$table</td><td style='color: red;'>ERROR</td><td>" . $e->getMessage() . "</td></tr>";
            }
        } else {
            echo "<tr><td>$table</td><td style='color: red;'>MISSING</td><td>-</td></tr>";
        }
    }
    echo "</table>";

    echo "<hr>";
    if (empty($missing_tables) && in_array('user', $existing_tables)) {
        echo "<h3 style='color: green;'>✅ Database appears to be working!</h3>";
        echo "<p><a href='index.php' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Go to Login</a>";
        echo "<a href='admin/index.php' style='background: #007cba; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Test Admin Panel</a></p>";
    } else {
        echo "<h3 style='color: red;'>❌ Database has issues!</h3>";
        echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Setup Script</a></p>";
    }

} catch (PDOException $e) {
    echo "<hr>";
    echo "<h3 style='color: red;'>❌ Database Check Failed</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";

    if ($e->getCode() == 2002) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>MySQL Server Not Running</h4>";
        echo "<p>Please start XAMPP MySQL service first.</p>";
        echo "</div>";
    } elseif ($e->getCode() == 1049) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>Database Does Not Exist</h4>";
        echo "<p>The database '$db_name' was not found.</p>";
        echo "<p><a href='setup_direct.php' style='background: #dc3545; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Run Setup Script</a></p>";
        echo "</div>";
    }
}
?>
<?php
require('dbconn.php');

echo "=== Checking User Table Structure ===\n";
try {
    $stmt = $conn->prepare("DESCRIBE user");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $column) {
        echo "Column: " . $column['Field'] . " - Type: " . $column['Type'] . " - Null: " . $column['Null'] . " - Default: " . $column['Default'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
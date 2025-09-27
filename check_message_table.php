<?php
require('dbconn.php');

echo "=== Checking Message Table Structure ===\n";
try {
    $stmt = $conn->prepare("DESCRIBE message");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $column) {
        echo "Column: " . $column['Field'] . " - Type: " . $column['Type'] . " - Null: " . $column['Null'] . " - Default: " . $column['Default'] . "\n";
    }

    echo "\n=== Sample Messages ===\n";
    $stmt2 = $conn->prepare("SELECT * FROM message LIMIT 5");
    $stmt2->execute();
    $messages = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    if (empty($messages)) {
        echo "No messages found in table\n";
    } else {
        foreach ($messages as $msg) {
            print_r($msg);
            echo "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
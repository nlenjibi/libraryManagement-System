<?php
require('dbconn.php');

echo "=== Checking Record Table Structure ===\n";
$stmt = $conn->prepare("DESCRIBE record");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($columns as $column) {
    echo "Column: " . $column['Field'] . " - Type: " . $column['Type'] . " - Null: " . $column['Null'] . " - Default: " . $column['Default'] . "\n";
}

echo "\n=== Sample Records ===\n";
$stmt = $conn->prepare("SELECT * FROM record LIMIT 5");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($records)) {
    echo "No records found in record table\n";
} else {
    foreach ($records as $record) {
        echo "ID: " . $record['id'] . " - BookId: " . $record['BookId'] . " - RollNo: " . $record['RollNo'] . " - Status: " . $record['Status'] . "\n";
    }
}

echo "\n=== Checking Book Table Structure ===\n";
$stmt = $conn->prepare("DESCRIBE book");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($columns as $column) {
    echo "Column: " . $column['Field'] . " - Type: " . $column['Type'] . "\n";
}
?>
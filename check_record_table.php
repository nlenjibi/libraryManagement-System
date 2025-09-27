<?php
require('dbconn.php');

// Check record table structure
echo "=== Record Table Structure ===\n";
$stmt = $conn->prepare("DESCRIBE record");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($columns as $column) {
    echo "Column: " . $column['Field'] . " - Type: " . $column['Type'] . "\n";
}

// Check existing data in record table
echo "\n=== Sample Records ===\n";
$stmt = $conn->prepare("SELECT * FROM record WHERE Date_of_Return IS NULL LIMIT 5");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($records as $record) {
    echo "BookId: " . $record['BookId'] . " - RollNo: " . $record['RollNo'] . "\n";
}
?>
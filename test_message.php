<?php
require('dbconn.php');

echo "=== Testing Message Functionality ===\n";

// Test if we can insert a message directly
try {
    $test_rollno = '2021001';
    $test_message = 'Test message from debug script';

    echo "Testing message insertion...\n";

    $sql1 = "INSERT INTO message (RollNo, Msg, Date, Time) VALUES (?, ?, CURDATE(), CURTIME())";
    $stmt = $conn->prepare($sql1);
    $stmt->execute([$test_rollno, $test_message]);

    if ($stmt->rowCount() > 0) {
        echo "✅ Message inserted successfully!\n";
        echo "Inserted message for RollNo: $test_rollno\n";
        echo "Message: $test_message\n";
    } else {
        echo "❌ Failed to insert message - no rows affected\n";
    }

    // Check if the message was actually inserted
    echo "\nChecking recent messages...\n";
    $check_sql = "SELECT * FROM message ORDER BY Date DESC, Time DESC LIMIT 5";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute();
    $messages = $check_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $msg) {
        echo "RollNo: " . $msg['RollNo'] . " - Message: " . $msg['Msg'] . " - Date: " . $msg['Date'] . " - Time: " . $msg['Time'] . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Check if RollNo exists in user table
echo "\nChecking if test RollNo exists in user table...\n";
try {
    $user_check = "SELECT RollNo, Name FROM user WHERE RollNo = ?";
    $user_stmt = $conn->prepare($user_check);
    $user_stmt->execute(['2021001']);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "✅ User found: " . $user['Name'] . " (" . $user['RollNo'] . ")\n";
    } else {
        echo "❌ User with RollNo 2021001 not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking user: " . $e->getMessage() . "\n";
}
?>
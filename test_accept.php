<?php
require('dbconn.php');

echo "=== Testing Accept Functionality ===\n";

// Check if we have any pending requests
$stmt = $conn->prepare("SELECT * FROM record WHERE Status = 'Requested' LIMIT 1");
$stmt->execute();
$pending = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pending) {
    echo "Found pending request:\n";
    echo "BookId: " . $pending['BookId'] . ", RollNo: " . $pending['RollNo'] . "\n";

    // Test accept functionality
    $bookid = $pending['BookId'];
    $rollno = $pending['RollNo'];

    echo "\nTesting accept for BookId: $bookid, RollNo: $rollno\n";

    try {
        // Get user category
        $sql = "SELECT Category FROM user WHERE RollNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rollno]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "User found, Category: " . $row['Category'] . "\n";

            // Test the update query
            $sql1 = "UPDATE record SET IssueDate = CURDATE(), Status = 'Issued' WHERE BookId = ? AND RollNo = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->execute([$bookid, $rollno]);

            echo "Update affected rows: " . $stmt1->rowCount() . "\n";

            if ($stmt1->rowCount() > 0) {
                echo "✅ Accept functionality should work!\n";
            } else {
                echo "❌ No rows updated - check the record exists\n";
            }
        } else {
            echo "❌ User not found\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No pending requests found. Let's create one for testing...\n";

    // Create a test request
    $stmt = $conn->prepare("INSERT INTO record (RollNo, BookId, Status) VALUES ('2021001', 1, 'Requested')");
    $stmt->execute();
    echo "Created test request. Re-run this script to test accept functionality.\n";
}
?>
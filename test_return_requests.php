<?php
require('dbconn.php');

// Check existing users
echo "=== Existing Users ===\n";
$stmt = $conn->prepare("SELECT RollNo, Name FROM user ORDER BY RollNo LIMIT 10");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "RollNo: " . $user['RollNo'] . " - Name: " . $user['Name'] . "\n";
}

// Check existing books that are issued
echo "\n=== Books Currently Issued ===\n";
$stmt = $conn->prepare("SELECT r.BookId, r.RollNo, b.Title, r.IssueDate 
                       FROM record r 
                       JOIN book b ON r.BookId = b.BookId 
                       WHERE r.ReturnDate IS NULL 
                       ORDER BY r.IssueDate DESC LIMIT 10");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($records as $record) {
    echo "BookId: " . $record['BookId'] . " - RollNo: " . $record['RollNo'] . " - Title: " . $record['Title'] . " - IssueDate: " . $record['IssueDate'] . "\n";
}

// Now add valid return requests
echo "\n=== Adding Return Requests ===\n";
if (!empty($records)) {
    try {
        // Use first 3 records to create return requests
        $count = 0;
        foreach ($records as $record) {
            if ($count >= 3)
                break;

            $bookid = $record['BookId'];
            $rollno = $record['RollNo'];

            // Check if return request already exists
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM return_req WHERE BookId = ? AND RollNo = ? AND Status = 'Requested'");
            $check_stmt->execute([$bookid, $rollno]);
            $exists = $check_stmt->fetchColumn();

            if ($exists == 0) {
                $insert_stmt = $conn->prepare("INSERT INTO return_req (BookId, RollNo, Status, requested_date) VALUES (?, ?, 'Requested', CURDATE() - INTERVAL ? DAY)");
                $days_ago = $count; // 0, 1, 2 days ago
                $insert_stmt->execute([$bookid, $rollno, $days_ago]);
                echo "Added return request for BookId: $bookid, RollNo: $rollno\n";
                $count++;
            } else {
                echo "Return request already exists for BookId: $bookid, RollNo: $rollno\n";
            }
        }
    } catch (Exception $e) {
        echo "Error adding return requests: " . $e->getMessage() . "\n";
    }
} else {
    echo "No issued books found to create return requests.\n";
}

// Show current return requests
echo "\n=== Current Return Requests ===\n";
$stmt = $conn->prepare("SELECT rr.BookId, rr.RollNo, b.Title, rr.requested_date, rr.Status 
                       FROM return_req rr 
                       JOIN book b ON rr.BookId = b.BookId 
                       WHERE rr.Status = 'Requested' 
                       ORDER BY rr.requested_date");
$stmt->execute();
$return_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($return_requests)) {
    echo "No pending return requests found.\n";
} else {
    foreach ($return_requests as $req) {
        echo "BookId: " . $req['BookId'] . " - RollNo: " . $req['RollNo'] . " - Title: " . $req['Title'] . " - Date: " . $req['requested_date'] . "\n";
    }
}
?>
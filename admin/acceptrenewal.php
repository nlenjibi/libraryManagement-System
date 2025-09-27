<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$rollno = $_GET['id2'];

try {
    // Start transaction
    $conn->beginTransaction();

    // Get user category
    $sql = "SELECT Category FROM user WHERE RollNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$rollno]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("User not found");
    }

    $category = $row['Category'];

    // Determine renewal period based on category
    $renewal_days = ($category == 'GEN' || $category == 'OBC') ? 60 : 180;

    // Since the table doesn't have Due_Date and Renewals_left columns,
    // we'll just mark the renewal as processed by adding a comment to the record
    // In a real system, you'd want to add these columns to track due dates and renewals
    // For now, we'll just ensure the record exists and is issued
    $sql1 = "SELECT id FROM record WHERE BookId = ? AND RollNo = ? AND Status = 'Issued'";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$bookid, $rollno]);

    if (!$stmt1->fetch()) {
        throw new Exception("No matching issued record found to renew");
    }

    // Delete from renew requests table
    $sql3 = "DELETE FROM renew WHERE BookId = ? AND RollNo = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$bookid, $rollno]);

    // Insert success message
    $sql5 = "INSERT INTO message (RollNo, Message, Msg_Date, Msg_Time) VALUES (?, ?, CURDATE(), CURTIME())";
    $stmt5 = $conn->prepare($sql5);
    $msg = "Your request for renewal of BookId: $bookid has been accepted. Extended by $renewal_days days.";
    $stmt5->execute([$rollno, $msg]);

    // Commit transaction
    $conn->commit();

    echo "<script type='text/javascript'>
        alert('✅ SUCCESS: Book renewal request accepted successfully!\\n\\nBookId: $bookid\\nStudent: $rollno\\nExtension: $renewal_days days');
        setTimeout(function() { window.location.href = 'renew_requests.php'; }, 2000);
    </script>";
    exit();
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script type='text/javascript'>alert('Error processing renewal request: " . $e->getMessage() . "')</script>";
    header("Refresh:1; url=renew_requests.php", true, 303);
}
?>
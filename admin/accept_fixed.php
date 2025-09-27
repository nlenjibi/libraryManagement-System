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

    // Determine due date based on category (60 days for GEN/OBC, 180 days for others)
    $loan_days = ($category == 'GEN' || $category == 'OBC') ? 60 : 180;

    // Update record with issue date, due date, and renewals left
    $sql1 = "UPDATE record SET IssueDate = CURDATE(), Due_Date = DATE_ADD(CURDATE(), INTERVAL ? DAY), Renewals_left = 1 WHERE BookId = ? AND RollNo = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$loan_days, $bookid, $rollno]);

    if ($stmt1->rowCount() == 0) {
        throw new Exception("No matching record found to update");
    }

    // Decrease book availability
    $sql3 = "UPDATE book SET Availability = Availability - 1 WHERE BookId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$bookid]);

    // Insert success message
    $sql5 = "INSERT INTO message (RollNo, Msg, Date, Time) VALUES (?, ?, CURDATE(), CURTIME())";
    $stmt5 = $conn->prepare($sql5);
    $msg = "Your request for issue of BookId: $bookid has been accepted. Due date: " . date('Y-m-d', strtotime("+$loan_days days"));
    $stmt5->execute([$rollno, $msg]);

    // Commit transaction
    $conn->commit();

    echo "<script type='text/javascript'>alert('Book issue request accepted successfully!')</script>";
    header("Refresh:0.01; url=issue_requests.php", true, 303);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script type='text/javascript'>alert('Error processing issue request: " . $e->getMessage() . "')</script>";
    header("Refresh:1; url=issue_requests.php", true, 303);
}
?>
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

    // Update record with new due date and set renewals left to 0
    $sql1 = "UPDATE record SET Due_Date = DATE_ADD(Due_Date, INTERVAL ? DAY), Renewals_left = 0 WHERE BookId = ? AND RollNo = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$renewal_days, $bookid, $rollno]);

    if ($stmt1->rowCount() == 0) {
        throw new Exception("No matching record found to renew");
    }

    // Delete from renew requests table
    $sql3 = "DELETE FROM renew WHERE BookId = ? AND RollNo = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$bookid, $rollno]);

    // Insert success message
    $sql5 = "INSERT INTO message (RollNo, Msg, Date, Time) VALUES (?, ?, CURDATE(), CURTIME())";
    $stmt5 = $conn->prepare($sql5);
    $msg = "Your request for renewal of BookId: $bookid has been accepted. New due date extended by $renewal_days days.";
    $stmt5->execute([$rollno, $msg]);

    // Commit transaction
    $conn->commit();

    echo "<script type='text/javascript'>alert('Renewal request accepted successfully!')</script>";
    header("Refresh:0.01; url=renew_requests.php", true, 303);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script type='text/javascript'>alert('Error processing renewal request: " . $e->getMessage() . "')</script>";
    header("Refresh:1; url=renew_requests.php", true, 303);
}
?>
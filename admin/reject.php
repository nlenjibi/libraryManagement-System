<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$rollno = $_GET['id2'];

try {
    // Start transaction
    $conn->beginTransaction();
    
    // Delete the record request
    $sql = "DELETE FROM record WHERE RollNo = ? AND BookId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$rollno, $bookid]);
    
    if ($stmt->rowCount() == 0) {
        throw new Exception("No matching record found to reject");
    }
    
    // Insert rejection message
    $sql1 = "INSERT INTO message (RollNo, Message, Msg_Date) VALUES (?, ?, CURDATE())";
    $stmt1 = $conn->prepare($sql1);
    $msg = "Your request for issue of BookId: $bookid has been rejected";
    $stmt1->execute([$rollno, $msg]);
    
    // Commit transaction
    $conn->commit();
    
    echo "<script type='text/javascript'>alert('Request rejected successfully!')</script>";
    header("Refresh:0.01; url=issue_requests.php", true, 303);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script type='text/javascript'>alert('Error rejecting request: " . $e->getMessage() . "')</script>";
    header("Refresh:0.01; url=issue_requests.php", true, 303);
}
?>
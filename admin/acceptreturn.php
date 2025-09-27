<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$rollno = $_GET['id2'];
$dues = $_GET['id3'];

try {
    // Start transaction
    $conn->beginTransaction();

    // Get user category (though not used in current logic)
    $sql = "SELECT Category FROM user WHERE RollNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$rollno]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $category = $row ? $row['Category'] : '';

    // Update record with return date and status
    $sql1 = "UPDATE record SET ReturnDate = CURDATE(), Status = 'Returned' WHERE BookId = ? AND RollNo = ? AND Status = 'Issued'";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$bookid, $rollno]);

    if ($stmt1->rowCount() == 0) {
        throw new Exception("No matching issued record found to return");
    }

    // Increase book availability
    $sql3 = "UPDATE book SET Availability = Availability + 1 WHERE BookId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$bookid]);

    // Remove from return requests table
    $sql4 = "DELETE FROM return_req WHERE BookId = ? AND RollNo = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->execute([$bookid, $rollno]);

    // Remove any renewal requests for this book
    $sql6 = "DELETE FROM renew WHERE BookId = ? AND RollNo = ?";
    $stmt6 = $conn->prepare($sql6);
    $stmt6->execute([$bookid, $rollno]);

    // Insert success message
    $sql5 = "INSERT INTO message (RollNo, Message, Msg_Date) VALUES (?, ?, CURDATE())";
    $stmt5 = $conn->prepare($sql5);
    $msg = "Your request for return of BookId: $bookid has been accepted. Book returned successfully!";
    $stmt5->execute([$rollno, $msg]);

    // Commit transaction
    $conn->commit();

    echo "<script type='text/javascript'>
        alert('✅ SUCCESS: Book return request accepted successfully!\\n\\nBookId: $bookid\\nStudent: $rollno\\nReturn Date: " . date('Y-m-d') . "');
        setTimeout(function() { window.location.href = 'return_requests.php'; }, 2000);
    </script>";
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "<script type='text/javascript'>
        alert('❌ ERROR: Failed to process return request\\n\\nError: " . addslashes($e->getMessage()) . "\\n\\nPlease contact administrator.');
        setTimeout(function() { window.location.href = 'return_requests.php'; }, 3000);
    </script>";
    exit();
}
?>
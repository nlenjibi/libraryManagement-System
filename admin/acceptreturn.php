<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$rollno = $_GET['id2'];
$dues = $_GET['id3'];

try {
    // Start transaction
    $conn->beginTransaction();

    // Check if the return request exists and is in 'Requested' status
    $sql_check_request = "SELECT * FROM return_req WHERE BookId = ? AND RollNo = ? AND Status = 'Requested'";
    $stmt_check = $conn->prepare($sql_check_request);
    $stmt_check->execute([$bookid, $rollno]);
    $return_request = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$return_request) {
        throw new Exception("No valid return request found or request has already been processed");
    }

    // Check if the book is currently issued to this student
    $sql_check_issued = "SELECT * FROM record WHERE BookId = ? AND RollNo = ? AND Status = 'Issued'";
    $stmt_issued = $conn->prepare($sql_check_issued);
    $stmt_issued->execute([$bookid, $rollno]);
    $issued_record = $stmt_issued->fetch(PDO::FETCH_ASSOC);

    if (!$issued_record) {
        // Book might already be returned, clean up the orphaned return request
        $sql_cleanup = "DELETE FROM return_req WHERE BookId = ? AND RollNo = ?";
        $stmt_cleanup = $conn->prepare($sql_cleanup);
        $stmt_cleanup->execute([$bookid, $rollno]);
        throw new Exception("Book is not currently issued to this student. Orphaned return request has been cleaned up.");
    }

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
        throw new Exception("Failed to update record status");
    }

    // Increase book availability
    $sql3 = "UPDATE book SET Availability = Availability + 1 WHERE BookId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$bookid]);

    // Update return request status to 'Approved' instead of deleting
    $sql4 = "UPDATE return_req SET Status = 'Approved' WHERE BookId = ? AND RollNo = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->execute([$bookid, $rollno]);

    // Remove any renewal requests for this book
    $sql6 = "DELETE FROM renew WHERE BookId = ? AND RollNo = ?";
    $stmt6 = $conn->prepare($sql6);
    $stmt6->execute([$bookid, $rollno]);

    // Insert success message
    $sql5 = "INSERT INTO message (RollNo, Message, Msg_Date, Msg_Time) VALUES (?, ?, CURDATE(), CURTIME())";
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
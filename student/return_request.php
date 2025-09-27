<?php
require('dbconn.php');

$id = $_GET['id'];
$roll = $_SESSION['RollNo'];

try {
    // Check if return request already exists
    $check_sql = "SELECT id FROM return_req WHERE RollNo = ? AND BookId = ? AND Status = 'Requested'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([$roll, $id]);
    
    if ($check_stmt->fetch()) {
        echo "<script type='text/javascript'>alert('Return request already sent for this book.')</script>";
        header("Refresh:0.01; url=current.php", true, 303);
        exit();
    }
    
    // Check if the book is actually issued to this student
    $book_check = "SELECT id FROM record WHERE RollNo = ? AND BookId = ? AND Status = 'Issued'";
    $book_stmt = $conn->prepare($book_check);
    $book_stmt->execute([$roll, $id]);
    
    if (!$book_stmt->fetch()) {
        echo "<script type='text/javascript'>alert('This book is not currently issued to you.')</script>";
        header("Refresh:0.01; url=current.php", true, 303);
        exit();
    }
    
    // Insert return request
    $sql = "INSERT INTO return_req (RollNo, BookId, requested_date, Status) VALUES (?, ?, CURDATE(), 'Requested')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$roll, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script type='text/javascript'>alert('✅ Return request sent to admin successfully!')</script>";
        header("Refresh:0.01; url=current.php", true, 303);
    } else {
        echo "<script type='text/javascript'>alert('❌ Failed to send return request.')</script>";
        header("Refresh:0.01; url=current.php", true, 303);
    }
    
} catch (PDOException $e) {
    echo "<script type='text/javascript'>alert('❌ Error: " . addslashes($e->getMessage()) . "')</script>";
    header("Refresh:0.01; url=current.php", true, 303);
}
?>
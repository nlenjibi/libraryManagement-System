<?php
require('dbconn.php');

$id = $_GET['id'];
$roll = $_SESSION['RollNo'];

try {
    // Check if book is available
    $availability_check = "SELECT Availability FROM book WHERE BookId = ?";
    $avail_stmt = $conn->prepare($availability_check);
    $avail_stmt->execute([$id]);
    $book = $avail_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$book || $book['Availability'] <= 0) {
        echo "<script type='text/javascript'>alert('Sorry, this book is not available for issue.')</script>";
        header("Refresh:0.01; url=book.php", true, 303);
        exit();
    }
    
    // Check if student already has this book issued
    $existing_check = "SELECT id FROM record WHERE RollNo = ? AND BookId = ? AND Status = 'Issued'";
    $existing_stmt = $conn->prepare($existing_check);
    $existing_stmt->execute([$roll, $id]);
    
    if ($existing_stmt->fetch()) {
        echo "<script type='text/javascript'>alert('You already have this book issued.')</script>";
        header("Refresh:0.01; url=book.php", true, 303);
        exit();
    }
    
    // Check for pending issue request
    $pending_check = "SELECT id FROM record WHERE RollNo = ? AND BookId = ? AND Status = 'Requested'";
    $pending_stmt = $conn->prepare($pending_check);
    $pending_stmt->execute([$roll, $id]);
    
    if ($pending_stmt->fetch()) {
        echo "<script type='text/javascript'>alert('Issue request already sent for this book.')</script>";
        header("Refresh:0.01; url=book.php", true, 303);
        exit();
    }
    
    // Insert issue request
    $sql = "INSERT INTO record (RollNo, BookId, IssueDate, Status) VALUES (?, ?, CURDATE(), 'Requested')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$roll, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script type='text/javascript'>alert('✅ Issue request sent to admin successfully!')</script>";
        header("Refresh:0.01; url=book.php", true, 303);
    } else {
        echo "<script type='text/javascript'>alert('❌ Failed to send issue request.')</script>";
        header("Refresh:0.01; url=book.php", true, 303);
    }
    
} catch (PDOException $e) {
    echo "<script type='text/javascript'>alert('❌ Error: " . addslashes($e->getMessage()) . "')</script>";
    header("Refresh:0.01; url=book.php", true, 303);
}
?>
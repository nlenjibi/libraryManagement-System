<?php
// Add sample issue requests for testing
require('../dbconn.php');

echo "<h2>📝 Adding Sample Issue Requests</h2>";
echo "<hr>";

try {
    // Add some pending issue requests
    $pending_requests = [
        ['2021001', 11, '2025-09-27', '2025-10-27', 'Requested'],
        ['2021002', 12, '2025-09-27', '2025-10-27', 'Requested'],
        ['2021003', 13, '2025-09-27', '2025-10-27', 'Requested'],
    ];

    $sql = "INSERT INTO record (RollNo, BookId, IssueDate, ReturnDate, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $added = 0;

    foreach ($pending_requests as $request) {
        if ($stmt->execute($request)) {
            $added++;
            echo "<p style='color: green;'>✓ Added pending request: Student {$request[0]} for Book {$request[1]}</p>";
        }
    }

    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Added $added pending requests!</h3>";
    echo "<p>Now the Issue Requests page will show pending requests that can be approved or rejected.</p>";
    echo "<p><a href='admin/issue_requests.php' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>View Issue Requests</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
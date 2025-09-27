<!DOCTYPE html>
<html>

<head>
    <title>Test Accept/Return Functionality</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .test-link {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .test-link:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Test Accept/Return Functionality</h2>

    <h3>Current Records Status:</h3>
    <?php
    require('dbconn.php');

    // Show current records
    $stmt = $conn->prepare("SELECT r.*, b.Title, u.Name FROM record r 
                           JOIN book b ON r.BookId = b.BookId 
                           JOIN user u ON r.RollNo = u.RollNo 
                           ORDER BY r.id DESC LIMIT 10");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Book</th><th>Student</th><th>Status</th><th>Issue Date</th><th>Return Date</th><th>Actions</th></tr>";

    foreach ($records as $record) {
        echo "<tr>";
        echo "<td>" . $record['id'] . "</td>";
        echo "<td>" . $record['Title'] . "</td>";
        echo "<td>" . $record['Name'] . " (" . $record['RollNo'] . ")</td>";
        echo "<td>" . $record['Status'] . "</td>";
        echo "<td>" . ($record['IssueDate'] ?: 'Not set') . "</td>";
        echo "<td>" . ($record['ReturnDate'] ?: 'Not returned') . "</td>";
        echo "<td>";

        if ($record['Status'] == 'Requested') {
            echo "<a href='admin/accept.php?id1=" . $record['BookId'] . "&id2=" . $record['RollNo'] . "' class='test-link'>Accept Issue</a>";
        } elseif ($record['Status'] == 'Issued') {
            echo "<a href='admin/acceptreturn.php?id1=" . $record['BookId'] . "&id2=" . $record['RollNo'] . "&id3=0' class='test-link'>Accept Return</a>";
        }

        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Add a test request button
    echo "<br><br>";
    echo "<h3>Create Test Request:</h3>";
    echo "<a href='?create_test=1' class='test-link'>Create Test Issue Request</a>";

    if (isset($_GET['create_test'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO record (RollNo, BookId, Status) VALUES ('2021002', 2, 'Requested')");
            $stmt->execute();
            echo "<p>✅ Test request created! <a href='?'>Refresh page</a></p>";
        } catch (Exception $e) {
            echo "<p>❌ Error creating test request: " . $e->getMessage() . "</p>";
        }
    }
    ?>

</body>

</html>
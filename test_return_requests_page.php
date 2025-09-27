<?php
require('dbconn.php');

// Simple test page without authentication
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Requests Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            border-radius: 3px;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h2>Return Requests Test Page</h2>

    <table>
        <thead>
            <tr>
                <th>Student Roll No</th>
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Days Pending</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT rr.BookId, rr.RollNo, b.Title, rr.requested_date,
                   DATEDIFF(CURDATE(), rr.requested_date) as days_pending 
                   FROM return_req rr 
                   JOIN book b ON rr.BookId = b.BookId 
                   WHERE rr.Status = 'Requested' 
                   ORDER BY rr.requested_date";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>";
                echo "<em>No pending return requests at this time.</em>";
                echo "</td></tr>";
            } else {
                foreach ($results as $row) {
                    $bookid = $row['BookId'];
                    $rollno = $row['RollNo'];
                    $name = $row['Title'];
                    $dues = $row['days_pending'];
                    ?>
                    <tr>
                        <td><?php echo strtoupper($rollno) ?></td>
                        <td><?php echo $bookid ?></td>
                        <td><b><?php echo $name ?></b></td>
                        <td><?php
                        if ($dues > 0)
                            echo $dues;
                        else
                            echo 0; ?></td>
                        <td>
                            <a href="acceptreturn.php?id1=<?php echo $bookid; ?>&id2=<?php echo $rollno; ?>&id3=<?php echo $dues ?>"
                                class="btn">Accept Return</a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>

    <br>
    <h3>Debug Information</h3>
    <p><strong>Total return requests found:</strong> <?php echo count($results); ?></p>
    <p><strong>Database connection:</strong> <?php echo ($conn ? 'Connected' : 'Failed'); ?></p>

</body>

</html>
<?php
session_start();
require('dbconn.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['RollNo'])) {
    header('location:../index.php');
    exit();
}

// Check if user is admin
$rollno = $_SESSION['RollNo'];

try {
    // Test if connection and table exist
    $stmt = $conn->prepare("SELECT Type FROM user WHERE RollNo = :rollno");
    $stmt->bindParam(':rollno', $rollno);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['Type'] !== 'Admin') {
        echo "<script type='text/javascript'>alert('Access Denied! Admin privileges required.')</script>";
        header('location:../index.php');
        exit();
    }
} catch (PDOException $e) {
    // If database error occurs, show helpful message and redirect
    echo "<script type='text/javascript'>alert('Database error: " . addslashes($e->getMessage()) . "\\n\\nPlease run install.php to setup the database properly.');</script>";
    echo "<p>Database setup required. <a href='../install.php'>Click here to setup database</a></p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
        rel='stylesheet'>
</head>

<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <i class="icon-reorder shaded"></i></a><a class="brand" href="index.php">Dr Hilla Limann Technical
                    University Library Management System</a>
                <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav pull-right">
                        <li><a href="../index.php" class="btn btn-success" style="margin-right: 10px; margin-top: 5px;">
                                <i class="icon-home icon-white"></i> Library Home
                            </a></li>
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php
                                // Get user's profile picture
                                $sql_pic = "SELECT profile_picture FROM user WHERE RollNo = ?";
                                $stmt_pic = $conn->prepare($sql_pic);
                                $stmt_pic->execute([$rollno]);
                                $row_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC);
                                $nav_profile_pic = $row_pic['profile_picture'] ?: 'images/user.png';
                                ?>
                                <img src="<?php echo $nav_profile_pic; ?>" class="nav-avatar"
                                    style="border-radius: 50%; object-fit: cover;" />
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="profile.php">Your Profile</a></li>
                                <li><a href="change_password.php">Change Password</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.nav-collapse -->
            </div>
        </div>
        <!-- /navbar-inner -->
    </div>
    <!-- /navbar -->
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="span3">
                    <div class="sidebar">
                        <ul class="widget widget-menu unstyled">
                            <li class="active"><a href="index.php"><i class="menu-icon icon-home"></i>Dashboard
                                </a></li>
                            <li><a href="message.php"><i class="menu-icon icon-inbox"></i>Messages</a>
                            </li>
                            <li><a href="student.php"><i class="menu-icon icon-user"></i>Manage Students </a>
                            </li>
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                            <li><a href="addbook.php"><i class="menu-icon icon-edit"></i>Add Books </a></li>
                            <li><a href="requests.php"><i class="menu-icon icon-tasks"></i>Issue/Return Requests </a>
                            </li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Requests </a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books </a>
                            </li>
                            <li><a href="attendance_report.php"><i class="menu-icon icon-time"></i>Attendance Report
                                </a></li>
                        </ul>
                        <ul class="widget widget-menu unstyled">
                            <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                        </ul>
                    </div>
                    <!--/.sidebar-->
                </div>
                <!--/.span3-->

                <div class="span9">
                    <div class="content">
                        <div class="header">
                            <h1 class="page-title">Dashboard</h1>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row-fluid">
                            <?php
                            // Get statistics
                            try {
                                // Total Students
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM user WHERE Type = 'Student'");
                                $stmt->execute();
                                $total_students = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Total Books
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM book");
                                $stmt->execute();
                                $total_books = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Currently Issued Books (books with Status = 'Issued')
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM record WHERE Status = 'Issued'");
                                $stmt->execute();
                                $issued_books = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Available Books
                                $available_books = $total_books - $issued_books;

                                // Pending Return Requests
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM return_req WHERE Status = 'Requested'");
                                $stmt->execute();
                                $pending_requests = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Messages
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM message");
                                $stmt->execute();
                                $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                            } catch (PDOException $e) {
                                $total_students = $total_books = $issued_books = $available_books = $pending_requests = $total_messages = 0;
                            }
                            ?>

                            <!-- Students Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-user icon-3x" style="color: #3498db;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $total_students; ?></h3>
                                            <p>Total Students</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="student.php" class="btn btn-small btn-primary">View Students</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Books Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-book icon-3x" style="color: #2ecc71;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $total_books; ?></h3>
                                            <p>Total Books</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="book.php" class="btn btn-small btn-success">View Books</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Issued Books Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-list icon-3x" style="color: #f39c12;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $issued_books; ?></h3>
                                            <p>Currently Issued</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="current.php" class="btn btn-small btn-warning">View Issued</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row-fluid" style="margin-top: 20px;">
                            <!-- Available Books Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-ok-circle icon-3x" style="color: #27ae60;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo max(0, $available_books); ?></h3>
                                            <p>Available Books</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="addbook.php" class="btn btn-small btn-info">Add Books</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-tasks icon-3x" style="color: #e74c3c;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $pending_requests; ?></h3>
                                            <p>Pending Requests</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="requests.php" class="btn btn-small btn-danger">View Requests</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-envelope icon-3x" style="color: #9b59b6;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $total_messages; ?></h3>
                                            <p>Messages</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="message.php" class="btn btn-small"
                                            style="background-color: #9b59b6; color: white;">View Messages</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Section -->
                        <div class="row-fluid" style="margin-top: 30px;">
                            <div class="span12">
                                <div class="widget">
                                    <div class="widget-header">
                                        <i class="icon-time"></i>
                                        <h3>Recent Activity</h3>
                                    </div>
                                    <div class="widget-content">
                                        <?php
                                        try {
                                            // Get recent activity (last 5 records)
                                            $stmt = $conn->prepare("
                                                SELECT r.*, u.Name as student_name, b.Title as book_title, b.Author as book_author 
                                                FROM record r 
                                                LEFT JOIN user u ON r.RollNo = u.RollNo 
                                                LEFT JOIN book b ON r.BookId = b.BookId 
                                                ORDER BY r.IssueDate DESC 
                                                LIMIT 5
                                            ");
                                            $stmt->execute();
                                            $recent_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if (count($recent_records) > 0) {
                                                echo "<table class='table table-striped table-bordered'>";
                                                echo "<thead><tr><th>Student</th><th>Book</th><th>Status</th><th>Issue Date</th><th>Return Date</th></tr></thead>";
                                                echo "<tbody>";
                                                foreach ($recent_records as $record) {
                                                    $status_class = '';
                                                    $record_status = $record['Status'] ?? 'Unknown';
                                                    switch ($record_status) {
                                                        case 'Issued':
                                                            $status_class = 'label-warning';
                                                            break;
                                                        case 'Returned':
                                                            $status_class = 'label-success';
                                                            break;
                                                        case 'Overdue':
                                                            $status_class = 'label-important';
                                                            break;
                                                        default:
                                                            $status_class = 'label-default';
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($record['student_name'] ?? 'N/A') . "</td>";
                                                    echo "<td>" . htmlspecialchars($record['book_title'] ?? 'N/A') . " by " . htmlspecialchars($record['book_author'] ?? 'N/A') . "</td>";
                                                    echo "<td><span class='label " . $status_class . "'>" . htmlspecialchars($record_status) . "</span></td>";
                                                    echo "<td>" . date('M j, Y', strtotime($record['IssueDate'] ?? date('Y-m-d'))) . "</td>";
                                                    echo "<td>" . ($record['ReturnDate'] ? date('M j, Y', strtotime($record['ReturnDate'])) : '-') . "</td>";
                                                    echo "</tr>";
                                                }
                                                echo "</tbody></table>";
                                            } else {
                                                echo "<p class='text-muted'>No recent activity found.</p>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<p class='text-muted'>Unable to load recent activity.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/.span9-->
            </div>
        </div>
        <!--/.container-->
    </div>
    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2025 Library Management System </b>All rights reserved.
        </div>
    </div>

    <!--/.wrapper-->
    <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
    <script src="scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
    <script src="scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="scripts/common.js" type="text/javascript"></script>

</body>

</html>
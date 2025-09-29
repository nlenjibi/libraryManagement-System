<?php
require('dbconn.php');

// Check if user is logged in
if (!isset($_SESSION['RollNo'])) {
    header('location:../index.php');
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
                    University Library Management System </a>
                <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav pull-right">
                        <li><a href="../index.php" class="btn btn-success" style="margin-right: 10px; margin-top: 5px;">
                                <i class="icon-home icon-white"></i> Library Home
                            </a></li>
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php
                                // Get user's profile picture
                                $rollno = $_SESSION['RollNo'];
                                $sql_pic = "SELECT ProfilePic FROM user WHERE RollNo = ?";
                                $stmt_pic = $conn->prepare($sql_pic);
                                $stmt_pic->execute([$rollno]);
                                $row_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC);
                                $nav_profile_pic = $row_pic['ProfilePic'] ?: 'images/user.png';
                                ?>
                                <img src="<?php echo $nav_profile_pic; ?>" class="nav-avatar"
                                    style="border-radius: 50%; object-fit: cover; width: 24px; height: 24px;" />
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php">Your Profile</a></li>
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
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                            <li><a href="history.php"><i class="menu-icon icon-tasks"></i>Previously Borrowed Books </a>
                            </li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Requests </a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books </a>
                            </li>
                            <li><a href="attendance.php"><i class="menu-icon icon-time"></i>Library Attendance </a></li>
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
                            <h1 class="page-title">Student Dashboard</h1>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row-fluid">
                            <?php
                            // Get student statistics
                            $rollno = $_SESSION['RollNo'];
                            try {
                                // Currently Issued Books
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM record WHERE RollNo = ? AND Status = 'Issued'");
                                $stmt->execute([$rollno]);
                                $issued_books = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Messages
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM message WHERE RollNo = ?");
                                $stmt->execute([$rollno]);
                                $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // History (Returned Books)
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM record WHERE RollNo = ? AND Status = 'Returned'");
                                $stmt->execute([$rollno]);
                                $returned_books = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Book Requests
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM recommendations WHERE RollNo = ?");
                                $stmt->execute([$rollno]);
                                $book_requests = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Pending Return Requests
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM return_req WHERE RollNo = ? AND Status = 'Requested'");
                                $stmt->execute([$rollno]);
                                $pending_returns = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                                // Renewal Requests
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM renew WHERE RollNo = ?");
                                $stmt->execute([$rollno]);
                                $renewal_requests = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                            } catch (PDOException $e) {
                                $issued_books = $total_messages = $returned_books = $book_requests = $pending_returns = $renewal_requests = 0;
                            }
                            ?>

                            <!-- Currently Issued Books Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-book icon-3x" style="color: #e74c3c;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $issued_books; ?></h3>
                                            <p>Currently Issued</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="current.php" class="btn btn-small btn-danger">View Books</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-envelope icon-3x" style="color: #3498db;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $total_messages; ?></h3>
                                            <p>Messages</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="message.php" class="btn btn-small btn-primary">View Messages</a>
                                    </div>
                                </div>
                            </div>

                            <!-- History Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-time icon-3x" style="color: #2ecc71;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $returned_books; ?></h3>
                                            <p>Books Returned</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="history.php" class="btn btn-small btn-success">View History</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row-fluid" style="margin-top: 20px;">
                            <!-- Book Requests Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-list icon-3x" style="color: #f39c12;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $book_requests; ?></h3>
                                            <p>Books Requested</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="recommendations.php" class="btn btn-small btn-warning">View
                                            Requests</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Returns Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-share icon-3x" style="color: #9b59b6;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $pending_returns; ?></h3>
                                            <p>Pending Returns</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="current.php" class="btn btn-small"
                                            style="background-color: #9b59b6; color: white;">Check Status</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Renewals Card -->
                            <div class="span4">
                                <div class="stat-block">
                                    <div class="stat">
                                        <div class="stat-icon">
                                            <i class="icon-refresh icon-3x" style="color: #1abc9c;"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3><?php echo $renewal_requests; ?></h3>
                                            <p>Renewal Requests</p>
                                        </div>
                                    </div>
                                    <div class="stat-footer">
                                        <a href="current.php" class="btn btn-small"
                                            style="background-color: #1abc9c; color: white;">View Status</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Section -->
                        <div class="row-fluid" style="margin-top: 30px;">
                            <div class="span12">
                                <div class="widget">
                                    <div class="widget-header">
                                        <i class="icon-tasks"></i>
                                        <h3>Quick Actions</h3>
                                    </div>
                                    <div class="widget-content">
                                        <div class="row-fluid">
                                            <div class="span3">
                                                <a href="book.php" class="btn btn-large btn-block btn-info">
                                                    <i class="icon-search"></i><br>Browse Books
                                                </a>
                                            </div>
                                            <div class="span3">
                                                <a href="current.php" class="btn btn-large btn-block btn-danger">
                                                    <i class="icon-book"></i><br>My Books
                                                </a>
                                            </div>
                                            <div class="span3">
                                                <a href="recommendations.php"
                                                    class="btn btn-large btn-block btn-warning">
                                                    <i class="icon-list"></i><br>Request Books
                                                </a>
                                            </div>
                                            <div class="span3">
                                                <a href="profile.php" class="btn btn-large btn-block btn-success">
                                                    <i class="icon-user"></i><br>My Profile
                                                </a>
                                            </div>
                                        </div>
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
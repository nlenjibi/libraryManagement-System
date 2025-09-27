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
                    <center>
                        <div class="card" style="width: 50%;">
                            <img class="card-img-top" src="images/profile2.png" alt="Card image cap">
                            <div class="card-body">

                                <?php
                                $rollno = $_SESSION['RollNo'];
                                $sql = "SELECT * FROM user WHERE RollNo=:rollno";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':rollno', $rollno);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name = $row['Name'];
                                $category = $row['Category'];
                                $email = $row['EmailId'];
                                $mobno = $row['MobNo'];
                                ?>
                                <i>
                                    <h1 class="card-title">
                                        <center><?php echo $name ?></center>
                                    </h1>
                                    <br>
                                    <p><b>Email ID: </b><?php echo $email ?></p>
                                    <br>
                                    <p><b>Mobile number: </b><?php echo $mobno ?></p>
                                    </b>
                                </i>

                            </div>
                        </div>
                        <br>
                        <a href="edit_admin_details.php" class="btn btn-primary">Edit Details</a>
                    </center>
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
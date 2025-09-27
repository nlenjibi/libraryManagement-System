<?php
require('dbconn.php');

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
    <title>Change Password - LMS</title>
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
                    <i class="icon-reorder shaded"></i></a>
                <a class="brand" href="index.php">Dr Hilla Limann Technical University Library Management System</a>
                <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav pull-right">
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php">Your Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="span3">
                    <div class="sidebar">
                        <ul class="widget widget-menu unstyled">
                            <li><a href="index.php"><i class="menu-icon icon-home"></i>Home</a></li>
                            <li><a href="message.php"><i class="menu-icon icon-inbox"></i>Messages</a></li>
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books</a></li>
                            <li><a href="history.php"><i class="menu-icon icon-tasks"></i>Previously Borrowed Books</a>
                            </li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Requests</a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books</a></li>
                            <li><a href="attendance.php"><i class="menu-icon icon-time"></i>Library Attendance</a></li>
                        </ul>
                        <ul class="widget widget-menu unstyled">
                            <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>

                <div class="span9">
                    <div class="content">
                        <div class="module">
                            <div class="module-head">
                                <h3>Change Password</h3>
                            </div>
                            <div class="module-body">
                                <form class="form-horizontal row-fluid" method="post" action="">

                                    <div class="control-group">
                                        <label class="control-label" for="current_password"><b>Current
                                                Password:</b></label>
                                        <div class="controls">
                                            <input type="password" id="current_password" name="current_password"
                                                placeholder="Enter your current password" class="span6" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="new_password"><b>New Password:</b></label>
                                        <div class="controls">
                                            <input type="password" id="new_password" name="new_password"
                                                placeholder="Enter new password" class="span6" required>
                                            <p class="help-block">Password must be at least 6 characters long</p>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="confirm_password"><b>Confirm New
                                                Password:</b></label>
                                        <div class="controls">
                                            <input type="password" id="confirm_password" name="confirm_password"
                                                placeholder="Confirm new password" class="span6" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <button type="submit" name="change_password" class="btn btn-primary">
                                                <i class="icon-key icon-white"></i> Change Password
                                            </button>
                                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['change_password'])) {
                                    $rollno = $_SESSION['RollNo'];
                                    $current_password = $_POST['current_password'];
                                    $new_password = $_POST['new_password'];
                                    $confirm_password = $_POST['confirm_password'];

                                    // Validate inputs
                                    if (strlen($new_password) < 6) {
                                        echo "<div class='alert alert-error'>New password must be at least 6 characters long.</div>";
                                    } elseif ($new_password !== $confirm_password) {
                                        echo "<div class='alert alert-error'>New passwords do not match.</div>";
                                    } else {
                                        // Check current password
                                        $stmt = $conn->prepare("SELECT Password FROM user WHERE RollNo = :rollno");
                                        $stmt->bindParam(':rollno', $rollno);
                                        $stmt->execute();
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                        if ($user && $user['Password'] === $current_password) {
                                            // Update password
                                            $update_stmt = $conn->prepare("UPDATE user SET Password = :new_password WHERE RollNo = :rollno");
                                            $update_stmt->bindParam(':new_password', $new_password);
                                            $update_stmt->bindParam(':rollno', $rollno);

                                            if ($update_stmt->execute()) {
                                                echo "<div class='alert alert-success'>Password changed successfully!</div>";
                                                echo "<script>setTimeout(function(){ window.location.href='index.php'; }, 2000);</script>";
                                            } else {
                                                echo "<div class='alert alert-error'>Error updating password. Please try again.</div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-error'>Current password is incorrect.</div>";
                                        }
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2025 Library Management System </b>All rights reserved.
        </div>
    </div>

    <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <script>
        // Client-side password validation
        document.getElementById('confirm_password').addEventListener('blur', function () {
            var newPass = document.getElementById('new_password').value;
            var confirmPass = this.value;

            if (newPass !== confirmPass && confirmPass !== '') {
                this.style.borderColor = 'red';
                if (!document.getElementById('password-mismatch')) {
                    var warning = document.createElement('span');
                    warning.id = 'password-mismatch';
                    warning.style.color = 'red';
                    warning.style.fontSize = '12px';
                    warning.textContent = 'Passwords do not match';
                    this.parentElement.appendChild(warning);
                }
            } else {
                this.style.borderColor = '';
                var warning = document.getElementById('password-mismatch');
                if (warning) {
                    warning.remove();
                }
            }
        });
    </script>
</body>

</html>
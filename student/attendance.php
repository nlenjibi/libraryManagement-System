<?php
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header('location:../index.php');
    exit();
}

$rollno = $_SESSION['RollNo'];
$today = date('Y-m-d');

// Check if user has an open session (checked in but not checked out)
$stmt = $conn->prepare("SELECT * FROM attendance WHERE RollNo = :rollno AND session_status = 'open' ORDER BY checkin_time DESC LIMIT 1");
$stmt->bindParam(':rollno', $rollno);
$stmt->execute();
$open_session = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle check-in/check-out
if (isset($_POST['checkin']) && !$open_session) {
    $checkin_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO attendance (RollNo, checkin_time, attendance_date, session_status) VALUES (:rollno, :checkin_time, :today, 'open')");
    $stmt->bindParam(':rollno', $rollno);
    $stmt->bindParam(':checkin_time', $checkin_time);
    $stmt->bindParam(':today', $today);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['checkout']) && $open_session) {
    $checkout_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("UPDATE attendance SET checkout_time = :checkout_time, session_status = 'closed' WHERE id = :id");
    $stmt->bindParam(':checkout_time', $checkout_time);
    $stmt->bindParam(':id', $open_session['id']);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM user WHERE RollNo = :rollno");
$stmt->bindParam(':rollno', $rollno);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Attendance</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
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
                            <li><a href="history.php"><i class="menu-icon icon-tasks"></i>Previously Borrowed Books</a></li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Requests</a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books</a></li>
                            <li class="active"><a href="attendance.php"><i class="menu-icon icon-time"></i>Library Attendance</a></li>
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
                                <h3>Library Attendance - <?php echo date('F j, Y'); ?></h3>
                            </div>
                            <div class="module-body">
                                <div class="profile-details">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <h4>Welcome, <?php echo htmlspecialchars($user['Name']); ?>!</h4>
                                            <p><strong>Roll No:</strong> <?php echo htmlspecialchars($user['RollNo']); ?></p>
                                            <p><strong>Today's Date:</strong> <?php echo date('F j, Y'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="attendance-status">
                                    <?php if ($open_session): ?>
                                        <div class="alert alert-warning">
                                            <h4>You are currently checked in</h4>
                                            <p><strong>Check-in Time:</strong> <?php echo date('h:i A', strtotime($open_session['checkin_time'])); ?></p>
                                            <p><strong>Status:</strong> You are currently checked in. Please remember to check out when leaving.</p>
                                            <form method="post" style="margin-top: 15px;">
                                                <button type="submit" name="checkout" class="btn btn-danger btn-large" onclick="return confirm('Are you sure you want to check out?')">
                                                    <i class="icon-sign-out"></i> Check Out
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <?php
                                        // Check today's completed sessions
                                        $stmt = $conn->prepare("SELECT COUNT(*) as session_count FROM attendance WHERE RollNo = :rollno AND attendance_date = :today AND session_status = 'closed'");
                                        $stmt->bindParam(':rollno', $rollno);
                                        $stmt->bindParam(':today', $today);
                                        $stmt->execute();
                                        $completed_sessions = $stmt->fetch(PDO::FETCH_ASSOC)['session_count'];
                                        ?>
                                        <div class="alert alert-info">
                                            <h4>Ready to check in</h4>
                                            <?php if ($completed_sessions > 0): ?>
                                                <p>You have completed <?php echo $completed_sessions; ?> library session(s) today. You can check in again for a new visit.</p>
                                            <?php else: ?>
                                                <p>You have not checked in today. Please check in to record your library visit.</p>
                                            <?php endif; ?>
                                            <form method="post" style="margin-top: 15px;">
                                                <button type="submit" name="checkin" class="btn btn-success btn-large">
                                                    <i class="icon-sign-in"></i> Check In
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="attendance-history" style="margin-top: 30px;">
                                    <h4>Your Recent Attendance History</h4>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Check-in Time</th>
                                                <th>Check-out Time</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM attendance WHERE RollNo = :rollno ORDER BY attendance_date DESC LIMIT 10");
                                            $stmt->bindParam(':rollno', $rollno);
                                            $stmt->execute();
                                            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if (count($history) > 0) {
                                                foreach ($history as $record) {
                                                    echo "<tr>";
                                                    echo "<td>" . date('M j, Y', strtotime($record['attendance_date'])) . "</td>";
                                                    echo "<td>" . ($record['checkin_time'] ? date('h:i A', strtotime($record['checkin_time'])) : '-') . "</td>";
                                                    echo "<td>" . ($record['checkout_time'] ? date('h:i A', strtotime($record['checkout_time'])) : 'Still checked in') . "</td>";
                                                    
                                                    if ($record['checkin_time'] && $record['checkout_time']) {
                                                        $checkin = new DateTime($record['checkin_time']);
                                                        $checkout = new DateTime($record['checkout_time']);
                                                        $duration = $checkout->diff($checkin);
                                                        echo "<td>" . $duration->format('%h hours %i minutes') . "</td>";
                                                    } else {
                                                        echo "<td>-</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No attendance records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
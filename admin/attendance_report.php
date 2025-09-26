<?php
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header('location:../index.php');
    exit();
}

// Get selected date or use today's date
$selected_date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

// Get daily attendance statistics - count distinct students and sessions properly
$stmt = $conn->prepare("SELECT COUNT(DISTINCT RollNo) as unique_visitors FROM attendance WHERE attendance_date = :date");
$stmt->bindParam(':date', $selected_date);
$stmt->execute();
$unique_visitors = $stmt->fetch(PDO::FETCH_ASSOC)['unique_visitors'];

$stmt = $conn->prepare("SELECT COUNT(*) as total_sessions FROM attendance WHERE attendance_date = :date");
$stmt->bindParam(':date', $selected_date);
$stmt->execute();
$total_sessions = $stmt->fetch(PDO::FETCH_ASSOC)['total_sessions'];

$stmt = $conn->prepare("SELECT COUNT(*) as completed_sessions FROM attendance WHERE attendance_date = :date AND session_status = 'closed'");
$stmt->bindParam(':date', $selected_date);
$stmt->execute();
$completed_sessions = $stmt->fetch(PDO::FETCH_ASSOC)['completed_sessions'];

$stmt = $conn->prepare("SELECT COUNT(DISTINCT RollNo) as currently_present FROM attendance WHERE session_status = 'open'");
$stmt->execute();
$currently_present = $stmt->fetch(PDO::FETCH_ASSOC)['currently_present'];

// Get detailed attendance records for the selected date
$stmt = $conn->prepare("
    SELECT a.*, u.Name, u.RollNo 
    FROM attendance a 
    JOIN user u ON a.RollNo = u.RollNo 
    WHERE a.attendance_date = :date 
    ORDER BY a.checkin_time DESC
");
$stmt->bindParam(':date', $selected_date);
$stmt->execute();
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get weekly statistics - count distinct students per day
$week_start = date('Y-m-d', strtotime('-6 days', strtotime($selected_date)));
$stmt = $conn->prepare("
    SELECT attendance_date, COUNT(DISTINCT RollNo) as daily_count 
    FROM attendance 
    WHERE attendance_date BETWEEN :week_start AND :selected_date 
    GROUP BY attendance_date 
    ORDER BY attendance_date
");
$stmt->bindParam(':week_start', $week_start);
$stmt->bindParam(':selected_date', $selected_date);
$stmt->execute();
$weekly_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - LMS</title>
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
                            <li><a href="student.php"><i class="menu-icon icon-user"></i>Manage Students</a></li>
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books</a></li>
                            <li><a href="addbook.php"><i class="menu-icon icon-edit"></i>Add Books</a></li>
                            <li><a href="requests.php"><i class="menu-icon icon-tasks"></i>Issue/Return Requests</a></li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Requests</a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books</a></li>
                            <li class="active"><a href="attendance_report.php"><i class="menu-icon icon-time"></i>Attendance Report</a></li>
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
                                <h3>Library Attendance Report</h3>
                            </div>
                            <div class="module-body">
                                <form method="post" class="form-horizontal">
                                    <div class="control-group">
                                        <label class="control-label" for="date">Select Date:</label>
                                        <div class="controls">
                                            <input type="date" id="date" name="date" value="<?php echo $selected_date; ?>" class="input-medium">
                                            <button type="submit" class="btn btn-primary">View Report</button>
                                            <button type="button" class="btn btn-success" onclick="printReport()">Print Report</button>
                                        </div>
                                    </div>
                                </form>

                                <div id="reportContent">
                                    <div class="row-fluid" style="margin-top: 20px;">
                                        <div class="span12">
                                            <h4>Daily Statistics for <?php echo date('F j, Y', strtotime($selected_date)); ?></h4>
                                        </div>
                                    </div>

                                    <div class="row-fluid">
                                        <div class="span3">
                                            <div class="stat">
                                                <div class="stat-value"><?php echo $unique_visitors; ?></div>
                                                <div class="stat-label">Unique Visitors</div>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="stat">
                                                <div class="stat-value"><?php echo $total_sessions; ?></div>
                                                <div class="stat-label">Total Sessions</div>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="stat">
                                                <div class="stat-value"><?php echo $completed_sessions; ?></div>
                                                <div class="stat-label">Completed Sessions</div>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="stat">
                                                <div class="stat-value"><?php echo $currently_present; ?></div>
                                                <div class="stat-label">Currently Present</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row-fluid" style="margin-top: 30px;">
                                        <div class="span12">
                                            <h4>Weekly Attendance Trend</h4>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Total Visitors</th>
                                                        <th>Day</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($weekly_stats as $day): ?>
                                                    <tr>
                                                        <td><?php echo date('M j, Y', strtotime($day['attendance_date'])); ?></td>
                                                        <td><?php echo $day['daily_count']; ?></td>
                                                        <td><?php echo date('l', strtotime($day['attendance_date'])); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row-fluid" style="margin-top: 30px;">
                                        <div class="span12">
                                            <h4>Detailed Attendance Records - <?php echo date('F j, Y', strtotime($selected_date)); ?></h4>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Student Name</th>
                                                        <th>Roll Number</th>
                                                        <th>Check-in Time</th>
                                                        <th>Check-out Time</th>
                                                        <th>Duration</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($attendance_records) > 0): ?>
                                                        <?php foreach ($attendance_records as $record): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($record['Name']); ?></td>
                                                            <td><?php echo htmlspecialchars($record['RollNo']); ?></td>
                                                            <td><?php echo $record['checkin_time'] ? date('h:i A', strtotime($record['checkin_time'])) : '-'; ?></td>
                                                            <td><?php echo $record['checkout_time'] ? date('h:i A', strtotime($record['checkout_time'])) : 'Still Present'; ?></td>
                                                            <td>
                                                                <?php 
                                                                if ($record['checkin_time'] && $record['checkout_time']) {
                                                                    $checkin = new DateTime($record['checkin_time']);
                                                                    $checkout = new DateTime($record['checkout_time']);
                                                                    $duration = $checkout->diff($checkin);
                                                                    echo $duration->format('%h hours %i minutes');
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($record['checkout_time']): ?>
                                                                    <span class="badge badge-success">Completed</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-warning">Present</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No attendance records found for this date</td>
                                                        </tr>
                                                    <?php endif; ?>
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
        </div>
    </div>

    <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    
    <script>
        function printReport() {
            var printContent = document.getElementById('reportContent').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = '<h2>Library Attendance Report</h2>' + printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }
    </script>

    <style>
        .stat {
            text-align: center;
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #2c5aa0;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        @media print {
            .navbar, .sidebar, .control-group, .btn { display: none !important; }
            .span9 { width: 100% !important; }
        }
    </style>
</body>
</html>
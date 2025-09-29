<?php
session_start();
require('dbconn.php');
?>

<?php
if ($_SESSION['RollNo']) {
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
                            <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="images/user.png" class="nav-avatar" />
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php">Your Profile</a></li>
                                    <!--li><a href="#">Edit Profile</a></li>
                                    <li><a href="#">Account Settings</a></li-->
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
                                <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Books Requested
                                    </a></li>
                                <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books </a>
                                </li>
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

                            <!-- Recent Messages Table -->
                            <div class="module">
                                <div class="module-head">
                                    <h3>Recent Messages</h3>
                                </div>
                                <div class="module-body table">
                                    <?php
                                    try {
                                        $sql = "SELECT m.*, u.Name as ReceiverName 
                                               FROM message m 
                                               LEFT JOIN user u ON m.RollNo = u.RollNo 
                                               ORDER BY m.Msg_Date DESC, m.Msg_Time DESC 
                                               LIMIT 10";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (!empty($messages)) {
                                            echo "<table class='table table-striped table-bordered table-condensed'>";
                                            echo "<thead>";
                                            echo "<tr>";
                                            echo "<th>Receiver</th>";
                                            echo "<th>Message</th>";
                                            echo "<th>Date</th>";
                                            echo "<th>Time</th>";
                                            echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";

                                            foreach ($messages as $msg) {
                                                echo "<tr>";
                                                echo "<td><strong>" . htmlspecialchars($msg['RollNo']) . "</strong><br>";
                                                echo "<small>" . htmlspecialchars($msg['ReceiverName'] ?: 'Unknown User') . "</small></td>";
                                                echo "<td>" . htmlspecialchars($msg['Message']) . "</td>";
                                                echo "<td>" . date('M j, Y', strtotime($msg['Msg_Date'])) . "</td>";
                                                echo "<td>" . ($msg['Msg_Time'] ? date('g:i A', strtotime($msg['Msg_Time'])) : '-') . "</td>";
                                                echo "</tr>";
                                            }

                                            echo "</tbody>";
                                            echo "</table>";
                                        } else {
                                            echo "<p class='text-muted text-center'>No messages found.</p>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "<p class='text-danger'>Error loading messages: " . htmlspecialchars($e->getMessage()) . "</p>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Send Message Form -->
                            <div class="module">
                                <div class="module-head">
                                    <h3>Send a Message</h3>
                                </div>
                                <div class="module-body">

                                    <br>

                                    <form class="form-horizontal row-fluid" action="message.php" method="post">
                                        <div class="control-group">
                                            <label class="control-label" for="Rollno"><b>Receiver ID:</b></label>
                                            <div class="controls">
                                                <input type="text" id="RollNo" name="RollNo"
                                                    placeholder="Enter Student Roll Number" class="span8" required>
                                                <span class="help-block">Enter the student's roll number to send
                                                    message</span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="Message"><b>Message:</b></label>
                                            <div class="controls">
                                                <textarea id="Message" name="Message"
                                                    placeholder="Enter your message here..." class="span8" rows="4"
                                                    required></textarea>
                                                <span class="help-block">Maximum 500 characters</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="control-group">
                                            <div class="controls">
                                                <button type="submit" name="submit" class="btn btn-primary">
                                                    <i class="icon-envelope icon-white"></i> Send Message
                                                </button>
                                                <button type="reset" class="btn">Clear</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div><!--/.content-->
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

        <?php
        if (isset($_POST['submit'])) {
            try {
                $rollno = $_POST['RollNo'];
                $message = $_POST['Message'];

                // Check if user exists
                $user_check = "SELECT RollNo FROM user WHERE RollNo = ?";
                $user_stmt = $conn->prepare($user_check);
                $user_stmt->execute([$rollno]);

                if (!$user_stmt->fetch()) {
                    throw new Exception("User with RollNo '$rollno' not found");
                }

                $sql1 = "INSERT INTO message (RollNo, Message, Msg_Date, Msg_Time) VALUES (?, ?, CURDATE(), CURTIME())";
                $stmt = $conn->prepare($sql1);
                $stmt->execute([$rollno, $message]);

                if ($stmt->rowCount() > 0) {
                    echo "<script type='text/javascript'>
                        alert('✅ SUCCESS: Message sent successfully!\\n\\nReceiver: $rollno\\nMessage: " . addslashes($message) . "\\nDate: " . date('Y-m-d') . "\\nTime: " . date('H:i:s') . "');
                        setTimeout(function() { 
                            document.getElementById('RollNo').value = '';
                            document.getElementById('Message').value = '';
                            window.location.reload();
                        }, 2000);
                    </script>";
                } else {
                    echo "<script type='text/javascript'>alert('❌ Failed to send message - no rows inserted')</script>";
                }
            } catch (Exception $e) {
                echo "<script type='text/javascript'>alert('❌ Error sending message: " . addslashes($e->getMessage()) . "')</script>";
            }
        }
        ?>
    </body>

    </html>


<?php } else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
} ?>
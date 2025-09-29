<?php
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
                                <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                                <li><a href="history.php"><i class="menu-icon icon-tasks"></i>Previously Borrowed Books </a>
                                </li>
                                <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Books Requested </a>
                                </li>
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
                        <form class="form-horizontal row-fluid" action="history.php" method="post">
                            <div class="control-group">
                                <label class="control-label" for="Search"><b>Search:</b></label>
                                <div class="controls">
                                    <input type="text" id="title" name="title" placeholder="Enter Book Name/Book Id."
                                        class="span8" required>
                                    <button type="submit" name="submit" class="btn">Search</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <?php
                        $rollno = $_SESSION['RollNo'];
                        try {
                            if (isset($_POST['submit'])) {
                                $s = $_POST['title'];
                                $sql = "SELECT r.*, b.* FROM record r 
                                       JOIN book b ON r.BookId = b.BookId 
                                       WHERE r.RollNo = ? AND r.IssueDate IS NOT NULL AND r.ReturnDate IS NOT NULL 
                                       AND r.Status = 'Returned' AND (r.BookId = ? OR b.Title LIKE ?)";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([$rollno, $s, "%$s%"]);
                            } else {
                                $sql = "SELECT r.*, b.* FROM record r 
                                       JOIN book b ON r.BookId = b.BookId 
                                       WHERE r.RollNo = ? AND r.IssueDate IS NOT NULL AND r.ReturnDate IS NOT NULL 
                                       AND r.Status = 'Returned'";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([$rollno]);
                            }

                            $history_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $rowcount = count($history_records);

                            if (!$rowcount) {
                                echo "<br><center><h2><b><i>No books have been borrowed previously</i></b></h2></center>";
                            } else {
                                ?>
                                <table class="table" id="tables">
                                    <thead>
                                        <tr>
                                            <th>Book ID</th>
                                            <th>Book name</th>
                                            <th>Issue Date</th>
                                            <th>Return Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_records as $row) {
                                            $bookid = $row['BookId'];
                                            $name = $row['Title'];
                                            $issuedate = $row['IssueDate'];
                                            $returndate = $row['ReturnDate'];
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($bookid) ?></td>
                                                <td><?php echo htmlspecialchars($name) ?></td>
                                                <td><?php echo date('M j, Y', strtotime($issuedate)) ?></td>
                                                <td><?php echo date('M j, Y', strtotime($returndate)) ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                        } catch (PDOException $e) {
                            echo "<br><center><h2><b><i>Error loading history: " . htmlspecialchars($e->getMessage()) . "</i></b></h2></center>";
                        }
                        ?>
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

<?php } else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
} ?>
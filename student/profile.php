<?php
require('dbconn.php');

// Check if user is logged in
if (!isset($_SESSION['RollNo'])) {
    header('location:../index.php');
    exit();
}

$rollno = $_SESSION['RollNo'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - My Profile</title>
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
                                $sql_pic = "SELECT ProfilePic FROM user WHERE RollNo = ?";
                                $stmt_pic = $conn->prepare($sql_pic);
                                $stmt_pic->execute([$rollno]);
                                $row_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC);
                                $nav_profile_pic = $row_pic['ProfilePic'] ?: 'images/user.png';
                                ?>
                                <img src="<?php echo $nav_profile_pic; ?>" class="nav-avatar"
                                    style="border-radius: 50%; object-fit: cover;" />
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php">Dashboard</a></li>
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
                            <li><a href="index.php"><i class="menu-icon icon-home"></i>Dashboard</a></li>
                            <li><a href="message.php"><i class="menu-icon icon-inbox"></i>Messages</a></li>
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books</a></li>
                            <li><a href="history.php"><i class="menu-icon icon-time"></i>Previously Issued Books</a>
                            </li>
                            <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Books Requested </a>
                            </li>
                            <li class="active"><a href="profile.php"><i class="menu-icon icon-user"></i>My Profile</a>
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
                    <div class="module">
                        <div class="module-head">
                            <h3>My Profile</h3>
                        </div>
                        <div class="module-body">

                            <?php
                            $rollno = $_SESSION['RollNo'];

                            // Get user details
                            $sql = "SELECT * FROM user WHERE RollNo = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$rollno]);
                            $row = $stmt->fetch();

                            if ($row) {
                                $name = $row['Name'];
                                $category = $row['Category'];
                                $email = $row['EmailId'];
                                $mobno = $row['MobNo'];
                                $pswd = $row['Password'];

                                // Check for profile picture
                                $profile_pic = 'images/profile.png'; // default
                                if (isset($row['ProfilePic']) && !empty($row['ProfilePic']) && file_exists($row['ProfilePic'])) {
                                    $profile_pic = $row['ProfilePic'];
                                }
                                ?>

                                <!-- Profile Photo Section -->
                                <div class="profile-photo-section"
                                    style="text-align: center; margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
                                    <h4 style="margin-bottom: 15px; color: #333;">Profile Photo</h4>
                                    <img id="profile-preview" src="<?php echo $profile_pic; ?>" alt="Profile Photo"
                                        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

                                    <form id="photo-form" action="profile.php" method="post" enctype="multipart/form-data"
                                        style="margin-top: 15px;">
                                        <div class="control-group">
                                            <label for="profile_photo" class="btn btn-info"
                                                style="cursor: pointer; margin: 5px;">
                                                📸 Choose New Photo
                                            </label>
                                            <input type="file" id="profile_photo" name="profile_photo"
                                                accept="image/jpeg,image/png,image/gif" style="display: none;"
                                                onchange="previewImage(this)">
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <button type="submit" name="upload_photo" class="btn btn-primary">📷 Update
                                                Photo</button>
                                        </div>
                                        <small style="color: #666; display: block; margin-top: 5px;">Supported: JPG, PNG,
                                            GIF (Max 5MB)</small>
                                    </form>
                                </div>

                                <script>
                                    function previewImage(input) {
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = function (e) {
                                                document.getElementById('profile-preview').src = e.target.result;
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                </script>

                                <form class="form-horizontal row-fluid" action="profile.php" method="post">

                                    <div class="control-group">
                                        <label class="control-label" for="Name"><b>Name:</b></label>
                                        <div class="controls">
                                            <input type="text" id="Name" name="Name" value="<?php echo $name ?>"
                                                class="span8" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="Category"><b>Category:</b></label>
                                        <div class="controls">
                                            <select name="Category" tabindex="1" value="SC"
                                                data-placeholder="Select Category" class="span6">
                                                <option value="<?php echo $category ?>"><?php echo $category ?> </option>
                                                <option value="GEN">GEN</option>
                                                <option value="OBC">OBC</option>
                                                <option value="SC">SC</option>
                                                <option value="ST">ST</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="control-group">
                                        <label class="control-label" for="EmailId"><b>Email Id:</b></label>
                                        <div class="controls">
                                            <input type="text" id="EmailId" name="EmailId" value="<?php echo $email ?>"
                                                class="span8" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="MobNo"><b>Mobile Number:</b></label>
                                        <div class="controls">
                                            <input type="text" id="MobNo" name="MobNo" value="<?php echo $mobno ?>"
                                                class="span8" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="Password"><b>New Password:</b></label>
                                        <div class="controls">
                                            <input type="password" id="Password" name="Password" value="<?php echo $pswd ?>"
                                                class="span8" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <button type="submit" name="submit" class="btn-primary">
                                                <center>Update Details</center>
                                            </button>
                                        </div>
                                    </div>

                                </form>

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

        <?php
        // Handle profile photo upload
        if (isset($_POST['upload_photo'])) {
            $rollno = $_SESSION['RollNo'];

            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
                $upload_dir = 'uploads/profiles/';

                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $file_tmp = $_FILES['profile_photo']['tmp_name'];
                $file_name = $_FILES['profile_photo']['name'];
                $file_size = $_FILES['profile_photo']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
    
                if (in_array($file_ext, $allowed_ext) && $file_size <= $max_size) {
                    $new_name = 'student_' . $rollno . '_' . time() . '.' . $file_ext;
                    $upload_path = $upload_dir . $new_name;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        // Update database with new profile picture path
                        $sql = "UPDATE user SET ProfilePic = ? WHERE RollNo = ?";
                        $stmt = $conn->prepare($sql);

                        if ($stmt->execute([$upload_path, $rollno])) {
                            echo "<script>alert('Profile photo updated successfully!'); window.location.href='profile.php';</script>";
                        } else {
                            echo "<script>alert('Error updating profile photo in database.');</script>";
                        }
                    } else {
                        echo "<script>alert('Error uploading file.');</script>";
                    }
                } else {
                    echo "<script>alert('Invalid file. Please upload JPG, PNG, or GIF under 5MB.');</script>";
                }
            }
        }

        // Handle profile details update
        if (isset($_POST['submit'])) {
            $rollno = $_SESSION['RollNo'];
            $name = $_POST['Name'];
            $category = $_POST['Category'];
            $email = $_POST['EmailId'];
            $mobno = $_POST['MobNo'];
            $pswd = $_POST['Password'];

            $sql = "UPDATE user SET Name = ?, Category = ?, EmailId = ?, MobNo = ?, Password = ? WHERE RollNo = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute([$name, $category, $email, $mobno, $pswd, $rollno])) {
                echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
            } else {
                echo "<script>alert('Error updating profile.');</script>";
            }
        }

                            } // Close the if ($row) block
                            ?>

</body>

</html>
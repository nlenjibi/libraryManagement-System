<?php
ob_start();
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
        <title>LMS - Admin Profile</title>
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
                                    $rollno = $_SESSION['RollNo'];
                                    $sql_pic = "SELECT ProfilePic FROM user WHERE RollNo = ?";
                                    $stmt_pic = $conn->prepare($sql_pic);
                                    $stmt_pic->execute([$rollno]);
                                    $row_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC);
                                    $nav_profile_pic = $row_pic['ProfilePic'] ?: 'images/user.png';
                                    ?>
                                    <img src="<?php echo $nav_profile_pic; ?>" class="nav-avatar" />
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="profile.php">Your Profile</a></li>
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
                                <li><a href="index.php"><i class="menu-icon icon-home"></i>Dashboard</a></li>
                                <li><a href="message.php"><i class="menu-icon icon-inbox"></i>Messages</a></li>
                                <li><a href="student.php"><i class="menu-icon icon-user"></i>Manage Students </a></li>
                                <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                                <li><a href="addbook.php"><i class="menu-icon icon-edit"></i>Add Books </a></li>
                                <li><a href="requests.php"><i class="menu-icon icon-tasks"></i>Issue/Return Requests </a></li>
                                <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Books Requested</a></li>
                                <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books </a></li>
                            </ul>
                            <ul class="widget widget-menu unstyled">
                                <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="span9">
                        <div class="module">
                            <div class="module-head">
                                <h3>Admin Profile</h3>
                            </div>
                            <div class="module-body">

                                <?php
                                $rollno = $_SESSION['RollNo'];
                                $sql = "SELECT * FROM user WHERE RollNo = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([$rollno]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name = $row['Name'];
                                $email = $row['EmailId'];
                                $mobno = $row['MobNo'];
                                $pswd = $row['Password'];
                                $profile_pic = $row['ProfilePic'] ?: 'images/user.png';
                                ?>

                                <!-- Profile Photo Section -->
                                <div class="profile-photo-section" style="text-align: center; margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
                                    <h4 style="margin-bottom: 15px; color: #333;">Profile Photo</h4>
                                    <img id="profile-preview" src="<?php echo $profile_pic; ?>" alt="Profile Photo" 
                                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                    
                                    <form id="photo-form" action="profile.php" method="post" enctype="multipart/form-data" style="margin-top: 15px;">
                                        <div class="control-group">
                                            <label for="profile_photo" class="btn btn-info" style="cursor: pointer; margin: 5px;">
                                                📸 Choose New Photo
                                            </label>
                                            <input type="file" id="profile_photo" name="profile_photo" 
                                                   accept="image/jpeg,image/png,image/gif" style="display: none;" 
                                                   onchange="previewImage(this)">
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <button type="submit" name="upload_photo" class="btn btn-primary">📷 Update Photo</button>
                                        </div>
                                        <small style="color: #666; display: block; margin-top: 5px;">Supported: JPG, PNG, GIF (Max 5MB)</small>
                                    </form>
                                </div>

                                <script>
                                function previewImage(input) {
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            document.getElementById('profile-preview').src = e.target.result;
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                                </script>

                                <!-- Profile Details Section -->
                                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 20px; background: #fff;">
                                    <h4 style="margin-bottom: 20px; color: #333;">Update Profile Details</h4>
                                    
                                    <form class="form-horizontal row-fluid" action="profile.php" method="post">

                                        <div class="control-group">
                                            <label class="control-label" for="Name"><b>Name:</b></label>
                                            <div class="controls">
                                                <input type="text" id="Name" name="Name" value="<?php echo $name ?>"
                                                    class="span8" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="EmailId"><b>Email Id:</b></label>
                                            <div class="controls">
                                                <input type="email" id="EmailId" name="EmailId" value="<?php echo $email ?>"
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
                                                <button type="submit" name="submit" class="btn btn-success btn-large">
                                                    ✏️ Update Profile Details
                                                </button>
                                            </div>
                                        </div>

                                    </form>
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
        <script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="scripts/common.js" type="text/javascript"></script>

        <?php
        // Handle profile photo upload
        if (isset($_POST['upload_photo'])) {
            try {
                $rollno = $_SESSION['RollNo'];
                
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['profile_photo']['name'];
                    $filetype = $_FILES['profile_photo']['type'];
                    $filesize = $_FILES['profile_photo']['size'];
                    
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (!in_array($ext, $allowed)) {
                        throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed");
                    }
                    
                    if ($filesize > 5000000) { // 5MB max
                        throw new Exception("File size must be less than 5MB");
                    }
                    
                    // Create unique filename
                    $newname = 'profile_' . $rollno . '_' . time() . '.' . $ext;
                    $upload_path = 'uploads/' . $newname;
                    
                    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                        // Update database
                        $sql = "UPDATE user SET ProfilePic = ? WHERE RollNo = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$upload_path, $rollno]);
                        
                        echo "<script type='text/javascript'>
                            alert('✅ Profile photo updated successfully!');
                            setTimeout(function() { window.location.reload(); }, 1000);
                        </script>";
                    } else {
                        throw new Exception("Failed to upload file");
                    }
                } else {
                    throw new Exception("Please select a file to upload");
                }
            } catch (Exception $e) {
                echo "<script type='text/javascript'>alert('❌ Error: " . addslashes($e->getMessage()) . "');</script>";
            }
        }

        // Handle profile details update
        if (isset($_POST['submit'])) {
            try {
                $rollno = $_SESSION['RollNo'];
                $name = $_POST['Name'];
                $email = $_POST['EmailId'];
                $mobno = $_POST['MobNo'];
                $pswd = $_POST['Password'];

                $sql1 = "UPDATE user SET Name = ?, EmailId = ?, MobNo = ?, Password = ? WHERE RollNo = ?";
                $stmt = $conn->prepare($sql1);
                $stmt->execute([$name, $email, $mobno, $pswd, $rollno]);

                if ($stmt->rowCount() > 0) {
                    echo "<script type='text/javascript'>
                        alert('✅ Profile updated successfully!');
                        setTimeout(function() { window.location.href = 'index.php'; }, 1500);
                    </script>";
                } else {
                    echo "<script type='text/javascript'>alert('No changes made or user not found')</script>";
                }
            } catch (Exception $e) {
                echo "<script type='text/javascript'>alert('❌ Error updating profile: " . addslashes($e->getMessage()) . "')</script>";
            }
        }
        ?>

    </body>
    </html>

<?php } else {
    echo "<script type='text/javascript'>alert('Access Denied!!!');</script>";
    header('location: ../index.php');
} ?>
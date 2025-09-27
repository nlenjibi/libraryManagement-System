<?php
require('dbconn.php');
?>


<!DOCTYPE html>
<html>

<!-- Head -->

<head>

        <title>Library Management System </title>

        <!-- Meta-Tags -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="keywords"
                content="Library Member Login Form Widget Responsive, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design" />
        <script
                type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- //Meta-Tags -->

        <!-- Style -->
        <link rel="stylesheet" href="css/style.css" type="text/css" media="all">

        <!-- Fonts -->
        <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <!-- //Fonts -->

</head>
<!-- //Head -->

<!-- Body -->

<body>

        <h1>LIBRARY MANAGEMENT SYSTEM</h1>

        <div class="container">

                <div class="login">
                        <h2>Sign In</h2>
                        <form action="index.php" method="post">
                                <input type="text" Name="RollNo" placeholder="RollNo" required="">
                                <input type="password" Name="Password" placeholder="Password" required="">


                                <div class="send-button">
                                        <!--<form>-->
                                        <input type="submit" name="signin" ; value="Sign In">
                        </form>
                </div>

                <div class="clear"></div>
        </div>

        <div class="register">
                <h2>Sign Up</h2>
                <form action="index.php" method="post">
                        <input type="text" Name="Name" placeholder="Name" required>
                        <input type="text" Name="Email" placeholder="Email" required>
                        <input type="password" Name="Password" placeholder="Password" required>
                        <input type="text" Name="PhoneNumber" placeholder="Phone Number" required>
                        <input type="text" Name="RollNo" placeholder="Roll Number" required="">

                        <select name="Category" id="Category" ">
                                <option style="color: blue;"value="GEN">General</option>
                                <option style="color: blue;"value="OBC">OBC</option>
                                <option style="color: blue;" value="SC">SC</option>
                                <option style="color: blue;" value="ST">ST</option>
                        </select>
                        <br>


                        <br>
                        <div class="send-button">
                                <input type="submit" name="signup" value="Sign Up">
                </form>
        </div>
        <p>By creating an account, you agree to our <a class="underline" href="terms.html">Terms</a></p>
        <div class="clear"></div>
        </div>

        <div class="clear"></div>

        </div>

        <div class="footer w3layouts agileits">
                <p> &copy; 2025 Library Member Login. All Rights Reserved </a></p>

        </div>

        <?php
        if (isset($_POST['signin'])) {
                $u = $_POST['RollNo'];
                $p = $_POST['Password'];
               

                $sql = "SELECT * FROM user WHERE RollNo=:rollno";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':rollno', $u);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $x = $row ? $row['Password'] : '';
                $y = $row ? $row['Type'] : '';
                if (strcasecmp($x, $p) == 0 && !empty($u) && !empty($p)) {//echo "Login Successful";
                        $_SESSION['RollNo'] = $u;


                        if ($y == 'Admin')
                                header('location:admin/index.php');
                        else
                                header('location:student/index.php');

                } else {
                        echo "<script type='text/javascript'>alert('Login Failed! Please check your Roll Number and Password')</script>";
                }


        }

        if (isset($_POST['signup'])) {
                $name = $_POST['Name'];
                $email = $_POST['Email'];
                $password = $_POST['Password'];
                $mobno = $_POST['PhoneNumber'];
                $rollno = $_POST['RollNo'];
                $category = $_POST['Category'];
                $type = 'Student';

                $sql = "INSERT INTO user (Name,Type,Category,RollNo,EmailId,MobNo,Password) VALUES (:name,:type,:category,:rollno,:email,:mobno,:password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':rollno', $rollno);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mobno', $mobno);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                        echo "<script type='text/javascript'>alert('Registration Successful')</script>";
                } else {
                        //echo "Error: " . $sql . "<br>" . $conn->error;
                        echo "<script type='text/javascript'>alert('Registration failed! A user with this Roll Number already exists')</script>";
                }
        }

        ?>

</body>
<!-- //Body -->

</html>
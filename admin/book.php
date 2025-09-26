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
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.php">Dr Hilla Limann Technical University Library Management System </a>
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
                                <li class="active"><a href="index.php"><i class="menu-icon icon-home"></i>Home
                                </a></li>
                                 <li><a href="message.php"><i class="menu-icon icon-inbox"></i>Messages</a>
                                </li>
                                <li><a href="student.php"><i class="menu-icon icon-user"></i>Manage Students </a>
                                </li>
                                <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books </a></li>
                                <li><a href="addbook.php"><i class="menu-icon icon-edit"></i>Add Books </a></li>
                                <li><a href="requests.php"><i class="menu-icon icon-tasks"></i>Issue/Return Requests </a></li>
                                <li><a href="recommendations.php"><i class="menu-icon icon-list"></i>Book Recommendations </a></li>
                                <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books </a></li>
                            </ul>
                            <ul class="widget widget-menu unstyled">
                                <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>

                    <div class="span9">
                  <form class="form-horizontal row-fluid" action="book.php" method="post">
                                        <div class="control-group">
                                            <label class="control-label" for="Search"><b>Search:</b></label>
                                            <div class="controls">
                                                <input type="text" id="title" name="title" placeholder="Enter Name/ID of Book" class="span8" required>
                                                <button type="submit" name="submit"class="btn">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <br>
                                    <?php
                                    // Handle book deletion
                                    if(isset($_POST['delete_book'])) {
                                        $book_id = $_POST['book_id'];
                                        
                                        // Check if book is currently issued
                                        $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM record WHERE BookId = :book_id AND Status = 'Issued'");
                                        $check_stmt->bindParam(':book_id', $book_id);
                                        $check_stmt->execute();
                                        $issued_count = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'];
                                        
                                        if($issued_count > 0) {
                                            echo "<script>alert('Cannot delete book. It is currently issued to students.');</script>";
                                        } else {
                                            // Delete the book
                                            $delete_stmt = $conn->prepare("DELETE FROM book WHERE BookId = :book_id");
                                            $delete_stmt->bindParam(':book_id', $book_id);
                                            
                                            if($delete_stmt->execute()) {
                                                echo "<script>alert('Book deleted successfully!'); window.location.href='book.php';</script>";
                                            } else {
                                                echo "<script>alert('Error deleting book. Please try again.');</script>";
                                            }
                                        }
                                    }
                                    
                                    if(isset($_POST['submit']))
                                        {$s=$_POST['title'];
                                            $sql="SELECT * FROM book WHERE BookId=:search OR Title LIKE :title_search";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':search', $s);
                                            $title_search = "%$s%";
                                            $stmt->bindParam(':title_search', $title_search);
                                            $stmt->execute();
                                            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        }
                                    else {
                                        $sql="SELECT * FROM book";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    $rowcount = count($books);

                                    if(!($rowcount))
                                        echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
                                    else
                                    {
                                    ?>
                                    <div class="alert alert-info">
                                        <strong><?php echo $rowcount; ?> book(s) found</strong>
                                    </div>
                                    <?php
                                    ?>
                        <table class="table" id = "tables">
                                  <thead>
                                    <tr>
                                      <th>Book id</th>
                                      <th>Book name</th>
                                      <th>Availability</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                            
                            foreach($books as $row)
                            {
                                $bookid=$row['BookId'];
                                $name=$row['Title'];
                                $avail=$row['Availability'];
                            
                           
                            ?>
                                    <tr>
                                      <td><?php echo $bookid ?></td>
                                      <td><?php echo htmlspecialchars($name) ?></td>
                                      <td><b><?php echo $avail ?></b></td>
                                        <td><center>
                                            <a href="bookdetails.php?id=<?php echo $bookid; ?>" class="btn btn-primary btn-small">Details</a>
                                            <a href="edit_book_details.php?id=<?php echo $bookid; ?>" class="btn btn-success btn-small">Edit</a>
                                            <form method="post" style="display: inline;" onsubmit="return confirmDelete('<?php echo htmlspecialchars($name); ?>')">
                                                <input type="hidden" name="book_id" value="<?php echo $bookid; ?>">
                                                <button type="submit" name="delete_book" class="btn btn-danger btn-small">
                                                    <i class="icon-trash icon-white"></i> Delete
                                                </button>
                                            </form>
                                        </center></td>
                                    </tr>
                               <?php }} ?>
                               </tbody>
                                </table>
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
        
        <script>
        function confirmDelete(bookTitle) {
            return confirm('Are you sure you want to delete the book "' + bookTitle + '"?\n\nThis action cannot be undone. The book will be permanently removed from the library system.');
        }
        </script>
      
    </body>

</html>


<?php }
else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
} ?>
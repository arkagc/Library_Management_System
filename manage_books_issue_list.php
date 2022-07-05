<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["books_issue_id"])){
    
    $books_issue_id=$_GET["books_issue_id"];
    
    $sql = "SELECT * FROM `books_issue` WHERE books_issue_id=$books_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);

    $books_id = $fetch['books_id'];

    $sql_book = "SELECT * FROM `books` WHERE books_id=$books_id"; 
    $query_book = mysqli_query($con,$sql_book) or mysqli_connect_error();
    $fetch_book = mysqli_fetch_array($query_book);

    $books_quantity = $fetch_book['books_quantity'];
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($books_issue_id))
    {
        $sql_books_issue = "UPDATE `books_issue` SET 
        books_issue_date='".strtoLower($_POST["books_issue_date"])."',
        books_issue_return_date='".strtoLower($_POST["books_issue_return_date"])."',
        books_issue_modt=CURRENT_TIMESTAMP,
        books_issue_status='".$_POST["books_issue_status"]."'
        WHERE books_issue_id ='".$books_issue_id."'";

        mysqli_query($con,$sql_books_issue) or mysqli_connect_error();
        
        $sql_check = "SELECT * FROM `books_issue` WHERE books_issue_id=$books_issue_id"; 
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $fetch_check = mysqli_fetch_array($query_check);

        $issue_status = $fetch_check['books_issue_status'];
        $no_of_book_issue = $fetch_check['books_issue_no_of_books'];
        $user_id = $fetch_check['user_id'];
        $books_id = $fetch_check['books_id'];

        if($issue_status=='I')
        {
            $updt_books_quantity = $books_quantity - $no_of_book_issue;

            $sql_books = "UPDATE `books` SET 
            books_quantity='".$updt_books_quantity."',
            books_modt=CURRENT_TIMESTAMP
            WHERE books_id ='".$books_id."'";

            mysqli_query($con,$sql_books) or mysqli_connect_error();

            //insert data to `books_not_return` table
            $sql_books_not_return = "INSERT INTO `books_not_return` SET 
            user_id='".$user_id."',
            books_issue_id='".$books_issue_id."',
            books_not_return_no_of_books_issue='".$no_of_book_issue."',
            books_not_return_no_of_books_pending='".$no_of_book_issue."',
            books_not_return_crdt=CURRENT_TIMESTAMP
            ";

            mysqli_query($con,$sql_books_not_return) or mysqli_connect_error();
            $books_not_return_id=mysqli_insert_id($con);
        }
        
        echo '<script>window.location.href="manage_books_issue_list.php?books_issue_id='.$books_issue_id.'&msg=update";</script>';
    }
    else
    {
        $sql_books_issue = "INSERT INTO `books_issue` SET 
        user_id='".$_SESSION['UserId']."',
        books_id='".$_POST["books_id"]."',
        books_issue_no_of_books='".$_POST["books_issue_no_of_books"]."',
        books_issue_crdt=CURRENT_TIMESTAMP
        ";

        mysqli_query($con,$sql_books_issue) or mysqli_connect_error();
        $books_issue_id=mysqli_insert_id($con);

        echo '<script>window.location.href="manage_books_issue_list.php?books_issue_id='.$books_issue_id.'&msg=insert";</script>';
    }
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Books Issue</title>
  
  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Header & Nav Bar -->
  <?php include("includes/header.php"); ?>

  <!-- Sidebar Menu -->
  <?php include("includes/sidebar_left.php"); ?>
  
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Books Issue</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php
                if($_SESSION['UserType']=='admin')
                {
              ?>
                  <li class="breadcrumb-item">Admin</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item">Student</li>
              <?php
                }
              ?>
              <?php
                if(isset($books_issue_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Books Issue</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Books Issue</li>
              <?php
                }
              ?>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content 1 -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <?php
                              if(isset($books_issue_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Books Issue</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Books Issue</h3>
                            <?php
                              }
                            ?>
                            <a href="all_books_issue_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post">
                            <div class="card-body">
                                <p style="color:red;" class=""><b>( * ) indicates mandatory fields</b></p>
                                
                                <!--Print Message-->
                                <?php
                                  if(isset($_GET['msg']) && $_GET['msg'] == 'update')
                                  {
                                ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <strong>Books Issue Update Successfully !</strong>
                                    </div>
                                <?php
                                  }
                                ?>
                                <?php
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'insert')
                                    {
                                ?>
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Books Issue Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Books Issue Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <?php
                                if($_SESSION['UserType']=='admin')
                                {
                                ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksTitle">Books Title</label>
                                            <select class="form-control" name="books_id" id="books_id" required disabled>
                                                <option value="">Select Books Title</option>
                                                <?php
                                                    $sql_books="SELECT * FROM `books` WHERE books_status='Y' and books_quantity>0 ";
                                                    $query_books=mysqli_query($con,$sql_books) or mysqli_connect_error();
                                                    $rows_books=mysqli_num_rows($query_books);

                                                    if($rows_books > 0)
                                                    {
                                                        while($fetch_books=mysqli_fetch_array($query_books))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_books['books_id'])){ ?> <?php echo $fetch_books['books_id']; }?>" <?php if(isset($fetch['books_id']) && $fetch_books['books_id'] == $fetch['books_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_books['books_title']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksNoIssue">No of Books Issue</label>
                                            <input type="text" class="form-control" id="books_issue_no_of_books" name="books_issue_no_of_books" placeholder="Enter No of Books Issue" value="<?php if(isset($fetch['books_issue_no_of_books'])){?> <?php echo strtoUpper($fetch['books_issue_no_of_books']); }?>" required disabled>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else
                                {
                                ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksTitle">Books Title</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="books_id" id="books_id" required>
                                                <option value="">Select Books Title</option>
                                                <?php
                                                    $sql_books="SELECT * FROM `books` WHERE books_status='Y' and books_quantity>0 ";
                                                    $query_books=mysqli_query($con,$sql_books) or mysqli_connect_error();
                                                    $rows_books=mysqli_num_rows($query_books);

                                                    if($rows_books > 0)
                                                    {
                                                        while($fetch_books=mysqli_fetch_array($query_books))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_books['books_id'])){ ?> <?php echo $fetch_books['books_id']; }?>" <?php if(isset($fetch['books_id']) && $fetch_books['books_id'] == $fetch['books_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_books['books_title']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksNoIssue">No of Books Issue</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_issue_no_of_books" name="books_issue_no_of_books" placeholder="Enter No of Books Issue" value="<?php if(isset($fetch['books_issue_no_of_books'])){?> <?php echo strtoUpper($fetch['books_issue_no_of_books']); }?>" required>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                
                                <?php
                                    if($_SESSION['UserType']=='admin')
                                    {
                                ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksIssueDate">Books Issue Date</label>
                                                <div class="input-group date" id="books_issue_date" name="books_issue_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#books_issue_date" name="books_issue_date" placeholder="Enter Books Issue Date" value="<?php if(isset($fetch['books_issue_date'])) { ?><?php echo $fetch['books_issue_date']; } ?>"/>
                                                    <div class="input-group-append" data-target="#books_issue_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksReturnDate">Books Return Date</label>
                                                <div class="input-group date" id="books_issue_return_date" name="books_issue_return_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#books_issue_return_date" name="books_issue_return_date" placeholder="Enter Books Return Date" value="<?php if(isset($fetch['books_issue_return_date'])) { ?><?php echo $fetch['books_issue_return_date']; } ?>"/>
                                                    <div class="input-group-append" data-target="#books_issue_return_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksIssueStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                                <input type="radio" id="books_issue_status" name="books_issue_status" value="P" <?php if(isset($fetch['books_issue_status']) && $fetch['books_issue_status']=='P'){?> checked="checked"<?php }?> required> Pending &nbsp;
                                                <input type="radio" id="books_issue_status" name="books_issue_status" value="NI" <?php if(isset($fetch['books_issue_status']) && $fetch['books_issue_status']=='NI'){?> checked="checked"<?php }?> required> Not Issue &nbsp;
                                                <input type="radio" id="books_issue_status" name="books_issue_status" value="I" <?php if(isset($fetch['books_issue_status']) && $fetch['books_issue_status']=='I'){?> checked="checked"<?php }?> required> Issue
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_books_issue_list.php" class="btn btn-info">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content 1 -->
  </div>
  
  <!-- Footer -->
  <?php include("includes/footer.php"); ?>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  bsCustomFileInput.init();
});
</script>

<script>
    //Date picker
    $(function () {
        $('#books_issue_date').datetimepicker({
            format: 'L'
        });
    })

    $(function () {
        $('#books_issue_return_date').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
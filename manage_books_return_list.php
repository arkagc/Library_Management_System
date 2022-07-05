<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["books_issue_id"])){
    
    $books_issue_id=$_GET["books_issue_id"];
    
    $sql = "SELECT * FROM `books_return` WHERE books_issue_id=$books_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    $sql_books_return = "INSERT INTO `books_return` SET 
    user_id='".$_SESSION['UserId']."',
    books_issue_id='".$books_issue_id."',
    books_return_return_date='".$_POST["books_return_return_date"]."',
    books_return_no_of_books='".$_POST["books_return_no_of_books"]."',
    books_return_crdt=CURRENT_TIMESTAMP
    ";

    mysqli_query($con,$sql_books_return) or mysqli_connect_error();
    $books_return_id=mysqli_insert_id($con);


    $sql_check_issue = "SELECT * FROM `books_issue` WHERE books_issue_id='".$books_issue_id."' ";
    $query_check_issue = mysqli_query($con,$sql_check_issue) or mysqli_connect_error();
    $fetch_check_issue = mysqli_fetch_array($query_check_issue);

    $sql_check_book_quantity = "SELECT * FROM `books` WHERE books_id='".$fetch_check_issue['books_id']."' ";
    $query_check_book_quantity = mysqli_query($con,$sql_check_book_quantity) or mysqli_connect_error();
    $fetch_check_book_quantity = mysqli_fetch_array($query_check_book_quantity);

    $sql_check_return = "SELECT * FROM `books_return` WHERE books_return_id='".$books_return_id."' ";
    $query_check_return = mysqli_query($con,$sql_check_return) or mysqli_connect_error();
    $fetch_check_return = mysqli_fetch_array($query_check_return);

    //Increase the book quantity after return
    $books_quantity = $fetch_check_book_quantity['books_quantity'];
    $books_return = $fetch_check_return['books_return_no_of_books'];
    $updt_quantity = $fetch_check_book_quantity['books_quantity'] + $fetch_check_return['books_return_no_of_books'];
    $book_id = $fetch_check_issue['books_id'];

    $sql_books_quantity_updt = "UPDATE `books` SET
    books_quantity='".$updt_quantity."',
    books_modt=CURRENT_TIMESTAMP
    WHERE books_id='".$book_id."' ";

    mysqli_query($con,$sql_books_quantity_updt) or mysqli_connect_error();
    

    $books_issue_no_of_books = $fetch_check_issue['books_issue_no_of_books'];
    $books_issue_return_date = $fetch_check_issue['books_issue_return_date'];

    $updt_issue_retun_date = date('Y-m-d', strtotime($books_issue_return_date));

    $books_return_no_of_books = $fetch_check_return['books_return_no_of_books'];
    $books_return_return_date = $fetch_check_return['books_return_return_date'];

    $updt_retun_date = date('Y-m-d', strtotime($books_return_return_date));
    
    if($updt_retun_date > $updt_issue_retun_date)
    {
        $date_issue_return = date_create($updt_issue_retun_date);
        $date_return = date_create($updt_retun_date);

        $diff_dates = date_diff($date_issue_return,$date_return);

        $no_of_days = $diff_dates->format("%a");

        //fetch fine
        $sql_fine = "SELECT * FROM `fine_details`";
        $query_fine = mysqli_query($con,$sql_fine) or mysqli_connect_error();
        $fetch_fine = mysqli_fetch_array($query_fine);

        $book_fine = $fetch_fine['fine_details_books_fine'];

        $fine_books = $no_of_days * $books_return_no_of_books * $book_fine;

        $sql_book_return_no = "SELECT SUM(books_return_no_of_books) AS 'No of Return Book' FROM `books_return` WHERE books_issue_id='".$books_issue_id."' ";
        $query_book_return_no = mysqli_query($con,$sql_book_return_no) or mysqli_connect_error();
        $fetch_book_return_no = mysqli_fetch_array($query_book_return_no);

        $no_book_return = $fetch_book_return_no['No of Return Book'];

        //book not return
        $not_return_books = $books_issue_no_of_books - $no_book_return;

        $sql_books_not_retrun = "UPDATE `books_not_return` SET
        books_not_return_no_of_books_pending='".$not_return_books."',
        books_not_return_modt=CURRENT_TIMESTAMP
        WHERE books_issue_id ='".$books_issue_id."'";

        mysqli_query($con,$sql_books_not_retrun) or mysqli_connect_error();

        if($not_return_books==0)
        {
            $sqlUpdt = "DELETE FROM `books_not_return` WHERE books_issue_id='".$books_issue_id."' ";
            $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();
        }


        if($books_issue_no_of_books == $no_book_return)
        {
            $pending_books = $books_issue_no_of_books - $no_book_return;

            $sql_books_return = "UPDATE `books_return` SET
            books_return_no_of_pending_books='".$pending_books."',
            books_return_fine='".$fine_books."',
            books_return_modt=CURRENT_TIMESTAMP,
            books_return_status='C'
            WHERE books_return_id ='".$books_return_id."'";

            mysqli_query($con,$sql_books_return) or mysqli_connect_error();
        }
        else
        {
            $pending_books = $books_issue_no_of_books - $no_book_return;

            $sql_books_return = "UPDATE `books_return` SET
            books_return_no_of_pending_books='".$pending_books."',
            books_return_fine='".$fine_books."',
            books_return_modt=CURRENT_TIMESTAMP,
            books_return_status='P'
            WHERE books_return_id ='".$books_return_id."'";

            mysqli_query($con,$sql_books_return) or mysqli_connect_error();
        }
    }
    else
    {
        $fine_books = 0;

        $sql_book_return_no = "SELECT SUM(books_return_no_of_books) AS 'No of Return Book' FROM `books_return` WHERE books_issue_id='".$books_issue_id."' ";
        $query_book_return_no = mysqli_query($con,$sql_book_return_no) or mysqli_connect_error();
        $fetch_book_return_no = mysqli_fetch_array($query_book_return_no);

        $no_book_return = $fetch_book_return_no['No of Return Book'];

        //book not return
        $not_return_books = $books_issue_no_of_books - $no_book_return;

        $sql_books_not_retrun = "UPDATE `books_not_return` SET
        books_not_return_no_of_books_pending='".$not_return_books."',
        books_not_return_modt=CURRENT_TIMESTAMP
        WHERE books_issue_id ='".$books_issue_id."'";

        mysqli_query($con,$sql_books_not_retrun) or mysqli_connect_error();

        if($not_return_books==0)
        {
            $sqlUpdt = "DELETE FROM `books_not_return` WHERE books_issue_id='".$books_issue_id."' ";
            $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();
        }

        if($books_issue_no_of_books == $no_book_return)
        {
            $pending_books = $books_issue_no_of_books - $no_book_return;

            $sql_books_return = "UPDATE `books_return` SET
            books_return_no_of_pending_books='".$pending_books."',
            books_return_fine='".$fine_books."',
            books_return_modt=CURRENT_TIMESTAMP,
            books_return_status='C'
            WHERE books_return_id ='".$books_return_id."'";

            mysqli_query($con,$sql_books_return) or mysqli_connect_error();
        }
        else
        {
            $pending_books = $books_issue_no_of_books - $no_book_return;

            $sql_books_return = "UPDATE `books_return` SET
            books_return_no_of_pending_books='".$pending_books."',
            books_return_fine='".$fine_books."',
            books_return_modt=CURRENT_TIMESTAMP,
            books_return_status='P'
            WHERE books_return_id ='".$books_return_id."'";

            mysqli_query($con,$sql_books_return) or mysqli_connect_error();
        }
    }

    echo '<script>window.location.href="all_books_return_list.php?books_return_id='.$books_return_id.'&msg=insert";</script>';
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Books Return</title>
  
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
            <h1 class="m-0">Dashboard</h1>
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
                if(isset($books_return_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Books Return</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Books Return</li>
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
                              if(isset($books_return_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Books Return</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Books Return</h3>
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
                                        <strong>Books Return Update Successfully !</strong>
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
                                            <strong>Books Return Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>No of Books Return Not Matched !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                
                                <?php
                                    if($_SESSION['UserType']=='student')
                                    {
                                        $sql_book_issue = "SELECT * FROM `books_issue` WHERE books_issue_id='".$books_issue_id."' "; 
                                        $query_book_issue = mysqli_query($con,$sql_book_issue) or mysqli_connect_error();
                                        $fetch_book_issue = mysqli_fetch_array($query_book_issue);
                                ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksIssueReturnDate">Books Issue Return Date</label>
                                                <input type="text" class="form-control" id="books_issue_return_date" name="books_issue_return_date" placeholder="Enter Books Issue Return Date" value="<?php if(isset($fetch_book_issue['books_issue_return_date'])){?> <?php echo strtoUpper($fetch_book_issue['books_issue_return_date']); }?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksNoIssue">No of Books Issue</label>
                                                <input type="text" class="form-control" id="books_issue_no_of_books" name="books_issue_no_of_books" placeholder="Enter No of Books Issue" value="<?php if(isset($fetch_book_issue['books_issue_no_of_books'])){?> <?php echo strtoUpper($fetch_book_issue['books_issue_no_of_books']); }?>" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksReturnDate">My Return Date</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                                <div class="input-group date" id="books_return_return_date" name="books_return_return_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#books_return_return_date" name="books_return_return_date" placeholder="Enter Books Return Date" value="<?php //if(isset($fetch['books_return_return_date'])) { ?><?php //echo $fetch['books_return_return_date']; } ?>" required/>
                                                    <div class="input-group-append" data-target="#books_return_return_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="booksNoReturn">No of Books Return</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                                <input type="text" class="form-control" id="books_return_no_of_books" name="books_return_no_of_books" placeholder="Enter No of Books Return" required>
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
        $('#books_return_return_date').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["newspapers_issue_id"])){
    
    $newspapers_issue_id=$_GET["newspapers_issue_id"];
    
    $sql = "SELECT * FROM `newspapers_return` WHERE newspapers_issue_id=$newspapers_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    $sql_newspapers_return = "INSERT INTO `newspapers_return` SET 
    user_id='".$_SESSION['UserId']."',
    newspapers_issue_id='".$newspapers_issue_id."',
    newspapers_return_return_date='".$_POST["newspapers_return_return_date"]."',
    newspapers_return_no_of_newspapers='".$_POST["newspapers_return_no_of_newspapers"]."',
    newspapers_return_crdt=CURRENT_TIMESTAMP
    ";

    mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
    $newspapers_return_id=mysqli_insert_id($con);


    $sql_check_issue = "SELECT * FROM `newspapers_issue` WHERE newspapers_issue_id='".$newspapers_issue_id."' ";
    $query_check_issue = mysqli_query($con,$sql_check_issue) or mysqli_connect_error();
    $fetch_check_issue = mysqli_fetch_array($query_check_issue);

    $sql_check_newspapers_quantity = "SELECT * FROM `newspapers` WHERE newspapers_id='".$fetch_check_issue['newspapers_id']."' ";
    $query_check_newspapers_quantity = mysqli_query($con,$sql_check_newspapers_quantity) or mysqli_connect_error();
    $fetch_check_newspapers_quantity = mysqli_fetch_array($query_check_newspapers_quantity);

    $sql_check_return = "SELECT * FROM `newspapers_return` WHERE newspapers_return_id='".$newspapers_return_id."' ";
    $query_check_return = mysqli_query($con,$sql_check_return) or mysqli_connect_error();
    $fetch_check_return = mysqli_fetch_array($query_check_return);

    //Increase the newspapers quantity after return
    $newspapers_quantity = $fetch_check_newspapers_quantity['newspapers_quantity'];
    $newspapers_return = $fetch_check_return['newspapers_return_no_of_newspapers'];
    $updt_quantity = $fetch_check_newspapers_quantity['newspapers_quantity'] + $fetch_check_return['newspapers_return_no_of_newspapers'];
    $newspapers_id = $fetch_check_issue['newspapers_id'];

    $sql_newspapers_quantity_updt = "UPDATE `newspapers` SET
    newspapers_quantity='".$updt_quantity."',
    newspapers_modt=CURRENT_TIMESTAMP
    WHERE newspapers_id='".$newspapers_id."' ";

    mysqli_query($con,$sql_newspapers_quantity_updt) or mysqli_connect_error();
    

    $newspapers_issue_no_of_newspapers = $fetch_check_issue['newspapers_issue_no_of_newspapers'];
    $newspapers_issue_return_date = $fetch_check_issue['newspapers_issue_return_date'];

    $updt_issue_retun_date = date('Y-m-d', strtotime($newspapers_issue_return_date));


    $newspapers_return_no_of_books = $fetch_check_return['newspapers_return_no_of_newspapers'];
    $newspapers_return_return_date = $fetch_check_return['newspapers_return_return_date'];

    $updt_retun_date = date('Y-m-d', strtotime($newspapers_return_return_date));
    
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

        $newspapers_fine = $fetch_fine['fine_details_newspapers_fine'];

        $fine_newspapers = $no_of_days * $newspapers_return_no_of_books * $newspapers_fine;

        $sql_newspapers_return_no = "SELECT SUM(newspapers_return_no_of_newspapers) AS 'No of Return Newspapers' FROM `newspapers_return` WHERE newspapers_issue_id='".$newspapers_issue_id."' ";
        $query_newspapers_return_no = mysqli_query($con,$sql_newspapers_return_no) or mysqli_connect_error();
        $fetch_newspapers_return_no = mysqli_fetch_array($query_newspapers_return_no);

        $no_newspapers_return = $fetch_newspapers_return_no['No of Return Newspapers'];

        //newspapers not return
        $not_return_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

        $sql_newspapers_not_retrun = "UPDATE `newspapers_not_return` SET
        newspapers_not_return_no_of_newspapers_pending='".$not_return_newspapers."',
        newspapers_not_return_modt=CURRENT_TIMESTAMP
        WHERE newspapers_issue_id ='".$newspapers_issue_id."'";

        mysqli_query($con,$sql_newspapers_not_retrun) or mysqli_connect_error();

        if($not_return_newspapers==0)
        {
            $sqlUpdt = "DELETE FROM `newspapers_not_return` WHERE newspapers_issue_id='".$newspapers_issue_id."' ";
            $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();
        }


        if($newspapers_issue_no_of_newspapers == $no_newspapers_return)
        {
            $pending_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

            $sql_newspapers_return = "UPDATE `newspapers_return` SET
            newspapers_return_no_of_pending_newspapers='".$pending_newspapers."',
            newspapers_return_fine='".$fine_newspapers."',
            newspapers_return_modt=CURRENT_TIMESTAMP,
            newspapers_return_status='C'
            WHERE newspapers_return_id='".$newspapers_return_id."'";

            mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
        }
        else
        {
            $pending_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

            $sql_newspapers_return = "UPDATE `newspapers_return` SET
            newspapers_return_no_of_pending_newspapers='".$pending_newspapers."',
            newspapers_return_fine='".$fine_newspapers."',
            newspapers_return_modt=CURRENT_TIMESTAMP,
            newspapers_return_status='P'
            WHERE newspapers_return_id ='".$newspapers_return_id."'";

            mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
        }
    }
    else
    {
        $fine_newspapers = 0;

        $sql_newspapers_return_no = "SELECT SUM(newspapers_return_no_of_newspapers) AS 'No of Return Newspapers' FROM `newspapers_return` WHERE newspapers_issue_id='".$newspapers_issue_id."' ";
        $query_newspapers_return_no = mysqli_query($con,$sql_newspapers_return_no) or mysqli_connect_error();
        $fetch_newspapers_return_no = mysqli_fetch_array($query_newspapers_return_no);

        $no_newspapers_return = $fetch_newspapers_return_no['No of Return Newspapers'];

        //newspapers not return
        $not_return_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

        $sql_newspapers_not_retrun = "UPDATE `newspapers_not_return` SET
        newspapers_not_return_no_of_newspapers_pending='".$not_return_newspapers."',
        newspapers_not_return_modt=CURRENT_TIMESTAMP
        WHERE newspapers_issue_id ='".$newspapers_issue_id."'";

        mysqli_query($con,$sql_newspapers_not_retrun) or mysqli_connect_error();

        if($not_return_newspapers==0)
        {
            $sqlUpdt = "DELETE FROM `newspapers_not_return` WHERE newspapers_issue_id='".$newspapers_issue_id."' ";
            $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();
        }

        if($newspapers_issue_no_of_newspapers == $no_newspapers_return)
        {
            $pending_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

            $sql_newspapers_return = "UPDATE `newspapers_return` SET
            newspapers_return_no_of_pending_newspapers='".$pending_newspapers."',
            newspapers_return_fine='".$fine_newspapers."',
            newspapers_return_modt=CURRENT_TIMESTAMP,
            newspapers_return_status='C'
            WHERE newspapers_return_id='".$newspapers_return_id."'";

            mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
        }
        else
        {
            $pending_newspapers = $newspapers_issue_no_of_newspapers - $no_newspapers_return;

            $sql_newspapers_return = "UPDATE `newspapers_return` SET
            newspapers_return_no_of_pending_newspapers='".$pending_newspapers."',
            newspapers_return_fine='".$fine_newspapers."',
            newspapers_return_modt=CURRENT_TIMESTAMP,
            newspapers_return_status='P'
            WHERE newspapers_return_id='".$newspapers_return_id."'";

            mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
        }
    }

    echo '<script>window.location.href="all_newspapers_return_list.php?newspapers_return_id='.$newspapers_return_id.'&msg=insert";</script>';
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Newspapers Return</title>
  
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
                if(isset($newspapers_return_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Newspapers Return</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Newspapers Return</li>
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
                              if(isset($newspapers_return_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Newspapers Return</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Newspapers Return</h3>
                            <?php
                              }
                            ?>
                            <a href="all_newspapers_issue_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Newspapers Return Update Successfully !</strong>
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
                                            <strong>Newspapers Return Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>No of Newspapers Return Not Matched !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                
                                <?php
                                    if($_SESSION['UserType']=='student')
                                    {
                                        $sql_newspapers_issue = "SELECT * FROM `newspapers_issue` WHERE newspapers_issue_id='".$newspapers_issue_id."' "; 
                                        $query_newspapers_issue = mysqli_query($con,$sql_newspapers_issue) or mysqli_connect_error();
                                        $fetch_newspapers_issue = mysqli_fetch_array($query_newspapers_issue);
                                ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="newspapersIssueReturnDate">Newspapers Issue Return Date</label>
                                                <input type="text" class="form-control" id="newspapers_issue_return_date" name="newspapers_issue_return_date" placeholder="Enter Newspapers Issue Return Date" value="<?php if(isset($fetch_newspapers_issue['newspapers_issue_return_date'])){?> <?php echo strtoUpper($fetch_newspapers_issue['newspapers_issue_return_date']); }?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="newspapersNoIssue">No of Newspapers Issue</label>
                                                <input type="text" class="form-control" id="newspapers_issue_no_of_newspapers" name="newspapers_issue_no_of_newspapers" placeholder="Enter No of Newspapers Issue" value="<?php if(isset($fetch_newspapers_issue['newspapers_issue_no_of_newspapers'])){?> <?php echo strtoUpper($fetch_newspapers_issue['newspapers_issue_no_of_newspapers']); }?>" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="mnewspapersReturnDate">My Return Date</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                                <div class="input-group date" id="newspapers_return_return_date" name="newspapers_return_return_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#newspapers_return_return_date" name="newspapers_return_return_date" placeholder="Enter Newspapers Return Date" value="<?php //if(isset($fetch['books_return_return_date'])) { ?><?php //echo $fetch['books_return_return_date']; } ?>" required/>
                                                    <div class="input-group-append" data-target="#newspapers_return_return_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="newspapersNoReturn">No of Newspapers Return</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                                <input type="text" class="form-control" id="newspapers_return_no_of_newspapers" name="newspapers_return_no_of_newspapers" placeholder="Enter No of Newspapers Return" required>
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
                                <a href="all_newspapers_issue_list.php" class="btn btn-info">Cancel</a>
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
        $('#newspapers_return_return_date').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
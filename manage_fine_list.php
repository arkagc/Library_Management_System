<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}
elseif($_SESSION['UserType']=='student')
{
  header('location:student_dashboard.php');
}

if(isset($_GET["fine_details_id"])){
    
    $fine_details_id=$_GET["fine_details_id"];
    
    $sql = "SELECT * FROM `fine_details` WHERE fine_details_id=$fine_details_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($fine_details_id))
    {
        $sql_fine_details = "UPDATE `fine_details` SET 
        fine_details_books_fine='".strtoLower($_POST["fine_details_books_fine"])."',
        fine_details_books='".strtoLower($_POST["fine_details_books"])."',
        fine_details_magazines_fine='".strtoLower($_POST["fine_details_magazines_fine"])."',
        fine_details_magazines='".strtoLower($_POST["fine_details_magazines"])."',
        fine_details_newspapers_fine='".strtoLower($_POST["fine_details_newspapers_fine"])."',
        fine_details_newspapers='".strtoLower($_POST["fine_details_newspapers"])."',
        fine_details_modt=CURRENT_TIMESTAMP,
        fine_details_status='".$_POST["fine_details_status"]."'
        WHERE fine_details_id='".$fine_details_id."'";

        mysqli_query($con,$sql_fine_details) or mysqli_connect_error();

        echo '<script>window.location.href="manage_fine_list.php?fine_details_id='.$fine_details_id.'&msg=update";</script>';
    }
    else
    {
        $sql_fine_details = "INSERT INTO `fine_details` SET 
        fine_details_books_fine='".strtoLower($_POST["fine_details_books_fine"])."',
        fine_details_books='".strtoLower($_POST["fine_details_books"])."',
        fine_details_magazines_fine='".strtoLower($_POST["fine_details_magazines_fine"])."',
        fine_details_magazines='".strtoLower($_POST["fine_details_magazines"])."',
        fine_details_newspapers_fine='".strtoLower($_POST["fine_details_newspapers_fine"])."',
        fine_details_newspapers='".strtoLower($_POST["fine_details_newspapers"])."',
        fine_details_crdt=CURRENT_TIMESTAMP,
        fine_details_status='".$_POST["fine_details_status"]."'
        ";

        mysqli_query($con,$sql_fine_details) or mysqli_connect_error();
        $fine_details_id=mysqli_insert_id($con);

        echo '<script>window.location.href="manage_fine_list.php?fine_details_id='.$fine_details_id.'&msg=insert";</script>';
    }
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Fine Details</title>
  
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
            <h1 class="m-0">Fine Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($fine_details_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Fine Details</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Fine Details</li>
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
                              if(isset($fine_details_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Fine Details</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Fine Details</h3>
                            <?php
                              }
                            ?>
                            <a href="all_fine_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Fine Details Update Successfully !</strong>
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
                                            <strong>Fine Details Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Fine Details Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksFine">Books Fine</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_books_fine" name="fine_details_books_fine" placeholder="Enter Books Fine" value="<?php if(isset($fetch['fine_details_books_fine'])){?> <?php echo strtoUpper($fetch['fine_details_books_fine']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksFineDetails">Books Fine Details</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_books" name="fine_details_books" placeholder="Enter Books Fine Details" value="<?php if(isset($fetch['fine_details_books'])){?> <?php echo strtoUpper($fetch['fine_details_books']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesFine">Magazines Fine</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_magazines_fine" name="fine_details_magazines_fine" placeholder="Enter Magazines Fine" value="<?php if(isset($fetch['fine_details_magazines_fine'])){?> <?php echo strtoUpper($fetch['fine_details_magazines_fine']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesFineDetails">Magazines Fine Details</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_magazines" name="fine_details_magazines" placeholder="Enter Magazines Fine Details" value="<?php if(isset($fetch['fine_details_magazines'])){?> <?php echo strtoUpper($fetch['fine_details_magazines']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersFine">Newspapers Fine</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_newspapers_fine" name="fine_details_newspapers_fine" placeholder="Enter Newspapers Fine" value="<?php if(isset($fetch['fine_details_newspapers_fine'])){?> <?php echo strtoUpper($fetch['fine_details_newspapers_fine']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersFineDetails">Newspapers Fine Details</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="fine_details_newspapers" name="fine_details_newspapers" placeholder="Enter Newspapers Fine Details" value="<?php if(isset($fetch['fine_details_newspapers'])){?> <?php echo strtoUpper($fetch['fine_details_newspapers']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="fineDetailsStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="fine_details_status" name="fine_details_status" value="Y" <?php if(isset($fetch['fine_details_status']) && $fetch['fine_details_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="fine_details_status" name="fine_details_status" value="N" <?php if(isset($fetch['fine_details_status']) && $fetch['fine_details_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_fine_list.php" class="btn btn-info">Cancel</a>
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

</body>
</html>
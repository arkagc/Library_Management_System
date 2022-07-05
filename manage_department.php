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

if(isset($_GET["dept_id"])){
    
    $dept_id=$_GET["dept_id"];
    
    $sql = "SELECT * FROM `department` WHERE dept_id=$dept_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($dept_id))
    {
        $sql_check = "SELECT * FROM `department` WHERE dept_code='".strtoLower($_POST["dept_code"])."'";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            $sql_dept = "UPDATE `department` SET 
            dept_name='".strtoLower($_POST["dept_name"])."',
            dept_code='".strtoLower($_POST["dept_code"])."',
            dept_modt=CURRENT_TIMESTAMP,
            dept_status='".$_POST["dept_status"]."'
            WHERE dept_id='".$dept_id."'";

            mysqli_query($con,$sql_dept) or mysqli_connect_error();

            echo '<script>window.location.href="manage_department.php?dept_id='.$dept_id.'&msg=update";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_department.php?&msg=error";</script>';
        }
    }
    else
    {
        $sql_check = "SELECT * FROM `department` WHERE dept_code='".strtoLower($_POST["dept_code"])."'";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            $sql_dept = "INSERT INTO `department` SET 
            dept_name='".strtoLower($_POST["dept_name"])."',
            dept_code='".strtoLower($_POST["dept_code"])."',
            dept_crdt=CURRENT_TIMESTAMP,
            dept_status='".$_POST["dept_status"]."'
            ";

            mysqli_query($con,$sql_dept) or mysqli_connect_error();
            $dept_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_department.php?dept_id='.$dept_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_department.php?&msg=error";</script>';
        }
    }
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Department</title>
  
  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
            <h1 class="m-0">Department</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($dept_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Department</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Department</li>
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
                              if(isset($dept_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Department</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Department</h3>
                            <?php
                              }
                            ?>
                            <a href="all_department.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Department Update Successfully !</strong>
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
                                            <strong>Department Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Department Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="deptName">Department Full Name</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="dept_name" name="dept_name" placeholder="Enter Department Full Name" value="<?php if(isset($fetch['dept_name'])){?> <?php echo strtoUpper($fetch['dept_name']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="deptCode">Department Short Code</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="dept_code" name="dept_code" placeholder="Enter Department Short Code" value="<?php if(isset($fetch['dept_code'])){?> <?php echo strtoUpper($fetch['dept_code']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="deptStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="dept_status" name="dept_status" value="Y" <?php if(isset($fetch['dept_status']) && $fetch['dept_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="dept_status" name="dept_status" value="N" <?php if(isset($fetch['dept_status']) && $fetch['dept_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_department.php" class="btn btn-info">Cancel</a>
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
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
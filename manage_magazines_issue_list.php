<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["magazines_issue_id"])){
    
    $magazines_issue_id=$_GET["magazines_issue_id"];
    
    $sql = "SELECT * FROM `magazines_issue` WHERE magazines_issue_id=$magazines_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);

    $magazines_id = $fetch['magazines_id'];

    $sql_magazine = "SELECT * FROM `magazines` WHERE magazines_id=$magazines_id"; 
    $query_magazine = mysqli_query($con,$sql_magazine) or mysqli_connect_error();
    $fetch_magazine = mysqli_fetch_array($query_magazine);

    $magazines_quantity = $fetch_magazine['magazines_quantity'];
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($magazines_issue_id))
    {
        $sql_magazines_issue = "UPDATE `magazines_issue` SET 
        magazines_issue_date='".strtoLower($_POST["magazines_issue_date"])."',
        magazines_issue_return_date='".strtoLower($_POST["magazines_issue_return_date"])."',
        magazines_issue_modt=CURRENT_TIMESTAMP,
        magazines_issue_status='".$_POST["magazines_issue_status"]."'
        WHERE magazines_issue_id ='".$magazines_issue_id."'";

        mysqli_query($con,$sql_magazines_issue) or mysqli_connect_error();
        
        $sql_check = "SELECT * FROM `magazines_issue` WHERE magazines_issue_id=$magazines_issue_id"; 
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $fetch_check = mysqli_fetch_array($query_check);

        $issue_status = $fetch_check['magazines_issue_status'];
        $no_of_magazine_issue = $fetch_check['magazines_issue_no_of_magazines'];
        $user_id = $fetch_check['user_id'];
        $magazines_id = $fetch_check['magazines_id'];

        if($issue_status=='I')
        {
            $updt_magazines_quantity = $magazines_quantity - $no_of_magazine_issue;

            $sql_magazines = "UPDATE `magazines` SET 
            magazines_quantity='".$updt_magazines_quantity."',
            magazines_modt=CURRENT_TIMESTAMP
            WHERE magazines_id ='".$magazines_id."'";

            mysqli_query($con,$sql_magazines) or mysqli_connect_error();

            //insert data to `magazines_not_return` table
            $sql_magazines_not_return = "INSERT INTO `magazines_not_return` SET 
            user_id='".$user_id."',
            magazines_issue_id='".$magazines_issue_id."',
            magazines_not_return_no_of_magazines_issue='".$no_of_magazine_issue."',
            magazines_not_return_no_of_magazines_pending='".$no_of_magazine_issue."',
            magazines_not_return_crdt=CURRENT_TIMESTAMP
            ";

            mysqli_query($con,$sql_magazines_not_return) or mysqli_connect_error();
            $magazines_not_return_id=mysqli_insert_id($con);
        }
        
        echo '<script>window.location.href="manage_magazines_issue_list.php?magazines_issue_id='.$magazines_issue_id.'&msg=update";</script>';

    }
    else
    {
        $sql_magazines_issue = "INSERT INTO `magazines_issue` SET 
        user_id='".$_SESSION['UserId']."',
        magazines_id='".$_POST["magazines_id"]."',
        magazines_issue_no_of_magazines='".$_POST["magazines_issue_no_of_magazines"]."',
        magazines_issue_crdt=CURRENT_TIMESTAMP
        ";

        mysqli_query($con,$sql_magazines_issue) or mysqli_connect_error();
        $magazines_issue_id=mysqli_insert_id($con);

        echo '<script>window.location.href="manage_magazines_issue_list.php?magazines_issue_id='.$magazines_issue_id.'&msg=insert";</script>';
    }
}
/*############################## End Add/Edit ################################*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Add / Edit Magazines Issue</title>
  
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
            <h1 class="m-0">Magazines Issue</h1>
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
                if(isset($magazines_issue_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Magazines Issue</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Magazines Issue</li>
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
                              if(isset($magazines_issue_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Magazines Issue</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Magazines Issue</h3>
                            <?php
                              }
                            ?>
                            <a href="all_magazines_issue_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Magazines Issue Update Successfully !</strong>
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
                                            <strong>Magazines Issue Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Magazines Issue Already Exists !</strong>
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
                                            <label for="magazinesTitle">Magazines Title</label>
                                            <select class="form-control" name="magazines_id" id="magazines_id" required disabled>
                                                <option value="">Select Magazines Title</option>
                                                <?php
                                                    $sql_magazines="SELECT * FROM `magazines` WHERE magazines_status='Y' and magazines_quantity>0 ";
                                                    $query_magazines=mysqli_query($con,$sql_magazines) or mysqli_connect_error();
                                                    $rows_magazines=mysqli_num_rows($query_magazines);

                                                    if($rows_magazines > 0)
                                                    {
                                                        while($fetch_magazines=mysqli_fetch_array($query_magazines))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_magazines['magazines_id'])){ ?> <?php echo $fetch_magazines['magazines_id']; }?>" <?php if(isset($fetch['magazines_id']) && $fetch_magazines['magazines_id'] == $fetch['magazines_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_magazines['magazines_title']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesNoIssue">No of Magazines Issue</label>
                                            <input type="text" class="form-control" id="magazines_issue_no_of_magazines" name="magazines_issue_no_of_magazines" placeholder="Enter No of Magazines Issue" value="<?php if(isset($fetch['magazines_issue_no_of_magazines'])){?> <?php echo strtoUpper($fetch['magazines_issue_no_of_magazines']); }?>" required disabled>
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
                                            <label for="magazinesTitle">Magazines Title</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="magazines_id" id="magazines_id" required>
                                                <option value="">Select Magazines Title</option>
                                                <?php
                                                    $sql_magazines="SELECT * FROM `magazines` WHERE magazines_status='Y' and magazines_quantity>0 ";
                                                    $query_magazines=mysqli_query($con,$sql_magazines) or mysqli_connect_error();
                                                    $rows_magazines=mysqli_num_rows($query_magazines);

                                                    if($rows_magazines > 0)
                                                    {
                                                        while($fetch_magazines=mysqli_fetch_array($query_magazines))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_magazines['magazines_id'])){ ?> <?php echo $fetch_magazines['magazines_id']; }?>" <?php if(isset($fetch['magazines_id']) && $fetch_magazines['magazines_id'] == $fetch['magazines_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_magazines['magazines_title']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesNoIssue">No of Magazines Issue</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_issue_no_of_magazines" name="magazines_issue_no_of_magazines" placeholder="Enter No of Magazines Issue" value="<?php if(isset($fetch['magazines_issue_no_of_magazines'])){?> <?php echo strtoUpper($fetch['magazines_issue_no_of_magazines']); }?>" required>
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
                                                <label for="magazinesIssueDate">Magazines Issue Date</label>
                                                <div class="input-group date" id="magazines_issue_date" name="magazines_issue_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#magazines_issue_date" name="magazines_issue_date" placeholder="Enter Magazines Issue Date" value="<?php if(isset($fetch['magazines_issue_date'])) { ?><?php echo $fetch['magazines_issue_date']; } ?>"/>
                                                    <div class="input-group-append" data-target="#magazines_issue_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="magazinesReturnDate">Magazines Return Date</label>
                                                <div class="input-group date" id="magazines_issue_return_date" name="magazines_issue_return_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#magazines_issue_return_date" name="magazines_issue_return_date" placeholder="Enter Magazines Return Date" value="<?php if(isset($fetch['magazines_issue_return_date'])) { ?><?php echo $fetch['magazines_issue_return_date']; } ?>"/>
                                                    <div class="input-group-append" data-target="#magazines_issue_return_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="magazinesIssueStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                                <input type="radio" id="magazines_issue_status" name="magazines_issue_status" value="P" <?php if(isset($fetch['magazines_issue_status']) && $fetch['magazines_issue_status']=='P'){?> checked="checked"<?php }?> required> Pending &nbsp;
                                                <input type="radio" id="magazines_issue_status" name="magazines_issue_status" value="NI" <?php if(isset($fetch['magazines_issue_status']) && $fetch['magazines_issue_status']=='NI'){?> checked="checked"<?php }?> required> Not Issue &nbsp;
                                                <input type="radio" id="magazines_issue_status" name="magazines_issue_status" value="I" <?php if(isset($fetch['magazines_issue_status']) && $fetch['magazines_issue_status']=='I'){?> checked="checked"<?php }?> required> Issue
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
                                <a href="all_magazines_issue_list.php" class="btn btn-info">Cancel</a>
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
        $('#magazines_issue_date').datetimepicker({
            format: 'L'
        });
    })

    $(function () {
        $('#magazines_issue_return_date').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
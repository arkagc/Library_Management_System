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

if(isset($_GET["magazines_id"])){
    
    $magazines_id=$_GET["magazines_id"];
    
    $sql = "SELECT * FROM `magazines` WHERE magazines_id=$magazines_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($magazines_id))
    {
        $sql_check = "SELECT * FROM `magazines` WHERE magazines_name='".strtoLower($_POST["magazines_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['magazines_image']['name']=="")
            {
                $mainpic=$_POST['oldimage'];			
            }
            else
            {
                $Imagesfolder="uploads/magazines/";
                $unlink_sql="SELECT magazines_image FROM `magazines` WHERE magazines_id='".$magazines_id."'";
                $unlink_rs=mysqli_query($con,$unlink_sql) or mysqli_connect_error();
                $row_unlink=mysqli_fetch_array($unlink_rs);
                $photo=$Imagesfolder.$row_unlink['magazines_image'];

                if(file_exists($photo))
                {
                    @unlink($photo);
                }

                $path_parts = pathinfo($_FILES['magazines_image']['name']); 
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['magazines_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }

            $sql_magazines = "UPDATE `magazines` SET 
            magazines_category_id='".strtoLower($_POST["magazines_category_id"])."',
            magazines_type='".strtoLower($_POST["magazines_type"])."',
            magazines_title='".strtoLower($_POST["magazines_title"])."',
            magazines_name='".strtoLower($_POST["magazines_name"])."',
            magazines_publisher='".strtoLower($_POST["magazines_publisher"])."',
            magazines_date_of_publish='".strtoLower($_POST["magazines_date_of_publish"])."',
            magazines_date_of_receipt='".strtoLower($_POST["magazines_date_of_receipt"])."',
            magazines_price='".strtoLower($_POST["magazines_price"])."',
            magazines_total_pages='".strtoLower($_POST["magazines_total_pages"])."',
            magazines_quantity='".strtoLower($_POST["magazines_quantity"])."',
            magazines_modt=CURRENT_TIMESTAMP,
            magazines_image='".$mainpic."',
            magazines_status='".$_POST["magazines_status"]."'
            WHERE magazines_id ='".$magazines_id."'";

            mysqli_query($con,$sql_magazines) or mysqli_connect_error();

            echo '<script>window.location.href="manage_magazines_list.php?magazines_id='.$magazines_id.'&msg=update";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_magazines_list.php?&msg=error";</script>';
        }
    }
    else
    {
        $sql_check = "SELECT * FROM `magazines` WHERE magazines_name='".strtoLower($_POST["magazines_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['magazines_image']['name']!="")
            {
                $Imagesfolder="uploads/magazines/";
                $path_parts = pathinfo($_FILES['magazines_image']['name']);
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['magazines_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }
            else
            {
                $mainpic="";
            }

            $sql_magazines = "INSERT INTO `magazines` SET 
            magazines_category_id='".strtoLower($_POST["magazines_category_id"])."',
            magazines_type='".strtoLower($_POST["magazines_type"])."',
            magazines_title='".strtoLower($_POST["magazines_title"])."',
            magazines_name='".strtoLower($_POST["magazines_name"])."',
            magazines_publisher='".strtoLower($_POST["magazines_publisher"])."',
            magazines_date_of_publish='".strtoLower($_POST["magazines_date_of_publish"])."',
            magazines_date_of_receipt='".strtoLower($_POST["magazines_date_of_receipt"])."',
            magazines_price='".strtoLower($_POST["magazines_price"])."',
            magazines_total_pages='".strtoLower($_POST["magazines_total_pages"])."',
            magazines_quantity='".strtoLower($_POST["magazines_quantity"])."',
            magazines_crdt=CURRENT_TIMESTAMP,
            magazines_image='".$mainpic."',
            magazines_status='".$_POST["magazines_status"]."'
            ";

            mysqli_query($con,$sql_magazines) or mysqli_connect_error();
            $magazines_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_magazines_list.php?magazines_id='.$magazines_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_magazines_list.php?&msg=error";</script>';
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
  <title>LMS | Add / Edit Magazines</title>
  
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
            <h1 class="m-0">Magazines</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($magazines_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Magazines</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Magazines</li>
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
                              if(isset($magazines_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Magazines</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Magazines</h3>
                            <?php
                              }
                            ?>
                            <a href="all_magazines_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <p style="color:red;" class=""><b>( * ) indicates mandatory fields</b></p>
                                
                                <!--Print Message-->
                                <?php
                                  if(isset($_GET['msg']) && $_GET['msg'] == 'update')
                                  {
                                ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <strong>Magazines Update Successfully !</strong>
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
                                            <strong>Magazines Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Magazines Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesCategory">Magazines Category</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="magazines_category_id" id="magazines_category_id" required>
                                                <option value="">Select Magazines Category</option>
                                                <?php
                                                    $sql_magazines_category="SELECT * FROM `magazines_category` WHERE magazines_category_status='Y' ";
                                                    $query_magazines_category=mysqli_query($con,$sql_magazines_category) or mysqli_connect_error();
                                                    $rows_magazines_category=mysqli_num_rows($query_magazines_category);

                                                    if($rows_magazines_category > 0)
                                                    {
                                                        while($fetch_magazines_category=mysqli_fetch_array($query_magazines_category))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_magazines_category['magazines_category_id'])){ ?> <?php echo $fetch_magazines_category['magazines_category_id']; }?>" <?php if(isset($fetch['magazines_category_id']) && $fetch_magazines_category['magazines_category_id'] == $fetch['magazines_category_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_magazines_category['magazines_category_name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesType">Magazines Type</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="magazines_type" id="magazines_type" required>
                                                <option value="">Select Magazines Type</option>
                                                <option value="Daily"<?php if(isset($fetch['magazines_type']) && $fetch['magazines_type'] == 'daily') { ?>selected<?php  } ?>>DAILY</option>
                                                <option value="Weekly"<?php if(isset($fetch['magazines_type']) && $fetch['magazines_type'] == 'weekly') { ?>selected<?php  } ?>>WEEKLY</option>
                                                <option value="Monthly"<?php if(isset($fetch['magazines_type']) && $fetch['magazines_type'] == 'monthly') { ?>selected<?php  } ?>>MONTHLY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesTitle">Magazines Title</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_title" name="magazines_title" placeholder="Enter Magazines Title" value="<?php if(isset($fetch['magazines_title'])){?> <?php echo strtoUpper($fetch['magazines_title']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesName">Magazines Name</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_name" name="magazines_name" placeholder="Enter Magazines Name" value="<?php if(isset($fetch['magazines_name'])){?> <?php echo strtoUpper($fetch['magazines_name']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesPublisher">Magazines Publisher</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_publisher" name="magazines_publisher" placeholder="Enter Magazines Publisher" value="<?php if(isset($fetch['magazines_publisher'])){?> <?php echo strtoUpper($fetch['magazines_publisher']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesDateOfPublish">Magazines Date of Publish</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <div class="input-group date" id="magazines_date_of_publish" name="magazines_date_of_publish" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#magazines_date_of_publish" name="magazines_date_of_publish" placeholder="Enter Magazines Date of Publish" value="<?php if(isset($fetch['magazines_date_of_publish'])) { ?><?php echo $fetch['magazines_date_of_publish']; } ?>" required/>
                                                <div class="input-group-append" data-target="#magazines_date_of_publish" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesDateOfReceipt">Magazines Date of Receipt</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <div class="input-group date" id="magazines_date_of_receipt" name="magazines_date_of_receipt" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#magazines_date_of_receipt" name="magazines_date_of_receipt" placeholder="Enter Magazines Date of Receipt" value="<?php if(isset($fetch['magazines_date_of_receipt'])) { ?><?php echo $fetch['magazines_date_of_receipt']; } ?>" required/>
                                                <div class="input-group-append" data-target="#magazines_date_of_receipt" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesPrice">Magazines Price</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_price" name="magazines_price" placeholder="Enter Magazines Price" value="<?php if(isset($fetch['magazines_price'])){?> <?php echo strtoUpper($fetch['magazines_price']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesTotalPages">Magazines Total Pages</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_total_pages" name="magazines_total_pages" placeholder="Enter Magazines Total Pages" value="<?php if(isset($fetch['magazines_total_pages'])){?> <?php echo strtoUpper($fetch['magazines_total_pages']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesQuantity">Magazines Quantity</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="magazines_quantity" name="magazines_quantity" placeholder="Enter Magazines Quantity" value="<?php if(isset($fetch['magazines_quantity'])){?> <?php echo strtoUpper($fetch['magazines_quantity']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Image Upload</label>
                                            <div class="input-group">
                                                <input type="file" name="magazines_image" id="magazines_image" />
				                                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $fetch['magazines_image']?>" />
                                                <?php
                                                    if(!empty($fetch['magazines_image']))
                                                    {
                                                ?>
                                                        <img src="uploads/magazines/<?php echo $fetch['magazines_image']?>" height="100" width="100"/>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="magazinesStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="magazines_status" name="magazines_status" value="Y" <?php if(isset($fetch['magazines_status']) && $fetch['magazines_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="magazines_status" name="magazines_status" value="N" <?php if(isset($fetch['magazines_status']) && $fetch['magazines_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_magazines_list.php" class="btn btn-info">Cancel</a>
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
        $('#magazines_date_of_publish').datetimepicker({
            format: 'L'
        });
    })

    $(function () {
        $('#magazines_date_of_receipt').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
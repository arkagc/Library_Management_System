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

if(isset($_GET["newspapers_id"])){
    
    $newspapers_id=$_GET["newspapers_id"];
    
    $sql = "SELECT * FROM `newspapers` WHERE newspapers_id=$newspapers_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($newspapers_id))
    {
        $sql_check = "SELECT * FROM `newspapers` WHERE newspapers_name='".strtoLower($_POST["newspapers_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['newspapers_image']['name']=="")
            {
                $mainpic=$_POST['oldimage'];			
            }
            else
            {
                $Imagesfolder="uploads/newspapers/";
                $unlink_sql="SELECT newspapers_image FROM `newspapers` WHERE newspapers_id='".$newspapers_id."'";
                $unlink_rs=mysqli_query($con,$unlink_sql) or mysqli_connect_error();
                $row_unlink=mysqli_fetch_array($unlink_rs);
                $photo=$Imagesfolder.$row_unlink['newspapers_image'];

                if(file_exists($photo))
                {
                    @unlink($photo);
                }

                $path_parts = pathinfo($_FILES['newspapers_image']['name']); 
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['newspapers_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }

            $sql_newspapers = "UPDATE `newspapers` SET 
            newspapers_category_id='".strtoLower($_POST["newspapers_category_id"])."',
            newspapers_type='".strtoLower($_POST["newspapers_type"])."',
            newspapers_title='".strtoLower($_POST["newspapers_title"])."',
            newspapers_name='".strtoLower($_POST["newspapers_name"])."',
            newspapers_publisher='".strtoLower($_POST["newspapers_publisher"])."',
            newspapers_date_of_publish='".strtoLower($_POST["newspapers_date_of_publish"])."',
            newspapers_date_of_receipt='".strtoLower($_POST["newspapers_date_of_receipt"])."',
            newspapers_price='".strtoLower($_POST["newspapers_price"])."',
            newspapers_total_pages='".strtoLower($_POST["newspapers_total_pages"])."',
            newspapers_quantity='".strtoLower($_POST["newspapers_quantity"])."',
            newspapers_modt=CURRENT_TIMESTAMP,
            newspapers_image='".$mainpic."',
            newspapers_status='".$_POST["newspapers_status"]."'
            WHERE newspapers_id='".$newspapers_id."'";

            mysqli_query($con,$sql_newspapers) or mysqli_connect_error();

            echo '<script>window.location.href="manage_newspapers_list.php?newspapers_id='.$newspapers_id.'&msg=update";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_newspapers_list.php?&msg=error";</script>';
        }
    }
    else
    {
        $sql_check = "SELECT * FROM `newspapers` WHERE newspapers_name='".strtoLower($_POST["newspapers_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['newspapers_image']['name']!="")
            {
                $Imagesfolder="uploads/newspapers/";
                $path_parts = pathinfo($_FILES['newspapers_image']['name']);
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['newspapers_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }
            else
            {
                $mainpic="";
            }

            $sql_newspapers = "INSERT INTO `newspapers` SET 
            newspapers_category_id='".strtoLower($_POST["newspapers_category_id"])."',
            newspapers_type='".strtoLower($_POST["newspapers_type"])."',
            newspapers_title='".strtoLower($_POST["newspapers_title"])."',
            newspapers_name='".strtoLower($_POST["newspapers_name"])."',
            newspapers_publisher='".strtoLower($_POST["newspapers_publisher"])."',
            newspapers_date_of_publish='".strtoLower($_POST["newspapers_date_of_publish"])."',
            newspapers_date_of_receipt='".strtoLower($_POST["newspapers_date_of_receipt"])."',
            newspapers_price='".strtoLower($_POST["newspapers_price"])."',
            newspapers_total_pages='".strtoLower($_POST["newspapers_total_pages"])."',
            newspapers_quantity='".strtoLower($_POST["newspapers_quantity"])."',
            newspapers_crdt=CURRENT_TIMESTAMP,
            newspapers_image='".$mainpic."',
            newspapers_status='".$_POST["newspapers_status"]."'
            ";

            mysqli_query($con,$sql_newspapers) or mysqli_connect_error();
            $newspapers_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_newspapers_list.php?newspapers_id='.$newspapers_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_newspapers_list.php?&msg=error";</script>';
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
  <title>LMS | Add / Edit Newspapers</title>
  
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
            <h1 class="m-0">Newspapers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($newspapers_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Newspapers</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Newspapers</li>
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
                              if(isset($newspapers_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Newspapers</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Newspapers</h3>
                            <?php
                              }
                            ?>
                            <a href="all_newspapers_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Newspapers Update Successfully !</strong>
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
                                            <strong>Newspapers Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Newspapers Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersCategory">Newspapers Category</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="newspapers_category_id" id="newspapers_category_id" required>
                                                <option value="">Select Newspapers Category</option>
                                                <?php
                                                    $sql_newspapers_category="SELECT * FROM `newspapers_category` WHERE newspapers_category_status='Y' ";
                                                    $query_newspapers_category=mysqli_query($con,$sql_newspapers_category) or mysqli_connect_error();
                                                    $rows_newspapers_category=mysqli_num_rows($query_newspapers_category);

                                                    if($rows_newspapers_category > 0)
                                                    {
                                                        while($fetch_newspapers_category=mysqli_fetch_array($query_newspapers_category))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_newspapers_category['newspapers_category_id'])){ ?> <?php echo $fetch_newspapers_category['newspapers_category_id']; }?>" <?php if(isset($fetch['newspapers_category_id']) && $fetch_newspapers_category['newspapers_category_id'] == $fetch['newspapers_category_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_newspapers_category['newspapers_category_name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersType">Newspapers Type</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="newspapers_type" id="newspapers_type" required>
                                                <option value="">Select Newspapers Type</option>
                                                <option value="Daily"<?php if(isset($fetch['newspapers_type']) && $fetch['newspapers_type'] == 'daily') { ?>selected<?php  } ?>>DAILY</option>
                                                <option value="Weekly"<?php if(isset($fetch['newspapers_type']) && $fetch['newspapers_type'] == 'weekly') { ?>selected<?php  } ?>>WEEKLY</option>
                                                <option value="Monthly"<?php if(isset($fetch['newspapers_type']) && $fetch['newspapers_type'] == 'monthly') { ?>selected<?php  } ?>>MONTHLY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersTitle">Newspapers Title</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_title" name="newspapers_title" placeholder="Enter Newspapers Title" value="<?php if(isset($fetch['newspapers_title'])){?> <?php echo strtoUpper($fetch['newspapers_title']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersName">Newspapers Name</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_name" name="newspapers_name" placeholder="Enter Newspapers Name" value="<?php if(isset($fetch['newspapers_name'])){?> <?php echo strtoUpper($fetch['newspapers_name']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersPublisher">Newspapers Publisher</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_publisher" name="newspapers_publisher" placeholder="Enter Newspapers Publisher" value="<?php if(isset($fetch['newspapers_publisher'])){?> <?php echo strtoUpper($fetch['newspapers_publisher']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersDateOfPublish">Newspapers Date of Publish</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <div class="input-group date" id="newspapers_date_of_publish" name="newspapers_date_of_publish" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#newspapers_date_of_publish" name="newspapers_date_of_publish" placeholder="Enter Newspapers Date of Publish" value="<?php if(isset($fetch['newspapers_date_of_publish'])) { ?><?php echo $fetch['newspapers_date_of_publish']; } ?>" required/>
                                                <div class="input-group-append" data-target="#newspapers_date_of_publish" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersDateOfReceipt">Newspapers Date of Receipt</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <div class="input-group date" id="newspapers_date_of_receipt" name="newspapers_date_of_receipt" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#newspapers_date_of_receipt" name="newspapers_date_of_receipt" placeholder="Enter Newspapers Date of Receipt" value="<?php if(isset($fetch['newspapers_date_of_receipt'])) { ?><?php echo $fetch['newspapers_date_of_receipt']; } ?>" required/>
                                                <div class="input-group-append" data-target="#newspapers_date_of_receipt" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersPrice">Newspapers Price</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_price" name="newspapers_price" placeholder="Enter Newspapers Price" value="<?php if(isset($fetch['newspapers_price'])){?> <?php echo strtoUpper($fetch['newspapers_price']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersTotalPages">Newspapers Total Pages</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_total_pages" name="newspapers_total_pages" placeholder="Enter Newspapers Total Pages" value="<?php if(isset($fetch['newspapers_total_pages'])){?> <?php echo strtoUpper($fetch['newspapers_total_pages']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersQuantity">Newspapers Quantity</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="newspapers_quantity" name="newspapers_quantity" placeholder="Enter Newspapers Quantity" value="<?php if(isset($fetch['newspapers_quantity'])){?> <?php echo strtoUpper($fetch['newspapers_quantity']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Image Upload</label>
                                            <div class="input-group">
                                                <input type="file" name="newspapers_image" id="newspapers_image" />
				                                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $fetch['newspapers_image']?>" />
                                                <?php
                                                    if(!empty($fetch['newspapers_image']))
                                                    {
                                                ?>
                                                        <img src="uploads/newspapers/<?php echo $fetch['newspapers_image']?>" height="100" width="100"/>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="newspapersStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="newspapers_status" name="newspapers_status" value="Y" <?php if(isset($fetch['newspapers_status']) && $fetch['newspapers_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="newspapers_status" name="newspapers_status" value="N" <?php if(isset($fetch['newspapers_status']) && $fetch['newspapers_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_newspapers_list.php" class="btn btn-info">Cancel</a>
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
        $('#newspapers_date_of_publish').datetimepicker({
            format: 'L'
        });
    })

    $(function () {
        $('#newspapers_date_of_receipt').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
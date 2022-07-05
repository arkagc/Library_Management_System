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

if(isset($_GET["user_id"])){
    
    $user_id=$_GET["user_id"];
    
    $sql = "SELECT * FROM `users` WHERE user_id=$user_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($user_id))
    {   
        if($_FILES['user_image']['name']=="")
        {
            $mainpic=$_POST['oldimage'];			
        }
        else
        {
            $Imagesfolder="uploads/student/";
            $unlink_sql="SELECT user_image FROM `users` WHERE user_id='".$user_id."'";
            $unlink_rs=mysqli_query($con,$unlink_sql) or mysqli_connect_error();
            $row_unlink=mysqli_fetch_array($unlink_rs);
            $photo=$Imagesfolder.$row_unlink['user_image'];

            if(file_exists($photo))
            {
                @unlink($photo);
            }

            $path_parts = pathinfo($_FILES['user_image']['name']); 
            $file_name = $path_parts['filename'];
            $file_extn = $path_parts['extension'];
            $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
            $temp = $_FILES['user_image']['tmp_name'];
            $upload  = $Imagesfolder.$mainpic; 
            @copy($temp,$upload);
        }


        // if($rows_check==0)
        // {
            $sql_std = "UPDATE `users` SET 
            user_library_no='".strtoLower($_POST["user_library_no"])."',
            user_name='".strtoLower($_POST["user_name"])."',
            dept_id='".$_POST["dept_id"]."',
            dept_yr_id='".$_POST["dept_yr_id"]."',
            user_roll_no='".strtoLower($_POST["user_roll_no"])."',
            user_registration_no='".strtoLower($_POST["user_registration_no"])."',
            user_mobile='".$_POST["user_mobile"]."',
            user_email='".strtoLower($_POST["user_email"])."',
            user_dob='".$_POST["user_dob"]."',
            user_blood_group='".strtoLower($_POST["user_blood_group"])."',
            user_image='".$mainpic."',
            user_father_name='".strtoLower($_POST["user_father_name"])."',
            user_mother_name='".strtoLower($_POST["user_mother_name"])."',
            user_address='".strtoLower($_POST["user_address"])."',
            user_state='".strtoLower($_POST["user_state"])."',
            user_city='".strtoLower($_POST["user_city"])."',
            user_pincode='".$_POST["user_pincode"]."',
            user_modt=CURRENT_TIMESTAMP,
            user_status='".$_POST["user_status"]."'
            WHERE user_id='".$user_id."'";

            mysqli_query($con,$sql_std) or mysqli_connect_error();

            echo '<script>window.location.href="manage_member.php?user_id='.$user_id.'&msg=update";</script>';
    }
    else
    {
        $sql_check = "SELECT * FROM `users` WHERE user_library_no='".strtoLower($_POST["user_library_no"])."' || user_roll_no='".strtoLower($_POST["user_roll_no"])."' || user_registration_no='".strtoLower($_POST["user_registration_no"])."' || user_email='".strtoLower($_POST["user_email"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($_FILES['user_image']['name']!="")
        {
            $Imagesfolder="uploads/student/";
			$path_parts = pathinfo($_FILES['user_image']['name']);
			$file_name = $path_parts['filename'];
			$file_extn = $path_parts['extension'];
			$mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
			$temp = $_FILES['user_image']['tmp_name'];
			$upload  = $Imagesfolder.$mainpic; 
			@copy($temp,$upload);
        }
        else
		{
			$mainpic="";
		}
        
        if($rows_check==0)
        {
            $sql_std = "INSERT INTO `users` SET 
            user_login_id='".$_POST["user_login_id"]."',
            user_password='".md5($_POST["user_password"])."',
            user_library_no='".strtoLower($_POST["user_library_no"])."',
            user_name='".strtoLower($_POST["user_name"])."',
            dept_id='".$_POST["dept_id"]."',
            dept_yr_id='".$_POST["dept_yr_id"]."',
            user_roll_no='".strtoLower($_POST["user_roll_no"])."',
            user_registration_no='".strtoLower($_POST["user_registration_no"])."',
            user_mobile='".$_POST["user_mobile"]."',
            user_email='".strtoLower($_POST["user_email"])."',
            user_dob='".$_POST["user_dob"]."',
            user_blood_group='".strtoLower($_POST["user_blood_group"])."',
            user_image='".$mainpic."',
            user_father_name='".strtoLower($_POST["user_father_name"])."',
            user_mother_name='".strtoLower($_POST["user_mother_name"])."',
            user_address='".strtoLower($_POST["user_address"])."',
            user_state='".strtoLower($_POST["user_state"])."',
            user_city='".strtoLower($_POST["user_city"])."',
            user_pincode='".$_POST["user_pincode"]."',
            user_crdt=CURRENT_TIMESTAMP,
            user_status='".$_POST["user_status"]."'
            ";

            mysqli_query($con,$sql_std) or mysqli_connect_error();
            $user_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_member.php?user_id='.$user_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_member.php?&msg=error";</script>';
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
  <title>LMS | Add / Edit Member</title>
  
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
            <h1 class="m-0">Member</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($user_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Member</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Member</li>
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
                                if(isset($user_id))
                                {
                            ?>
                                    <h3 class="card-title">Edit Member</h3>
                            <?php
                                }
                                else
                                {
                            ?>
                                    <h3 class="card-title">Add Member</h3>
                            <?php
                                }
                            ?>
                            <a href="all_member.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Student Update Successfully !</strong>
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
                                            <strong>Student Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Student Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userName">Student Name</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Student Name" value="<?php if(isset($fetch['user_name'])){?> <?php echo strtoUpper($fetch['user_name']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userLibraryNo">Library No</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_library_no" name="user_library_no" placeholder="Enter Library No" value="<?php if(isset($fetch['user_library_no'])){?> <?php echo strtoUpper($fetch['user_library_no']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userLoginId">Student User ID</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_login_id" name="user_login_id" placeholder="Enter User ID" value="<?php if(isset($fetch['user_login_id'])){?> <?php echo $fetch['user_login_id']; }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userPassword">Password</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter Password" value="<?php if(isset($fetch['user_password'])){?> <?php echo $fetch['user_password']; }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userDept">Department Code</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="dept_id" id="dept_id" required>
                                                <option value="">Select Department Code</option>
                                                <?php
                                                    $sql_dept="SELECT * FROM `department` WHERE dept_status='Y'";
                                                    $query_dept=mysqli_query($con,$sql_dept) or mysqli_connect_error();
                                                    $rows_dept=mysqli_num_rows($query_dept);

                                                    if($rows_dept > 0)
                                                    {
                                                        while($fetch_dept=mysqli_fetch_array($query_dept))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_dept['dept_id'])){ ?> <?php echo $fetch_dept['dept_id']; }?>" <?php if(isset($fetch['dept_id']) && $fetch_dept['dept_id'] == $fetch['dept_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_dept['dept_code']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userYear">Department Year</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="dept_yr_id" id="dept_yr_id" required>
                                                <option value="">Select Department Year</option>
                                                    <?php
                                                    if(!empty($fetch['dept_id']))
                                                    {
                                                        if(isset($_REQUEST['user_id']))
                                                        {
                                                            $dept_yr_id = $fetch['dept_yr_id'];

                                                            $sql_dept_yr="SELECT * FROM `department_year` WHERE dept_yr_id ='".$dept_yr_id."'";
                                                            $query_dept_yr=mysqli_query($con,$sql_dept_yr) or mysqli_connect_error();
                                                            $fetch_dept_yr=mysqli_fetch_array($query_dept_yr);
                                                    ?>
                                                            <option value="<?php echo $fetch_dept_yr['dept_yr_id']; ?>" <?php if($fetch_dept_yr['dept_yr_id'] == $fetch['dept_yr_id']) echo "selected"; ?>><?php echo strtoUpper($fetch_dept_yr['dept_yr_name']); ?></option>

                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userRollNo">Roll No</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_roll_no" name="user_roll_no" placeholder="Enter Roll No" value="<?php if(isset($fetch['user_roll_no'])){?> <?php echo $fetch['user_roll_no']; }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userRegNo">Registration No</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_registration_no" name="user_registration_no" placeholder="Enter Registration No" value="<?php if(isset($fetch['user_registration_no'])){?> <?php echo strtoUpper($fetch['user_registration_no']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userEmail">Email ID</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter Email ID" value="<?php if(isset($fetch['user_email'])){?> <?php echo $fetch['user_email']; }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userMobile">Mobile No</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="user_mobile" name="user_mobile" placeholder="Enter Mobile No" value="<?php if(isset($fetch['user_mobile'])){?> <?php echo $fetch['user_mobile']; }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userDob">Date of Birth</label>
                                            <div class="input-group date" id="user_dob" name="user_dob" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#user_dob" name="user_dob" placeholder="Enter Date of Birth" value="<?php if(isset($fetch['user_dob'])) { ?><?php echo $fetch['user_dob']; } ?>"/>
                                                <div class="input-group-append" data-target="#user_dob" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userBloodGrp">Blood Group</label>
                                            <input type="text" class="form-control" id="user_blood_group" name="user_blood_group" placeholder="Enter Blood Group" value="<?php if(isset($fetch['user_blood_group'])){?> <?php echo strtoUpper($fetch['user_blood_group']); }?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="UserFatherName">Father's Name</label>
                                            <input type="text" class="form-control" id="user_father_name" name="user_father_name" placeholder="Enter Father's Name" value="<?php if(isset($fetch['user_father_name'])){?> <?php echo strtoUpper($fetch['user_father_name']); }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userMotherName">Mother's Name</label>
                                            <input type="text" class="form-control" id="user_mother_name" name="user_mother_name" placeholder="Enter Mother's Name" value="<?php if(isset($fetch['user_mother_name'])){?> <?php echo strtoUpper($fetch['user_mother_name']); }?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userAddress">Address</label>
                                            <input type="text" class="form-control" id="user_address" name="user_address" placeholder="Enter Address" value="<?php if(isset($fetch['user_address'])){?> <?php echo strtoUpper($fetch['user_address']); }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userState">State</label>
                                            <input type="text" class="form-control" id="user_state" name="user_state" placeholder="Enter State" value="<?php if(isset($fetch['user_state'])){?> <?php echo strtoUpper($fetch['user_state']); }?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userCity">City</label>
                                            <input type="text" class="form-control" id="user_city" name="user_city" placeholder="Enter City" value="<?php if(isset($fetch['user_city'])){?> <?php echo strtoUpper($fetch['user_city']); }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userPincode">Pincode</label>
                                            <input type="text" class="form-control" id="user_pincode" name="user_pincode" placeholder="Enter Pincode" value="<?php if(isset($fetch['user_pincode'])){?> <?php echo $fetch['user_pincode']; }?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Image Upload</label>
                                            <div class="input-group">
                                                <input type="file" name="user_image" id="user_image" />
				                                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $fetch['user_image']?>" />
                                                <?php
                                                    if(!empty($fetch['user_image']))
                                                    {
                                                ?>
                                                        <img src="uploads/student/<?php echo $fetch['user_image']?>" height="100" width="100"/>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="userStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="user_status" name="user_status" value="Y" <?php if(isset($fetch['user_status']) && $fetch['user_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="user_status" name="user_status" value="N" <?php if(isset($fetch['user_status']) && $fetch['user_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_member.php" class="btn btn-info">Cancel</a>
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
    $(document).ready(function(){
        $(document).on('change', '#dept_id', function(){
            var dept_id = $(this).val();
            if(dept_id.length != 0){
                $.ajax({
                    type: 'POST',
                    url: 'call_ajax.php',
                    cache: 'false',
                    data: {dept_id:dept_id},
                    success: function(data){
                        $('#dept_yr_id').html(data);
                    },

                    error: function(jqXHR, textStatus, errorThrown){
                        // error
                    }
                });
            }else{
                $('#dept_yr_id').html('<option value=""> -- Select Department Year -- </option>');
            }
        });
    });

    //Date picker
    $(function () {
        $('#user_dob').datetimepicker({
            format: 'L'
        });
    })
</script>
</body>
</html>
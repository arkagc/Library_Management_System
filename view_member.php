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

//Fetch member
if(isset($_GET["user_id"])){
    
    $user_id=$_GET["user_id"];
    
    $sql = "SELECT * FROM `users` WHERE user_id=$user_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
    if(!empty($fetch['dept_id']))
    {
        //fetch from `department` table using `users` table
        $sql_dept  = mysqli_query($con,"SELECT * FROM `department` WHERE dept_id='".$fetch['dept_id']."'");
        $fetch_dept = mysqli_fetch_array($sql_dept);

        //fetch from `department_year` table using `department` table
        $sql_dept_yr  = mysqli_query($con,"SELECT * FROM `department_year` WHERE dept_id='".$fetch_dept['dept_id']."'");
        $fetch_dept_yr = mysqli_fetch_array($sql_dept_yr);   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | View Member</title>
  
  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
              <li class="breadcrumb-item active">View Member</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content 1 -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">View Member</h3>
                            <a href="all_member.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td> <?php echo 'Student Name';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_name']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Student Id';?></td>
                                        <td> <?php echo $fetch['user_login_id'];?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Library No.';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_library_no']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Department Full Name';?></td>
                                        <td> 
                                            <?php
                                                if(!empty($fetch['dept_id']))
                                                {
                                                    echo strtoUpper($fetch_dept['dept_name']);
                                                }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Department Code';?></td>
                                        <td> 
                                            <?php
                                                if(!empty($fetch['dept_id']))
                                                {
                                                    echo strtoUpper($fetch_dept['dept_code']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td> <?php echo 'Department Year';?></td>
                                        <td> 
                                            <?php
                                                if(!empty($fetch['dept_id']))
                                                {
                                                    echo strtoUpper($fetch_dept_yr['dept_yr_name']);
                                                }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Roll No.';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_roll_no']);?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td> <?php echo 'Registration No.';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_registration_no']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Mobile';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_mobile']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Email';?></td>
                                        <td> <?php echo $fetch['user_email'];?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Date of Birth';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_dob']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Blood Group';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_blood_group']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Father Name';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_father_name']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Mother Name';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_mother_name']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Address';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_address']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'State';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_state']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'City';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_city']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Pincode';?></td>
                                        <td> <?php echo strtoUpper($fetch['user_pincode']);?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td> <?php echo 'Image';?></td>
                                        <td>
                                            <?php
                                            if(!empty($fetch['user_image']))
                                            {
                                            ?>
                                                <img src="uploads/student/<?php echo $fetch['user_image'];?>" height="100" width="100">
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <img src="uploads/student/icon.png" height="100" width="100">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Status';?></td>
                                        <td> <?php if($fetch['user_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{ ?><span class="badge badge-danger">Inactive</span><?php }?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
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
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
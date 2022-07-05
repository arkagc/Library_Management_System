<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}
elseif($_SESSION['UserType']=='admin')
{
  header('location:dashboard.php');
}

if(isset($_SESSION['UserId'])){
    
  $user_id=$_SESSION['UserId'];
  
  $sql = "SELECT * FROM `users` WHERE user_id=$user_id"; 
  $query = mysqli_query($con,$sql) or mysqli_connect_error();
  $fetch = mysqli_fetch_array($query);
  
  //fetch department
  $sql_dept = "SELECT * FROM `department` WHERE dept_id='".$fetch['dept_id']."' "; 
  $query_dept = mysqli_query($con,$sql_dept) or mysqli_connect_error();
  $fetch_dept = mysqli_fetch_array($query_dept);

  //fetch department year
  $sql_dept_yr = "SELECT * FROM `department_year` WHERE dept_yr_id='".$fetch['dept_yr_id']."' "; 
  $query_dept_yr = mysqli_query($con,$sql_dept_yr) or mysqli_connect_error();
  $fetch_dept_yr = mysqli_fetch_array($query_dept_yr);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Student Profile</title>

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
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Student</li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <?php
                    if(!empty($fetch['user_image']))
                    {
                  ?>
                      <img class="profile-user-img img-fluid img-circle" src="uploads/student/<?php echo $fetch['user_image'];?>" alt="User profile picture">
                  <?php
                    }
                    else
                    {
                  ?>
                      <img class="profile-user-img img-fluid img-circle" src="uploads/student/icon.png" alt="User profile picture">
                  <?php
                    }
                  ?>
                </div>

                <h3 class="profile-username text-center"><?php echo ucwords($fetch['user_name'], " "); ?></h3>

                <p class="text-muted text-center"><b>Library No. - <?php echo strtoUpper($fetch['user_library_no']); ?></b></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Department Full Name</b> <p class="float-right"><?php echo strtoUpper($fetch_dept['dept_name']); ?></p>
                  </li>
                  <li class="list-group-item">
                    <b>Department Code</b> <p class="float-right"><?php echo strtoUpper($fetch_dept['dept_code']); ?></p>
                  </li>
                  <li class="list-group-item">
                    <b>Department Year</b> <p class="float-right"><?php echo strtoUpper($fetch_dept_yr['dept_yr_name']); ?></p>
                  </li>
                  <li class="list-group-item">
                    <b>Roll No.</b> <p class="float-right"><?php echo strtoUpper($fetch['user_roll_no']); ?></p>
                  </li>
                  <li class="list-group-item">
                    <b>Registration No.</b> <p class="float-right"><?php echo strtoUpper($fetch['user_registration_no']); ?></p>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-address-book mr-1"></i> Contact Details</strong>
                <p class="text-muted"><b>Email : <?php echo $fetch['user_email']; ?> , Mobile : <?php echo $fetch['user_mobile']; ?></b></p>
                
                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                <p class="text-muted"><b>Address : <?php echo ucwords($fetch['user_address']," "); ?> , State : <?php echo ucwords($fetch['user_state'], " "); ?> , City : <?php echo ucwords($fetch['user_city'], " "); ?> , Pincode : <?php echo $fetch['user_pincode']; ?></b></p>

                <hr>

                <strong><i class="fas fa-address-card mr-1"></i> Personal Details</strong>
                <p class="text-muted"><b>Date of Birth : <?php echo $fetch['user_dob']; ?> , Blood Group : <?php echo strtoUpper($fetch['user_blood_group']); ?></b></p>

                <hr>

                <strong><i class="fas fa-user-friends mr-1"></i> Parents Name</strong>
                <p class="text-muted"><b>Father's Name : <?php echo ucwords($fetch['user_father_name']); ?> , Mother's Name : <?php echo ucwords($fetch['user_mother_name']); ?></b></p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <!-- Footer -->
  <?php include("includes/footer.php"); ?>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
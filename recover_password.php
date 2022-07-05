<?php
include("connection.php"); //For connection to database

//Session Check
if(!isset($_SESSION['UserId']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_POST['change_pass']))
{
  $Password=($_POST['Password']);
  $ConfirmPassword=($_POST['CnfPassword']);

  if($Password==$ConfirmPassword)
  {
    $sql="UPDATE `users` SET user_password='".md5($Password)."' WHERE user_id='".$_SESSION['UserId']."'";
    mysqli_query($con,$sql) or mysqli_connect_error();
    echo '<script>window.location.href="index.php?msg=success";</script>';
    session_unset(); 
    session_destroy();
  }
  else
  {
    echo '<script>window.location.href="recover_password.php?msg=notmatch";</script>';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Recover Password</title>

  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" background="images/lms_background.jpg">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <h1><b>LMS</b></h1>
      <h5><b>Library Management System</b></h5>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="Password" id="Password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="CnfPassword" id="CnfPassword" placeholder="Confirm Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" name="change_pass">Change password</button>
          </div>
        </div>
      </form>

      <div class="row">
        <div class="col-9">
          <span class="fas fa-arrow-left"></span>
          <a href="forgot_password.php">Back</a>
        </div>

        <div class="col-3">
          <span class="fas fa-sign-in-alt"></span>
          <a href="index.php">Sign in</a>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div class="row">
      <div class="col-12">
        <div class="login-box-msg">
          <?php 
            if(isset($_GET['msg']) && $_GET['msg'] == 'notmatch')
            { 
          ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5>Password not match</h5>
            </div>
          <?php 
            }
            if(isset($_GET['msg']) && $_GET['msg'] == 'success')
            { 
          ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5>Update Successfully !</h5>
            </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
    
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>

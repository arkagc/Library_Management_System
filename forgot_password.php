<?php
include("connection.php"); //For connection to database

$message='';
if(isset($_POST['forgot_pass']))
{
  $UserId=($_POST['UserId']);

  $sql_login="SELECT * FROM `users` WHERE user_login_id='".$UserId."' ";
  $res_login=mysqli_query($con,$sql_login) or mysqli_connect_error();
  $rows_count = mysqli_num_rows($res_login);

  if($rows_count>0)
  {
    $fetchinfo=mysqli_fetch_array($res_login);

    $_SESSION['UserId']=$fetchinfo['user_id'];
    $_SESSION['UserLogId']=$fetchinfo['user_login_id'];
    $_SESSION['UserType']=$fetchinfo['user_type'];

    echo "<script>window.location.href='recover_password.php';</script>";
  }
  else
  {
    $message = "Invalid User ID";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Forgot Password</title>

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
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="UserId" id="UserId" placeholder="User ID" required value="<?php if(isset($_SESSION['UserLogId'])){ echo $_SESSION['UserLogId']; }else{ echo ''; } ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" name="forgot_pass">Request new password</button>
          </div>
        </div>
      </form>
      <p class="mt-3 mb-1">
        <span class="fas fa-sign-in-alt"></span>
        <a href="index.php">Sign in</a>
      </p>
    </div>

    <!-- Error Message -->
    <div class="row">
      <div class="col-12">
        <div class="login-box-msg">
          <?php if($message!=""){ ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><?php echo $message; ?></h5>
            </div>
          <?php } ?>
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
<?php
include("connection.php"); //For connection to database

//Session Check
if(isset($_SESSION['UserType']) == 'admin')
{
  echo "<script>window.location.href='dashboard.php'</script>";
}
elseif(isset($_SESSION['UserType']) == 'student')
{
  echo "<script>window.location.href='student_dashboard.php'</script>";
}

$message='';
if(isset($_POST['adminLogin']))
{
  $AdminUserName=($_POST['AdminUserName']);
	$AdminPassword=($_POST['AdminPassword']);

  $encrpt_password = md5($AdminPassword);
  
  //remember me check
  if(isset($_POST["remember_me"])=='1' || isset($_POST["remember_me"])=='on')
  { 
    $hour = time() + 1 * 60 * 60; //using expiry in 1 hour(1*60*60 seconds or 3600 seconds)
    setcookie('username', $AdminUserName, $hour);
    setcookie('password', $AdminPassword, $hour);
  }

  $sql_login="SELECT * FROM `users` WHERE user_login_id='".$AdminUserName."' and user_password='".$encrpt_password."' and user_status='Y' ";
  $res_login=mysqli_query($con,$sql_login) or mysqli_connect_error();
  $rows_count = mysqli_num_rows($res_login);

  if($rows_count>0)
  {
    $fetchinfo=mysqli_fetch_array($res_login);

    $_SESSION['UserType']=$fetchinfo['user_type'];
		$_SESSION['UserName']=$fetchinfo['user_name'];
		$_SESSION['UserId']=$fetchinfo['user_id'];
    
    if($_SESSION['UserType']!='admin')
    {
      echo "<script>window.location.href='student_dashboard.php';</script>";
    }
    else
    {
      echo "<script>window.location.href='dashboard.php';</script>";
    }
  }
  else
  {
    $message = "Invalid User ID or Password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Log in</title>

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
      <p class="login-box-msg">Sign in to LMS</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="AdminUserName" id="AdminUserName" placeholder="User ID" autofocus required value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username']; }?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="AdminPassword" id="AdminPassword" placeholder="Password" required value="<?php if(isset($_COOKIE['password'])){ echo $_COOKIE['password']; }?>"> 
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember_me" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="adminLogin">Sign In</button>
          </div>
        </div>
      </form>

      <p class="mb-1">
        <span class="fas fa-key"></span>
        <a href="forgot_password.php">I forgot my password</a>
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
          <?php }if(isset($_GET['msg']) && $_GET['msg'] == 'success'){ ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5>Password Update Successfully !</h5>
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
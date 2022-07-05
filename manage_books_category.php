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

if(isset($_GET["books_category_id"])){
    
    $books_category_id=$_GET["books_category_id"];
  
    $sql = "SELECT * FROM `books_category` WHERE books_category_id=$books_category_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($books_category_id))
    {
        $sql_check = "SELECT * FROM `books_category` WHERE books_category_name='".strtoLower($_POST["books_category_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            $sql_dept = "UPDATE `books_category` SET 
            books_category_name='".strtoLower($_POST["books_category_name"])."',
            books_category_modt=CURRENT_TIMESTAMP,
            books_category_status='".$_POST["books_category_status"]."'
            WHERE books_category_id='".$books_category_id ."'";

            mysqli_query($con,$sql_dept) or mysqli_connect_error();

            echo '<script>window.location.href="manage_books_category.php?books_category_id='.$books_category_id.'&msg=update";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_books_category.php?&msg=error";</script>';
        }
    }
    else
    {
        $sql_check = "SELECT * FROM `books_category` WHERE books_category_name='".strtoLower($_POST["books_category_name"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            $sql_dept = "INSERT INTO `books_category` SET 
            books_category_name='".strtoLower($_POST["books_category_name"])."',
            books_category_crdt=CURRENT_TIMESTAMP,
            books_category_status='".$_POST["books_category_status"]."'
            ";

            mysqli_query($con,$sql_dept) or mysqli_connect_error();
            $books_category_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_books_category.php?books_category_id='.$books_category_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_books_category.php?&msg=error";</script>';
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
  <title>LMS | Add / Edit Books Category</title>
  
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
            <h1 class="m-0">Books Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($books_category_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Books Category</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Books Category</li>
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
                              if(isset($books_category_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Books Category</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Books Category</h3>
                            <?php
                              }
                            ?>
                            <a href="all_books_category.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                        <strong>Books Category Update Successfully !</strong>
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
                                            <strong>Books Category Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Books Category Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                  <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksCategory">Books Category</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_category_name" name="books_category_name" placeholder="Enter Books Category" value="<?php if(isset($fetch['books_category_name'])){?> <?php echo strtoUpper($fetch['books_category_name']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksCategoryStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="books_category_status" name="books_category_status" value="Y" <?php if(isset($fetch['books_category_status']) && $fetch['books_category_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="books_category_status" name="books_category_status" value="N" <?php if(isset($fetch['books_category_status']) && $fetch['books_category_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_books_category.php" class="btn btn-info">Cancel</a>
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
<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["newspapers_issue_id"])){
    
    $newspapers_issue_id=$_GET["newspapers_issue_id"];
    
    $sql = "SELECT * FROM `newspapers_issue` WHERE newspapers_issue_id=$newspapers_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
    //fetch from `newspapers` table using `newspapers_issue` table
    $sql_newspapers  = mysqli_query($con,"SELECT * FROM `newspapers` WHERE newspapers_id='".$fetch['newspapers_id']."'");
    $fetch_newspapers = mysqli_fetch_array($sql_newspapers);

    //fetch from `newspapers_category` table using `newspapers` table
    $sql_newspapers_category  = mysqli_query($con,"SELECT * FROM `newspapers_category` WHERE newspapers_category_id='".$fetch_newspapers['newspapers_category_id']."'");
    $fetch_newspapers_category = mysqli_fetch_array($sql_newspapers_category);

    //fetch from `users` table using `newspapers_issue` table
    $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$fetch['user_id']."'");
    $fetch_users = mysqli_fetch_array($sql_users);

    //fetch from `department` table using `users` table
    $sql_dept  = mysqli_query($con,"SELECT * FROM `department` WHERE dept_id='".$fetch_users['dept_id']."'");
    $fetch_dept = mysqli_fetch_array($sql_dept);

    //fetch from `department_year` table using `users` table
    $sql_dept_yr  = mysqli_query($con,"SELECT * FROM `department_year` WHERE dept_yr_id='".$fetch_users['dept_yr_id']."'");
    $fetch_dept_yr = mysqli_fetch_array($sql_dept_yr);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | View Newspapers Issue</title>
  
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
            <h1 class="m-0">Newspapers Issue</h1>
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
              <li class="breadcrumb-item active">View Newspapers Issue</li>
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
                            <h3 class="card-title">View Newspapers Issue</h3>
                            <a href="all_newspapers_issue_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <?php
                        if($_SESSION['UserType']=='admin')
                        {
                        ?>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td> <?php echo 'Student Name';?></td>
                                            <td> <?php echo strtoUpper($fetch_users['user_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Department Name';?></td>
                                            <td> 
                                                <?php
                                                if(!empty($fetch_users['dept_id']))
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
                                                if(!empty($fetch_users['dept_id']))
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
                                                if(!empty($fetch_users['dept_id']))
                                                {
                                                    echo strtoUpper($fetch_dept_yr['dept_yr_name']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Category';?></td>
                                            <td> 
                                                <?php
                                                if(!empty($fetch_newspapers['newspapers_category_id']))
                                                {
                                                    echo strtoUpper($fetch_newspapers_category['newspapers_category_name']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Type';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_type']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Title';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Name';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Publisher';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_publisher']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Price';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_price']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Total Pages';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_total_pages']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Quantity';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_quantity']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'No of Newspapers Issue';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_no_of_newspapers']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Issue Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Return Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_return_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Issue Status';?></td>
                                            <td> <?php if($fetch['newspapers_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch['newspapers_issue_status']=='NI'){ ?><span class="badge badge-danger">Rejected</span><?php }else{?><span class="badge badge-success">Issue</span><?php }?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        <?php
                        }
                        else
                        {
                        ?>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                            <td> <?php echo 'Newspapers Category';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers_category['newspapers_category_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Type';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_type']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Title';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Name';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Price';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_price']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Total Pages';?></td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_total_pages']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'No of Newspapers Issue';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_no_of_newspapers']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Issue Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Return Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['newspapers_issue_return_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Newspapers Issue Status';?></td>
                                            <td> <?php if($fetch['newspapers_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch['newspapers_issue_status']=='NI'){ ?><span class="badge badge-danger">Rejected</span><?php }else{?><span class="badge badge-success">Issue</span><?php }?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        <?php
                        }
                        ?>
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
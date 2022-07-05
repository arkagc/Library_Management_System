<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

if(isset($_GET["magazines_issue_id"])){
    
    $magazines_issue_id=$_GET["magazines_issue_id"];
    
    $sql = "SELECT * FROM `magazines_issue` WHERE magazines_issue_id=$magazines_issue_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
    //fetch from `magazines` table using `magazines_issue` table
    $sql_magazines  = mysqli_query($con,"SELECT * FROM `magazines` WHERE magazines_id='".$fetch['magazines_id']."'");
    $fetch_magazines = mysqli_fetch_array($sql_magazines);

    //fetch from `magazines_category` table using `magazines` table
    $sql_magazines_category  = mysqli_query($con,"SELECT * FROM `magazines_category` WHERE magazines_category_id='".$fetch_magazines['magazines_category_id']."'");
    $fetch_magazines_category = mysqli_fetch_array($sql_magazines_category);

    //fetch from `users` table using `magazines_issue` table
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
  <title>LMS | View Magazines Issue</title>
  
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
            <h1 class="m-0">Magazines Issue</h1>
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
              <li class="breadcrumb-item active">View Magazines Issue</li>
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
                            <h3 class="card-title">View Magazines Issue</h3>
                            <a href="all_magazines_issue_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
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
                                            <td> <?php echo 'Magazines Category';?></td>
                                            <td> 
                                                <?php
                                                if(!empty($fetch_magazines['magazines_category_id']))
                                                {
                                                    echo strtoUpper($fetch_magazines_category['magazines_category_name']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Type';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_type']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Title';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_title']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Name';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Publisher';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_publisher']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Price';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_price']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazaines Total Pages';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_total_pages']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Quantity';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_quantity']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'No of Magazines Issue';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_no_of_magazines']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Issue Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Return Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_return_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Issue Status';?></td>
                                            <td> <?php if($fetch['magazines_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch['magazines_issue_status']=='NI'){ ?><span class="badge badge-danger">Rejected</span><?php }else{?><span class="badge badge-success">Issue</span><?php }?></td>
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
                                            <td> <?php echo 'Magazines Type';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_type']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Title';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_title']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Name';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_name']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Price';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_price']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazaines Total Pages';?></td>
                                            <td> <?php echo strtoUpper($fetch_magazines['magazines_total_pages']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'No of Magazines Issue';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_no_of_magazines']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Issue Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Return Date';?></td>
                                            <td> <?php echo strtoUpper($fetch['magazines_issue_return_date']);?></td>
                                        </tr>
                                        <tr>
                                            <td> <?php echo 'Magazines Issue Status';?></td>
                                            <td> <?php if($fetch['magazines_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch['magazines_issue_status']=='NI'){ ?><span class="badge badge-danger">Rejected</span><?php }else{?><span class="badge badge-success">Issue</span><?php }?></td>
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
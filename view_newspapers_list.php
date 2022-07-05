<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch department
if(isset($_GET["newspapers_id"])){
    
    $newspapers_id=$_GET["newspapers_id"];
    
    $sql = "SELECT * FROM `newspapers` WHERE newspapers_id=$newspapers_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
    //fetch from `newspapers_category` table using `newspapers` table
    $sql_newspapers_category  = mysqli_query($con,"SELECT * FROM `newspapers_category` WHERE newspapers_category_id='".$fetch['newspapers_category_id']."'");
    $fetch_newspapers_category = mysqli_fetch_array($sql_newspapers_category);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | View Newspapers</title>
  
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
            <h1 class="m-0">Newspapers</h1>
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
              <li class="breadcrumb-item active">View Newspapers</li>
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
                            <h3 class="card-title">View Newspapers</h3>
                            <a href="all_newspapers_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td> <?php echo 'Newspapers Category';?></td>
                                        <td> 
                                            <?php
                                                if(!empty($fetch['newspapers_category_id']))
                                                {
                                                    echo strtoUpper($fetch_newspapers_category['newspapers_category_name']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Type';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_type']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Title';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_title']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Name';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_name']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Publisher';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_publisher']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Date of Publish';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_date_of_publish']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Date of Receipt';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_date_of_receipt']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Price';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_price']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Total Pages';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_total_pages']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Newspapers Quantity';?></td>
                                        <td> <?php echo strtoUpper($fetch['newspapers_quantity']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Image';?></td>
                                        <td>
                                            <?php
                                            if(!empty($fetch['newspapers_image']))
                                            {
                                            ?>
                                                <img src="uploads/newspapers/<?php echo $fetch['newspapers_image'];?>" height="100" width="100">
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <img src="uploads/newspapers/newspaper_icon.png" height="100" width="100">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Newspapers Status';?></td>
                                        <td> <?php if($fetch['newspapers_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{ ?><span class="badge badge-danger">Inactive</span><?php }?></td>
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
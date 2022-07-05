<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch all newspapers for admin & students
if($_SESSION['UserType']=='admin')
{
  $sql_newspapers = "SELECT * FROM `newspapers` ORDER BY newspapers_id";
}
else
{
  $sql_newspapers = "SELECT * FROM `newspapers` WHERE newspapers_status='Y' ORDER BY newspapers_id";
}
$query_newspapers = mysqli_query($con,$sql_newspapers) or mysqli_connect_error();
$rows_newspapers = mysqli_num_rows($query_newspapers);

//For Delete
if(isset($_GET['newspapers_del_id']))
{
    $sqlUpdt = "DELETE FROM `newspapers` WHERE newspapers_id='".$_GET['newspapers_del_id']."'";
    $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();

    echo "<script>window.location.href='all_newspapers_list.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | All Newspapers List</title>
  
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
            <h1 class="m-0">All Newspapers List</h1>
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
              <li class="breadcrumb-item active">All Newspapers List</li>
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
                    <div class="card">
                        <div class="card-header">
                          <?php
                            if($_SESSION['UserType']=='admin')
                            {
                          ?>
                              <a href="manage_newspapers_list.php" class="card-title"><button type="button" class="btn btn-block btn-primary"><i class="fas fa-newspaper"></i>  Add New Newspapers</button></a>
                          <?php
                            }
                          ?>
                        </div>
                        <!-- /.card-header -->
                        <?php
                          if($_SESSION['UserType']=='admin')
                          {
                        ?>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="color:black; width:80px;">Newspaper Image</th>
                                        <th style="color:black; width:20px;">Newspapers Category</th>
                                        <th style="color:black; width:20px;">Newpapers Title</th>
                                        <th style="color:black; width:20px;">Newspapers Price</th>
                                        <th style="color:black; width:20px;">Newspapers Quantity</th>
                                        <th style="color:black; width:10px;">View</th>
                                        <th style="color:black; width:10px;">Edit</th>
                                        <th style="color:black; width:10px;">Delete</th>
                                        <th style="color:black; width:10px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($rows_newspapers>0)
                                    {
                                        $p=1;
                                        while($fetch_newspapers=mysqli_fetch_array($query_newspapers))
                                        {
                                            $sql_newspapers_category  = mysqli_query($con,"SELECT * FROM `newspapers_category` WHERE newspapers_category_id='".$fetch_newspapers['newspapers_category_id']."'");
                                            $fetch_newspapers_category = mysqli_fetch_array($sql_newspapers_category);
                                    ?>
                                        <tr>
                                            <td>
                                              <?php
                                              if(!empty($fetch_newspapers['newspapers_image']))
                                              {
                                              ?>
                                                <img src="uploads/newspapers/<?php echo $fetch_newspapers['newspapers_image'];?>" height="60" width="60">
                                              <?php
                                              }
                                              else
                                              {
                                              ?>
                                                <img src="uploads/newspapers/newspaper_icon.png" height="60" width="60">
                                              <?php
                                              }
                                              ?>
                                            </td>
                                            <td>
                                                <?php
                                                  if(!empty($fetch_newspapers['newspapers_category_id']))
                                                  {
                                                    echo strtoUpper($fetch_newspapers_category['newspapers_category_name']);
                                                  } 
                                                ?>
                                            </td>
                                            <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                            <td> <?php echo $fetch_newspapers['newspapers_price']?></td>
                                            <td> <?php echo $fetch_newspapers['newspapers_quantity'];?></td>
                                            <td>
                                                <a href="view_newspapers_list.php?newspapers_id=<?php echo $fetch_newspapers['newspapers_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-folder"> View</i></a>
                                            </td>
                                            <td>
                                                <a href="manage_newspapers_list.php?newspapers_id=<?php echo $fetch_newspapers['newspapers_id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"> Edit</i></a>
                                            </td>
                                            <td>
                                                <a href="all_newspapers_list.php?newspapers_del_id=<?php echo $fetch_newspapers['newspapers_id']; ?>" onClick="return confirm('Are you sure to continue?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"> Delete</i></a>
                                            </td>
                                            <td> <?php if($fetch_newspapers['newspapers_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{?><span class="badge badge-danger">Inactive</span><?php }?></td>
                                        </tr>
                                    <?php
                                        $p++;
                                        }
                                    }
                                    ?>
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
                                  <thead>
                                      <tr>
                                          <th style="color:black; width:80px;">Newspaper Image</th>
                                          <th style="color:black; width:20px;">Newspapers Category</th>
                                          <th style="color:black; width:20px;">Newpapers Title</th>
                                          <th style="color:black; width:20px;">Newspapers Price</th>
                                          <th style="color:black; width:20px;">Newspapers Quantity</th>
                                          <th style="color:black; width:10px;">View</th>
                                          <th style="color:black; width:10px;">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php 
                                      if($rows_newspapers>0)
                                      {
                                          $p=1;
                                          while($fetch_newspapers=mysqli_fetch_array($query_newspapers))
                                          {
                                              $sql_newspapers_category  = mysqli_query($con,"SELECT * FROM `newspapers_category` WHERE newspapers_category_id='".$fetch_newspapers['newspapers_category_id']."'");
                                              $fetch_newspapers_category = mysqli_fetch_array($sql_newspapers_category);
                                      ?>
                                          <tr>
                                              <td>
                                                <?php
                                                if(!empty($fetch_newspapers['newspapers_image']))
                                                {
                                                ?>
                                                  <img src="uploads/newspapers/<?php echo $fetch_newspapers['newspapers_image'];?>" height="60" width="60">
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                  <img src="uploads/newspapers/newspaper_icon.png" height="60" width="60">
                                                <?php
                                                }
                                                ?>
                                              </td>
                                              <td>
                                                <?php
                                                  if(!empty($fetch_newspapers['newspapers_category_id']))
                                                  {
                                                    echo strtoUpper($fetch_newspapers_category['newspapers_category_name']);
                                                  } 
                                                ?>
                                              </td>
                                              <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                              <td> <?php echo $fetch_newspapers['newspapers_price']?></td>
                                              <td> <?php echo $fetch_newspapers['newspapers_quantity'];?></td>
                                              <td>
                                                  <a href="view_newspapers_list.php?newspapers_id=<?php echo $fetch_newspapers['newspapers_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-folder"> View</i></a>
                                              </td>
                                              <td> <?php if($fetch_newspapers['newspapers_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{?><span class="badge badge-danger">Inactive</span><?php }?></td>
                                          </tr>
                                      <?php
                                          $p++;
                                          }
                                      }
                                      ?>
                              </table>
                            </div>
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

<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // "buttons": ["pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
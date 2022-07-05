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

//Fetch all members
$sql_std = "SELECT * FROM `users` WHERE user_type='student' ORDER BY user_name ";
$query_std = mysqli_query($con,$sql_std) or mysqli_connect_error();
$rows_std = mysqli_num_rows($query_std);

//For Delete
if(isset($_GET['std_del_id']))
{
    $sqlUpdt = "DELETE FROM `users` WHERE user_id='".$_GET['std_del_id']."'";
    $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();

    echo "<script>window.location.href='all_member.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | All Members</title>
  
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
            <h1 class="m-0">All Member List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item active">All Member List</li>
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
                            <a href="manage_member.php" class="card-title"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-users"></i>  Add New Member</button></a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="color:black; width:80px;">Image</th>
                                        <th style="color:black; width:100px;">Library No</th>
                                        <th style="color:black; width:100px;">Name</th>
                                        <th style="color:black; width:15px;">Department</th>
                                        <th style="color:black; width:50px;">Mobile</th>
                                        <th style="color:black; width:10px;">View</th>
                                        <th style="color:black; width:10px;">Edit</th>
                                        <th style="color:black; width:10px;">Delete</th>
                                        <th style="color:black; width:10px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($rows_std>0)
                                    {
                                        $p=1;
                                        while($fetch_std=mysqli_fetch_array($query_std))
                                        {
                                          $sql_dept  = mysqli_query($con,"SELECT * FROM `department` WHERE dept_id='".$fetch_std['dept_id']."'");
                                          $fetch_dept = mysqli_fetch_array($sql_dept);
                                    ?>
                                        <tr>
                                            <td>
                                                <?php
                                                if(!empty($fetch_std['user_image']))
                                                {
                                                ?>
                                                    <img src="uploads/student/<?php echo $fetch_std['user_image'];?>" height="60" width="60">
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                    <img src="uploads/student/icon.png" height="60" width="60">
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo strtoUpper($fetch_std['user_library_no']);?></td>
                                            <td><?php echo strtoUpper($fetch_std['user_name']);?></td>
                                            <td>
                                              <?php
                                                if(!empty($fetch_std['dept_id']))
                                                {
                                                  echo strtoUpper($fetch_dept['dept_code']);
                                                }
                                              ?>
                                            </td>
                                            <td><?php echo $fetch_std['user_mobile'];?></td>
                                            <td>
                                              <a href="view_member.php?user_id=<?php echo $fetch_std['user_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-folder"> View</i></a>
                                            </td>
                                            <td>
                                              <a href="manage_member.php?user_id=<?php echo $fetch_std['user_id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"> Edit</i></a>
                                            </td>
                                            <td>
                                              <a href="all_member.php?std_del_id=<?php echo $fetch_std['user_id']; ?>" onClick="return confirm('Are you sure to continue?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"> Delete</i></a>
                                            </td>
                                            <td><?php if($fetch_std['user_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{?><span class="badge badge-danger">Inactive</span><?php }?></td>
                                        </tr>
                                    <?php
                                        $p++;
                                        }
                                    }
                                    ?>
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

<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // "buttons": ["pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });
  });
</script>
</body>
</html>

<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch all books isssue for admin & student
if($_SESSION['UserType']=='admin')
{
  $sql_magazines_issue = "SELECT * FROM `magazines_issue` ORDER BY magazines_issue_id";
}
else
{
  $sql_magazines_issue = "SELECT * FROM `magazines_issue` WHERE user_id='".$_SESSION['UserId']."' ORDER BY magazines_issue_id";
}
$query_magazines_issue = mysqli_query($con,$sql_magazines_issue) or mysqli_connect_error();
$rows_magazines_issue = mysqli_num_rows($query_magazines_issue);

//For Delete
if(isset($_GET['magazines_issue_del_id']))
{
    $sqlUpdt = "DELETE FROM `magazines_issue` WHERE magazines_issue_id='".$_GET['magazines_issue_del_id']."'";
    $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();

    echo "<script>window.location.href='all_magazines_issue_list.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | All Magazines Issue List</title>
  
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
            <h1 class="m-0">All Magazines Issue List</h1>
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
              <li class="breadcrumb-item active">All Magazines Issue List</li>
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
                            if($_SESSION['UserType']=='student')
                            {
                          ?>
                              <a href="manage_magazines_issue_list.php" class="card-title"><button type="button" class="btn btn-block btn-primary"><i class="fas fa-book-open"></i>  Add New Magazines Issue</button></a>
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
                                            <th style="color:black; width:20px;">Student Name</th>
                                            <th style="color:black; width:20px;">Magazines Title</th>
                                            <th style="color:black; width:20px;">Magazines Quantity</th>
                                            <th style="color:black; width:20px;">No of Issue Magazines</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Return Date</th>
                                            <th style="color:black; width:10px;">View</th>
                                            <th style="color:black; width:10px;">Edit</th>
                                            <th style="color:black; width:10px;">Delete</th>
                                            <th style="color:black; width:10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_magazines_issue>0)
                                        {
                                            $p=1;
                                            while($fetch_magazines_issue=mysqli_fetch_array($query_magazines_issue))
                                            {
                                                //fetch user name using `users` table
                                                $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$fetch_magazines_issue['user_id']."'");
                                                $fetch_users= mysqli_fetch_array($sql_users);

                                                //fetch `magazines` table
                                                $sql_magazines  = mysqli_query($con,"SELECT * FROM `magazines` WHERE magazines_id='".$fetch_magazines_issue['magazines_id']."'");
                                                $fetch_magazines = mysqli_fetch_array($sql_magazines);
                                        ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_users['user_name']);?></td>
                                                <td> <?php echo strtoUpper($fetch_magazines['magazines_title']);?></td>
                                                <td> <?php echo strtoUpper($fetch_magazines['magazines_quantity']);?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_no_of_magazines']?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_date'];?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_return_date'];?></td>
                                                <td>
                                                    <a href="view_magazines_issue_list.php?magazines_issue_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-folder"> View</i></a>
                                                </td>
                                                <td>
                                                    <a href="manage_magazines_issue_list.php?magazines_issue_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"> Edit</i></a>
                                                </td>
                                                <td>
                                                    <a href="all_magazines_issue_list.php?magazines_issue_del_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" onClick="return confirm('Are you sure to continue?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"> Delete</i></a>
                                                </td>
                                                <td> <?php if($fetch_magazines_issue['magazines_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch_magazines_issue['magazines_issue_status']=='I'){?><span class="badge badge-success">Issue</span><?php }else{?><span class="badge badge-danger">Rejected</span><?php }?></td>
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
                          else
                          {
                        ?>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="color:black; width:20px;">Magazines Title</th>
                                            <th style="color:black; width:20px;">No of Issue Magazines</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Return Date</th>
                                            <th style="color:black; width:10px;">View</th>
                                            <th style="color:black; width:10px;">Issue Status</th>
                                            <th style="color:black; width:10px;">Return Magazine</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_magazines_issue>0)
                                        {
                                            $p=1;
                                            while($fetch_magazines_issue=mysqli_fetch_array($query_magazines_issue))
                                            {
                                                //fetch `magazines` table
                                                $sql_magazines  = mysqli_query($con,"SELECT * FROM `magazines` WHERE magazines_id='".$fetch_magazines_issue['magazines_id']."'");
                                                $fetch_magazines = mysqli_fetch_array($sql_magazines);

                                                $sql_magazines_return_no = "SELECT SUM(magazines_return_no_of_magazines) AS 'No of Return Magazine' FROM `magazines_return` WHERE magazines_issue_id='".$fetch_magazines_issue['magazines_issue_id']."' ";
                                                $query_magazines_return_no = mysqli_query($con,$sql_magazines_return_no) or mysqli_connect_error();
                                                $fetch_magazines_return_no = mysqli_fetch_array($query_magazines_return_no);

                                                $no_magazines_return = $fetch_magazines_return_no['No of Return Magazine'];
                                        ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_magazines['magazines_title']);?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_no_of_magazines']?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_date'];?></td>
                                                <td> <?php echo $fetch_magazines_issue['magazines_issue_return_date'];?></td>
                                                <td>
                                                    <a href="view_magazines_issue_list.php?magazines_issue_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-folder"> View</i></a>
                                                </td>
                                                <td> <?php if($fetch_magazines_issue['magazines_issue_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }elseif($fetch_magazines_issue['magazines_issue_status']=='I'){?><span class="badge badge-success">Issue</span><?php }else{?><span class="badge badge-danger">Rejected</span><?php }?></td>
                                                <td>
                                                  <?php
                                                  if(!empty($fetch_magazines_issue['magazines_issue_date']) && !empty($fetch_magazines_issue['magazines_issue_return_date']) && ($fetch_magazines_issue['magazines_issue_no_of_magazines']!=$no_magazines_return))
                                                  {
                                                  ?>
                                                    <a href="manage_magazines_return_list.php?magazines_issue_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-right"> Return</i></a>
                                                  <?php
                                                  }
                                                  else
                                                  {
                                                  ?>
                                                    <a href="manage_magazines_return_list.php?magazines_issue_id=<?php echo $fetch_magazines_issue['magazines_issue_id']; ?>" class="btn btn-secondary btn-sm" style="pointer-events: none"><i class="fas fa-arrow-right"> Return</i></a>
                                                  <?php
                                                  }
                                                  ?>
                                                </td>
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
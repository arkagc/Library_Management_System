<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch all books isssue for admin & student
if($_SESSION['UserType']=='admin')
{
  $sql_books_not_return = "SELECT * FROM `books_not_return` ORDER BY books_not_return_id";
}
else
{
  $sql_books_not_return = "SELECT * FROM `books_not_return` WHERE user_id='".$_SESSION['UserId']."' ORDER BY books_not_return_id";
}
$query_books_not_return = mysqli_query($con,$sql_books_not_return) or mysqli_connect_error();
$rows_books_not_return = mysqli_num_rows($query_books_not_return);

//For Delete
if(isset($_GET['books_not_return_del_id']))
{
    $sqlUpdt = "DELETE FROM `books_not_return` WHERE books_not_return_id='".$_GET['books_not_return_del_id']."'";
    $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();

    echo "<script>window.location.href='all_books_not_return_list.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | All Books Not Return List</title>
  
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
            <h1 class="m-0">All Books Not Return List</h1>
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
              <li class="breadcrumb-item active">All Books Not Return List</li>
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
                                            <th style="color:black; width:20px;">Books Title</th>
                                            <th style="color:black; width:20px;">No of Issue Books</th>
                                            <th style="color:black; width:20px;">No of Pending Books</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Return Date</th>
                                            <th style="color:black; width:10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_books_not_return>0)
                                        {
                                            $p=1;
                                            while($fetch_books_not_return=mysqli_fetch_array($query_books_not_return))
                                            {
                                                //fetch user name using `users` table
                                                $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$fetch_books_not_return['user_id']."'");
                                                $fetch_users= mysqli_fetch_array($sql_users);

                                                //fetch `books_issue` table
                                                $sql_books_issue  = mysqli_query($con,"SELECT * FROM `books_issue` WHERE books_issue_id='".$fetch_books_not_return['books_issue_id']."'");
                                                $fetch_books_issue = mysqli_fetch_array($sql_books_issue);

                                                //fetch `books` table
                                                $sql_books  = mysqli_query($con,"SELECT * FROM `books` WHERE books_id='".$fetch_books_issue['books_id']."'");
                                                $fetch_books= mysqli_fetch_array($sql_books);
                                        ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_users['user_name']);?></td>
                                                <td> <?php echo strtoUpper($fetch_books['books_title']);?></td>
                                                <td> <?php echo $fetch_books_not_return['books_not_return_no_of_books_issue']?></td>
                                                <td> <?php echo $fetch_books_not_return['books_not_return_no_of_books_pending']?></td>
                                                <td> <?php echo $fetch_books_issue['books_issue_date'];?></td>
                                                <td> <?php echo $fetch_books_issue['books_issue_return_date'];?></td>
                                                <td> <?php if($fetch_books_not_return['books_not_return_status']=='NR'){ ?><span class="badge badge-danger">Not Return</span><?php }?></td>
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
                                            <th style="color:black; width:20px;">Books Title</th>
                                            <th style="color:black; width:20px;">No of Issue Books</th>
                                            <th style="color:black; width:20px;">No of Pending Books</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Return Date</th>
                                            <th style="color:black; width:10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_books_not_return>0)
                                        {
                                            $p=1;
                                            while($fetch_books_not_return=mysqli_fetch_array($query_books_not_return))
                                            {
                                                //fetch `books_issue` table
                                                $sql_books_issue  = mysqli_query($con,"SELECT * FROM `books_issue` WHERE books_issue_id='".$fetch_books_not_return['books_issue_id']."'");
                                                $fetch_books_issue = mysqli_fetch_array($sql_books_issue);

                                                //fetch `books` table
                                                $sql_books  = mysqli_query($con,"SELECT * FROM `books` WHERE books_id='".$fetch_books_issue['books_id']."'");
                                                $fetch_books= mysqli_fetch_array($sql_books);
                                        ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_books['books_title']);?></td>
                                                <td> <?php echo $fetch_books_not_return['books_not_return_no_of_books_issue']?></td>
                                                <td> <?php echo $fetch_books_not_return['books_not_return_no_of_books_pending']?></td>
                                                <td> <?php echo $fetch_books_issue['books_issue_date'];?></td>
                                                <td> <?php echo $fetch_books_issue['books_issue_return_date'];?></td>
                                                <td> <?php if($fetch_books_not_return['books_not_return_status']=='NR'){ ?><span class="badge badge-danger">Not Return</span><?php }?></td>
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
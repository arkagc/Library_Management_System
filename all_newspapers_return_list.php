<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch all books isssue for admin & student
if($_SESSION['UserType']=='admin')
{
  $sql_newspapers_return = "SELECT * FROM `newspapers_return` ORDER BY newspapers_return_id";
}
else
{
  $sql_newspapers_return = "SELECT * FROM `newspapers_return` WHERE user_id='".$_SESSION['UserId']."' ORDER BY newspapers_return_id";
}
$query_newspapers_return = mysqli_query($con,$sql_newspapers_return) or mysqli_connect_error();
$rows_newspapers_return = mysqli_num_rows($query_newspapers_return);

//For Delete
if(isset($_GET['newspapers_return_del_id']))
{
    $sqlUpdt = "DELETE FROM `newspapers_return` WHERE newspapers_return_id='".$_GET['newspapers_return_del_id']."'";
    $queryUpdt = mysqli_query($con,$sqlUpdt) or mysqli_connect_error();

    echo "<script>window.location.href='all_newspapers_return_list.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | All Newspapers Return List</title>
  
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
            <h1 class="m-0">All Newspapers Return List</h1>
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
              <li class="breadcrumb-item active">All Newspapers Return List</li>
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
                                            <th style="color:black; width:20px;">Newspapers Title</th>
                                            <th style="color:black; width:20px;">No of Issue Newspapers</th>
                                            <th style="color:black; width:20px;">No of Return Newspapers</th>
                                            <th style="color:black; width:20px;">No of Pending Newspapers</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Issue Return Date</th>
                                            <th style="color:black; width:20px;">Student Return Date</th>
                                            <th style="color:black; width:20px;">No of Extra Days</th>
                                            <th style="color:black; width:10px;">Fine</th>
                                            <th style="color:black; width:10px;">Delete</th>
                                            <th style="color:black; width:10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_newspapers_return>0)
                                        {
                                            $p=1;
                                            while($fetch_newspapers_return=mysqli_fetch_array($query_newspapers_return))
                                            {
                                                //fetch from `newspapers_issue` table
                                                $sql_newspapers_issue  = mysqli_query($con,"SELECT * FROM `newspapers_issue` WHERE newspapers_issue_id='".$fetch_newspapers_return['newspapers_issue_id']."'");
                                                $fetch_newspapers_issue = mysqli_fetch_array($sql_newspapers_issue);

                                                //fetch `users` table
                                                $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$fetch_newspapers_return['user_id']."'");
                                                $fetch_users= mysqli_fetch_array($sql_users);

                                                //fetch `newspapers` table
                                                $sql_newspapers  = mysqli_query($con,"SELECT * FROM `newspapers` WHERE newspapers_id='".$fetch_newspapers_issue['newspapers_id']."'");
                                                $fetch_newspapers = mysqli_fetch_array($sql_newspapers);

                                                $updt_issue_retun_date = date('Y-m-d', strtotime($fetch_newspapers_issue['newspapers_issue_return_date']));
                                                $updt_retun_date = date('Y-m-d', strtotime($fetch_newspapers_return['newspapers_return_return_date']));

                                                if($updt_retun_date > $updt_issue_retun_date)
                                                {
                                                    $date_issue_return = date_create($updt_issue_retun_date);
                                                    $date_return = date_create($updt_retun_date);

                                                    $diff_dates = date_diff($date_issue_return,$date_return);

                                                    $no_of_days = $diff_dates->format("%a");
                                                }
                                                else
                                                {
                                                    $no_of_days = 0;
                                                }
                                        ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_users['user_name']);?></td>
                                                <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_no_of_newspapers']?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_no_of_newspapers']?></td>
                                                <td> 
                                                    <?php 
                                                        if($fetch_newspapers_return['newspapers_return_no_of_pending_newspapers'] == 0)
                                                        {
                                                            echo 'No Pending Newspapers';
                                                        }
                                                        else
                                                        {
                                                            $pending_newspapers = $fetch_newspapers_return['newspapers_return_no_of_pending_newspapers'];
                                                            echo $pending_newspapers.' Pending Newspapers';
                                                        }
                                                    ?>
                                                </td>
                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_date'];?></td>
                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_return_date'];?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_return_date']?></td>
                                                <td> <?php echo $no_of_days; ?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_fine']?></td>
                                                <td>
                                                    <a href="all_newspapers_return_list.php?newspapers_return_del_id=<?php echo $fetch_newspapers_return['newspapers_return_id']; ?>" onClick="return confirm('Are you sure to continue?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"> Delete</i></a>
                                                </td>
                                                <td> <?php if($fetch_newspapers_return['newspapers_return_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }else{?><span class="badge badge-success">Clear</span><?php }?></td>
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
                                            <th style="color:black; width:20px;">Newspapers Title</th>
                                            <th style="color:black; width:20px;">No of Issue Newspapers</th>
                                            <th style="color:black; width:20px;">No of Return Newspapers</th>
                                            <th style="color:black; width:20px;">No of Pending Newspapers</th>
                                            <th style="color:black; width:20px;">Issue Date</th>
                                            <th style="color:black; width:20px;">Issue Return Date</th>
                                            <th style="color:black; width:20px;">My Return Date</th>
                                            <th style="color:black; width:20px;">No of Extra Days</th>
                                            <th style="color:black; width:20px;">Fine</th>
                                            <th style="color:black; width:10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($rows_newspapers_return>0)
                                        {
                                            $p=1;
                                            while($fetch_newspapers_return=mysqli_fetch_array($query_newspapers_return))
                                            {
                                                //fetch from `newspapers_issue` table
                                                $sql_newspapers_issue  = mysqli_query($con,"SELECT * FROM `newspapers_issue` WHERE newspapers_issue_id='".$fetch_newspapers_return['newspapers_issue_id']."'");
                                                $fetch_newspapers_issue = mysqli_fetch_array($sql_newspapers_issue);

                                                //fetch `users` table
                                                $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$fetch_newspapers_return['user_id']."'");
                                                $fetch_users= mysqli_fetch_array($sql_users);

                                                //fetch `newspapers` table
                                                $sql_newspapers  = mysqli_query($con,"SELECT * FROM `newspapers` WHERE newspapers_id='".$fetch_newspapers_issue['newspapers_id']."'");
                                                $fetch_newspapers = mysqli_fetch_array($sql_newspapers);

                                                $updt_issue_retun_date = date('Y-m-d', strtotime($fetch_newspapers_issue['newspapers_issue_return_date']));
                                                $updt_retun_date = date('Y-m-d', strtotime($fetch_newspapers_return['newspapers_return_return_date']));

                                                if($updt_retun_date > $updt_issue_retun_date)
                                                {
                                                    $date_issue_return = date_create($updt_issue_retun_date);
                                                    $date_return = date_create($updt_retun_date);

                                                    $diff_dates = date_diff($date_issue_return,$date_return);

                                                    $no_of_days = $diff_dates->format("%a");
                                                }
                                                else
                                                {
                                                    $no_of_days = 0;
                                                }
                                            ?>
                                            <tr>
                                                <td> <?php echo strtoUpper($fetch_newspapers['newspapers_title']);?></td>
                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_no_of_newspapers'];?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_no_of_newspapers'];?></td>
                                                
                                                <td> 
                                                    <?php 
                                                        if($fetch_newspapers_return['newspapers_return_no_of_pending_newspapers'] == 0)
                                                        {
                                                            echo 'No Pending Newspapers';
                                                        }
                                                        else
                                                        {
                                                            $pending_newspapers = $fetch_newspapers_return['newspapers_return_no_of_pending_newspapers'];
                                                            echo $pending_newspapers.' Pending Newspapers';
                                                        }
                                                    ?>
                                                </td>

                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_date'];?></td>
                                                <td> <?php echo $fetch_newspapers_issue['newspapers_issue_return_date'];?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_return_date'];?></td>
                                                <td> <?php echo $no_of_days; ?></td>
                                                <td> <?php echo $fetch_newspapers_return['newspapers_return_fine'];?></td>
                                                <td> <?php if($fetch_newspapers_return['newspapers_return_status']=='P'){ ?><span class="badge badge-warning">Pending</span><?php }else{?><span class="badge badge-success">Clear</span><?php }?></td>
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
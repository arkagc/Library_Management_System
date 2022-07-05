<?php
include("connection.php"); //For connection to database

if(!isset($_SESSION['UserType']))
{
  echo "<script>window.location.href='index.php'</script>";
}

//Fetch department
if(isset($_GET["books_id"])){
    
    $books_id=$_GET["books_id"];
    
    $sql = "SELECT * FROM `books` WHERE books_id=$books_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
    //fetch from `books_category` table using `books` table
    $sql_books_category  = mysqli_query($con,"SELECT * FROM `books_category` WHERE books_category_id='".$fetch['books_category_id']."'");
    $fetch_books_category = mysqli_fetch_array($sql_books_category);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | View Books</title>
  
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
            <h1 class="m-0">Books</h1>
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
              <li class="breadcrumb-item active">View Books</li>
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
                            <h3 class="card-title">View Books</h3>
                            <a href="all_books_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td> <?php echo 'Books Category';?></td>
                                        <td> 
                                            <?php
                                                if(!empty($fetch['books_category_id']))
                                                {
                                                    echo strtoUpper($fetch_books_category['books_category_name']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books ISBN No';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_isbn_no']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Title';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_title']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Author Name';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_author_name']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Publisher';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_publisher']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Edition';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_edition']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Purchase Date';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_purchase_date']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Price';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_price']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Total Pages';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_total_pages']);?></td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo 'Books Quantity';?></td>
                                        <td> <?php echo strtoUpper($fetch['books_quantity']);?></td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Image';?></td>
                                        <td>
                                            <?php
                                            if(!empty($fetch['books_image']))
                                            {
                                            ?>
                                                <img src="uploads/books/<?php echo $fetch['books_image'];?>" height="100" width="100">
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <img src="uploads/books/book_icon.png" height="100" width="100">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <?php echo 'Books Status';?></td>
                                        <td> <?php if($fetch['books_status']=='Y'){ ?><span class="badge badge-success">Active</span><?php }else{ ?><span class="badge badge-danger">Inactive</span><?php }?></td>
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
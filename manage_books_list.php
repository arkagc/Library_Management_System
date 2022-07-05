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

if(isset($_GET["books_id"])){
    
    $books_id=$_GET["books_id"];
    
    $sql = "SELECT * FROM `books` WHERE books_id=$books_id"; 
    $query = mysqli_query($con,$sql) or mysqli_connect_error();
    $fetch = mysqli_fetch_array($query);
    
}

/*############################## Start Add/Edit ################################*/
if(isset($_POST['SubmitX']))
{
    if(isset($books_id))
    {
        $sql_check = "SELECT * FROM `books` WHERE books_isbn_no='".strtoLower($_POST["books_isbn_no"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['books_image']['name']=="")
            {
                $mainpic=$_POST['oldimage'];			
            }
            else
            {
                $Imagesfolder="uploads/books/";
                $unlink_sql="SELECT books_image FROM `books` WHERE books_id='".$books_id."'";
                $unlink_rs=mysqli_query($con,$unlink_sql) or mysqli_connect_error();
                $row_unlink=mysqli_fetch_array($unlink_rs);
                $photo=$Imagesfolder.$row_unlink['books_image'];

                if(file_exists($photo))
                {
                    @unlink($photo);
                }

                $path_parts = pathinfo($_FILES['books_image']['name']); 
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['books_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }

            $sql_books = "UPDATE `books` SET 
            books_category_id='".strtoLower($_POST["books_category_id"])."',
            books_isbn_no='".strtoLower($_POST["books_isbn_no"])."',
            books_title='".strtoLower($_POST["books_title"])."',
            books_author_name='".strtoLower($_POST["books_author_name"])."',
            books_publisher='".strtoLower($_POST["books_publisher"])."',
            books_edition='".strtoLower($_POST["books_edition"])."',
            books_purchase_date='".strtoLower($_POST["books_purchase_date"])."',
            books_price='".strtoLower($_POST["books_price"])."',
            books_total_pages='".strtoLower($_POST["books_total_pages"])."',
            books_quantity='".strtoLower($_POST["books_quantity"])."',
            books_modt=CURRENT_TIMESTAMP,
            books_image='".$mainpic."',
            books_status='".$_POST["books_status"]."'
            WHERE books_id ='".$books_id."'";

            mysqli_query($con,$sql_books) or mysqli_connect_error();

            echo '<script>window.location.href="manage_books_list.php?books_id='.$books_id.'&msg=update";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_books_list.php?&msg=error";</script>';
        }
    }
    else
    {
        $sql_check = "SELECT * FROM `books` WHERE books_isbn_no='".strtoLower($_POST["books_isbn_no"])."' ";
        $query_check = mysqli_query($con,$sql_check) or mysqli_connect_error();
        $rows_check = mysqli_num_rows($query_check);

        if($rows_check==0)
        {
            if($_FILES['books_image']['name']!="")
            {
                $Imagesfolder="uploads/books/";
                $path_parts = pathinfo($_FILES['books_image']['name']);
                $file_name = $path_parts['filename'];
                $file_extn = $path_parts['extension'];
                $mainpic = $file_name."_".date('Y-m-d-H-i-s').".".$file_extn;		
                $temp = $_FILES['books_image']['tmp_name'];
                $upload  = $Imagesfolder.$mainpic; 
                @copy($temp,$upload);
            }
            else
            {
                $mainpic="";
            }

            $sql_books = "INSERT INTO `books` SET 
            books_category_id='".strtoLower($_POST["books_category_id"])."',
            books_isbn_no='".strtoLower($_POST["books_isbn_no"])."',
            books_title='".strtoLower($_POST["books_title"])."',
            books_author_name='".strtoLower($_POST["books_author_name"])."',
            books_publisher='".strtoLower($_POST["books_publisher"])."',
            books_edition='".strtoLower($_POST["books_edition"])."',
            books_purchase_date='".strtoLower($_POST["books_purchase_date"])."',
            books_price='".strtoLower($_POST["books_price"])."',
            books_total_pages='".strtoLower($_POST["books_total_pages"])."',
            books_quantity='".strtoLower($_POST["books_quantity"])."',
            books_crdt=CURRENT_TIMESTAMP,
            books_image='".$mainpic."',
            books_status='".$_POST["books_status"]."'
            ";

            mysqli_query($con,$sql_books) or mysqli_connect_error();
            $books_id=mysqli_insert_id($con);

            echo '<script>window.location.href="manage_books_list.php?books_id='.$books_id.'&msg=insert";</script>';
        }
        else
        {
            echo '<script>window.location.href="manage_books_list.php?&msg=error";</script>';
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
  <title>LMS | Add / Edit Books</title>
  
  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
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
              <li class="breadcrumb-item">Admin</li>
              <?php
                if(isset($books_id))
                {
              ?>
                  <li class="breadcrumb-item active">Edit Books</li>
              <?php
                }
                else
                {
              ?>
                  <li class="breadcrumb-item active">Add Books</li>
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
                              if(isset($books_id))
                              {
                            ?>
                                <h3 class="card-title">Edit Books</h3>
                            <?php
                              }
                              else
                              {
                            ?>
                                <h3 class="card-title">Add Books</h3>
                            <?php
                              }
                            ?>
                            <a href="all_books_list.php" class="btn btn-warning btn-sm" style="float:right; color:#000000"><i class="fa fa-arrow-left"></i> <b>Back</b></a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <p style="color:red;" class=""><b>( * ) indicates mandatory fields</b></p>
                                
                                <!--Print Message-->
                                <?php
                                  if(isset($_GET['msg']) && $_GET['msg'] == 'update')
                                  {
                                ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <strong>Books Update Successfully !</strong>
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
                                            <strong>Books Add Successfully !</strong>
                                        </div>
                                <?php
                                    }
                                    if(isset($_GET['msg']) && $_GET['msg'] == 'error')
                                    {
                                ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Books Already Exists !</strong>
                                        </div>
                                <?php
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksCategory">Books Category</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <select class="form-control" name="books_category_id" id="books_category_id" required>
                                                <option value="">Select Books Category</option>
                                                <?php
                                                    $sql_books_category="SELECT * FROM `books_category` WHERE books_category_status='Y' ";
                                                    $query_books_category=mysqli_query($con,$sql_books_category) or mysqli_connect_error();
                                                    $rows_books_category=mysqli_num_rows($query_books_category);

                                                    if($rows_books_category > 0)
                                                    {
                                                        while($fetch_books_category=mysqli_fetch_array($query_books_category))
                                                        {
                                                ?>
                                                            <option value="<?php if(isset($fetch_books_category['books_category_id'])){ ?> <?php echo $fetch_books_category['books_category_id']; }?>" <?php if(isset($fetch['books_category_id']) && $fetch_books_category['books_category_id'] == $fetch['books_category_id']){ ?>selected<?php } ?>><?php echo strtoUpper($fetch_books_category['books_category_name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksIsbnNo">Books ISBN No</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_isbn_no" name="books_isbn_no" placeholder="Enter ISBN No" value="<?php if(isset($fetch['books_isbn_no'])){?> <?php echo strtoUpper($fetch['books_isbn_no']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksTitle">Books Title</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_title" name="books_title" placeholder="Enter Books Title" value="<?php if(isset($fetch['books_title'])){?> <?php echo strtoUpper($fetch['books_title']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksAuthor">Books Author Name</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_author_name" name="books_author_name" placeholder="Enter Books Author Name" value="<?php if(isset($fetch['books_author_name'])){?> <?php echo strtoUpper($fetch['books_author_name']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksPublisher">Books Publisher</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_publisher" name="books_publisher" placeholder="Enter Books Publisher" value="<?php if(isset($fetch['books_publisher'])){?> <?php echo strtoUpper($fetch['books_publisher']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksEdition">Books Edition</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_edition" name="books_edition" placeholder="Enter Books Edition" value="<?php if(isset($fetch['books_edition'])){?> <?php echo strtoUpper($fetch['books_edition']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksPurchaseDate">Books Purchase Date</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <div class="input-group date" id="books_purchase_date" name="books_purchase_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#books_purchase_date" name="books_purchase_date" placeholder="Enter Books Purchase Date" value="<?php if(isset($fetch['books_purchase_date'])) { ?><?php echo $fetch['books_purchase_date']; } ?>" required/>
                                                <div class="input-group-append" data-target="#books_purchase_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksPrice">Books Price</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_price" name="books_price" placeholder="Enter Books Price" value="<?php if(isset($fetch['books_price'])){?> <?php echo strtoUpper($fetch['books_price']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksTotalPages">Books Total Pages</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_total_pages" name="books_total_pages" placeholder="Enter Books Total Pages" value="<?php if(isset($fetch['books_total_pages'])){?> <?php echo strtoUpper($fetch['books_total_pages']); }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksQuantity">Books Quantity</label><span class="required" style="color:#FF0000;"> <b>*</b></span>
                                            <input type="text" class="form-control" id="books_quantity" name="books_quantity" placeholder="Enter Books Quantity" value="<?php if(isset($fetch['books_quantity'])){?> <?php echo strtoUpper($fetch['books_quantity']); }?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Image Upload</label>
                                            <div class="input-group">
                                                <input type="file" name="books_image" id="books_image" />
				                                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $fetch['books_image']?>" />
                                                <?php
                                                    if(!empty($fetch['books_image']))
                                                    {
                                                ?>
                                                        <img src="uploads/books/<?php echo $fetch['books_image']?>" height="100" width="100"/>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="booksStatus">Status</label><span class="required" style="color:#FF0000;"> <b>*</b></span></br>
                                            <input type="radio" id="books_status" name="books_status" value="Y" <?php if(isset($fetch['books_status']) && $fetch['books_status']=='Y'){?> checked="checked"<?php }?> required> Active &nbsp;
                                            <input type="radio" id="books_status" name="books_status" value="N" <?php if(isset($fetch['books_status']) && $fetch['books_status']=='N'){?> checked="checked"<?php }?> required> Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success" name="SubmitX" id="SubmitX" value="Submit"/>
                                <a href="all_books_list.php" class="btn btn-info">Cancel</a>
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
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  bsCustomFileInput.init();
});
</script>

<script>
    //Date picker
    $(function () {
        $('#books_purchase_date').datetimepicker({
            format: 'L'
        });
    })
</script>

</body>
</html>
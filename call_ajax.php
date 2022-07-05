<?php
include("connection.php"); //For connection to database

if(isset($_POST['dept_id'])){
    $id = $_POST['dept_id'];
    $sql = "SELECT * FROM `department_year` WHERE dept_id = '$id' ORDER BY dept_yr_name ";
    $result = mysqli_query($con,$sql);
    echo "<option value=''>Select Department Year</option>";
    while($row = mysqli_fetch_array($result)){
        echo "<option value='" . $row['dept_yr_id'] ."'>" . strtoUpper($row['dept_yr_name']) ."</option>";
    }
}
?>
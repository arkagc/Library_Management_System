<?php 
// Initialize the session. 
session_start(); 

// Unset all of the session variables. 
session_unset(); 

// Finally, destroy the session. 
session_destroy(); 

echo "<script>window.location.href='index.php'</script>";
?>
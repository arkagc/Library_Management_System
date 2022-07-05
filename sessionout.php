<?php
$idle = (30*60); // In Seconds 2 hrs
if(isset($_SESSION['time'])){
	$time_elapsed = (time() - $_SESSION['time']);
	
	if($time_elapsed < $idle){
		$_SESSION['time'] = time();
	}
	else{	
		echo '<script>window.location.href="logout.php"</script>';
	}
}
else{
	$_SESSION['time'] = time();
}

if(!isset($_SESSION["UserName"]) || empty($_SESSION["UserName"]))
{
	echo '<script>window.location.href="index.php"</script>';
	exit();
}
?>
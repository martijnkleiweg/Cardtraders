<?php

$con = mysqli_connect("localhost","gcadmin","GCpass@86","giftcard");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
		}

date_default_timezone_set('Europe/Amsterdam');
$error="";
?>

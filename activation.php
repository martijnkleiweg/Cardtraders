<?php
	require_once("dbcontroller2.php");
	$db_handle = new DBController2();
	if(!empty($_GET["token"])) {
	$query = "UPDATE registered_users set status = 'active' WHERE token='" . $_GET["token"]. "'";
	$result = $db_handle->updateQuery($query);
		if(!empty($result)) {
			$message = "Your account is activated.";
			$type = "success";
		} else {
		    $message = "Problem in account activation.";
		    $type = "error";
		}
	}
?>

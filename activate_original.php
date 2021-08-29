<?php
	require_once("dbcontroller.php");
	$db_handle = new DBController();
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
<html>
<head>
<title>PHP User Activation</title>
<link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php if(isset($message)) { ?>
<div class="message <?php echo $type; ?>"><?php echo $message; ?></div>
<?php } ?>
</body></html>

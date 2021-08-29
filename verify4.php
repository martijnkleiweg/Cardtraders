<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

//mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = 'martinuskleiweg@gmail.com'; // YOUR gmail email
$mail->Password = 'radio538'; // YOUR gmail password


if(count($_POST)>0) {
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
	if(empty($_POST[$key])) {
	$message = ucwords($key) . " field is required";
	$type = "error";
	break;
	}
	}
	/* Password Matching Validation */
	if($_POST['signup-password'] != $_POST['confirm-password']){
	$message = 'Passwords should be the same<br>';
	$type = "error";
	}

	/* Email Validation */
	if(!isset($message)) {
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
	$message = "Invalid UserEmail";
	$type = "error";
	}
	}


	/* Validation to check if Terms and Conditions are accepted */
	if(!isset($message)) {
	if(!isset($_POST["terms"])) {
	$message = "Accept Terms and conditions before submit";
	$type = "error";
	}
	}


	if(!isset($message)) {
		require_once("dbcontroller.php");
		$db_handle = new DBController();
		$query = "SELECT * FROM registered_users where email = '" . $_POST["email"] . "'";
		$count = $db_handle->numRows($query);

		$query = "SELECT * FROM registered_users where user_name = '" . $_POST["username"] . "'";
		$count2 = $db_handle->numRows($query);
		}

		// PHP's password_hash is a safe choice to store passwords

		$hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);

		$username = $_POST["username"];


		if($count==0 && $count2==0) {
			$query = "INSERT INTO registered_users (user_name, password, email) VALUES
			('" . $_POST["username"] . "', '" . $hashedPassword . "', '" . $_POST["email"] . "')";
			$current_id = $db_handle->insertQuery($query);
			if(!empty($current_id)) {

				$mail->setFrom('martinuskleiweg@gmail.com', 'Cardtraders');
				$mail->addAddress($_POST["email"], 'Cardtrader User');
				$mail->addReplyTo('martinuskleiweg@gmail.com', 'Cardtraders');

				$actual_link = "http://$_SERVER[HTTP_HOST]".$current_id;

				// Setting the email content
		    $mail->IsHTML(true);
		    $mail->Subject = "Activate Cardtraders Account";
		    $mail->Body = "Hello " . $username .", thank you for signing up with Cardtraders. <br><br> Click this link to activate your account. <a href='" . $actual_link . "'>" . $actual_link . "</a><br> All the best,<br><br> Cardtraders Team";
		    $mail->AltBody = "Click this link to activate your account: $actual_link";


				if ($mail->send()){
					$message = "Thanks for signing up. Click on the link in the email to activate you account.";
					$type = "success";
				}
				unset($_POST);
			} else {
				$message = "Problem in registration. Try Again!";
				$type = "error";
			}
		}
		else if ($count==1 && $count2==1){
			$message = "Email address and username are already in use.";
			$type = "error";
		}
		 else if ($count==1 && $count2==0){
			$message = "Email address is already in use.";
			$type = "error";
		}
		 else if ($count==0 && $count2==1){
		 $message = "Username is already in use.";
		 $type = "error";
	}
	else {
		$message = "Problem in registration. Try Again!";
		$type = "error";
	}
}
?>

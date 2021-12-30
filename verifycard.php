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

$mail->Username = 'test@gmail.com'; // YOUR gmail email
$mail->Password = 'test'; // YOUR gmail password


if(count($_POST)>0) {
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
	if(empty($_POST[$key])) {
	$message = ucwords($key) . " field is required";
	$type = "error";
	break;
	}
	}

	/* Email Validation */
	if(!isset($message)) {
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
	$message = "Invalid Paypal account";
	$type = "error";
	}
	}


	/* Validation to check if Terms and Conditions are accepted */
	if(!isset($message)) {
	if(!isset($_POST["terms"])) {
	$message = "Please accept terms and conditions before submitting.";
	$type = "error";
	}
	}

	$brand = $_POST["brand"];

	$value = $_POST["balance"];

	$giftcard_code = $_POST["code"];

	$encrypt_giftcard_code = encrypt($giftcard_code);

	$pincode = $_POST["pincode"];

	$date = $_POST["date"];

	$price = $_POST["price"];

	$payout = $_POST["payout"];

	//$paypal = $_POST["paypal"];

	$username = $_POST["username"];

	$paypal = $_POST["email"];

	$email = $_POST["email"];

	$member_id = $_POST["member_id"];

	$verified = 0;

	$sold = 0;

	$paid = 0;



	if(!isset($message)) {
		require_once("dbcontroller2.php");
		$db_handle = new DBController2();
		}







		if(!isset($message)) {
			$query = "INSERT INTO giftcard (brand, value, giftcard_code, pincode, expiration_date, verified, sold) VALUES
			('" . $brand . "', '" . $value . "', '" . $encrypt_giftcard_code . "', '" . $pincode . "', '" . $date . "', '" . $verified . "', '" . $sold . "')";
			$current_id = $db_handle->insertQuery($query);

			//$query2 = "SELECT * from giftcard WHERE giftcard_code = $giftcard_code";

			//$giftcardarray = $db_handle->runQuery($query2);

			//echo $giftcardarray;

			$giftcard_id = intval(getGiftcardID($encrypt_giftcard_code));

			//die('Error: ' $giftcard_id);

			$query3 = "INSERT INTO sell_order (giftcard_id, price, member_id, payout, paypal, paid) VALUES
			('" . $giftcard_id . "', '" . $price . "', '" . $member_id . "', '" . $payout . "', '" . $paypal . "', '" . $paid . "')";

			$current_id2 = $db_handle->insertQuery($query3);



			if(!empty($current_id2)) {

				$mail->setFrom('martinuskleiweg@gmail.com', 'Cardtraders');
				$mail->addAddress($email, $username);
				$mail->addReplyTo('martinuskleiweg@gmail.com', 'Cardtraders');

				// Setting the email content
		    $mail->IsHTML(true);
		    $mail->Subject = "Card sell order - Cardtraders";
		    $mail->Body = "Hello " . $username .",<br><br> Thank you for submitting your " . $brand . " ". $value ." euro giftcard to sell it on Cardtraders. <br><br> We will now verify the balance of the card and list the card for sale if there are no issues. <br><br> We will let you know by email within 24 hours. All the best,<br><br> Cardtraders Team";

				if ($mail->send()){
					$message = "Thanks for submitting your card. We will now verify the balance and list the card for sale.";
					$type = "success";
				}
				unset($_POST);
			} else {
				$message = "Problem submitting the card1. Please try Again!";
				$type = "error";
			}
	}
	else {
		//$message = "Problem submitting the card2. Please try Again!";
		$type = "error";
	}
}

function encrypt($code){

		// Storing a string into the variable which
		// needs to be Encrypted
		$simple_string = $code;

		// Storingthe cipher method
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options  = 0;

		// Non-NULL Initialization Vector for encryption
		$encryption_iv = '1234567891011121';

		// Storing the encryption key
		$encryption_key = "fLs6uov1u3lPp5Av";

		// Using openssl_encrypt() function to encrypt the data
		$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);

		return $encryption;
}


function decrypt($code){

		// Storingthe cipher method
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options  = 0;

		// Non-NULL Initialization Vector for decryption
		$decryption_iv = '1234567891011121';

		// Storing the decryption key
		$decryption_key = "fLs6uov1u3lPp5Av";

		// Using openssl_decrypt() function to decrypt the data
		$decryption = openssl_decrypt($code, $ciphering, $decryption_key, $options, $decryption_iv);

		return $decryption;

}

function getGiftcardID($giftcard_code)
{
      $servername = "localhost";
      $username = "gcadmin";
      $password = "GCpass@86";
      $dbname = "giftcard";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT * from giftcard WHERE giftcard_code = '$giftcard_code'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
          $giftcard_id = $row["id"];
        }
      } else {
        echo "0 results";
      }
      $conn->close();
      return $giftcard_id;
}


?>

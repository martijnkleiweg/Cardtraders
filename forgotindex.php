<main>

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

include('db.php');
if(isset($_POST["email"]) && (!empty($_POST["email"]))){
$email = $_POST["email"];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$email) {
  	$error .="<p>Invalid email address please type a valid email address!</p>";
	}else{
	$sel_query = "SELECT * FROM `registered_users` WHERE email='".$email."'";
	$results = mysqli_query($con,$sel_query);
	$row = mysqli_num_rows($results);
	if ($row==""){
    sleep(2);
		$error .= "<p>If this is a known email address, an email has been sent with instructions on how to reset your password.</p>";
		}
	}
	if($error!=""){
	echo "<div class='error'>".$error."</div>
	<br /><a href='javascript:history.go(-1)'>Go Back</a>";
		}else{
	$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
	$expDate = date("Y-m-d H:i:s",$expFormat);
	$key = md5(2418*2+$email);
	$addKey = substr(md5(uniqid(rand(),1)),3,10);
	$key = $key . $addKey;
// Insert Temp Table
mysqli_query($con,
"INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
VALUES ('".$email."', '".$key."', '".$expDate."');");

$output='<p>Dear user,</p>';
$output.='<p>Please click on the following link to reset your password.</p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p><a href="http://192.168.2.13/cardtrader/user/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">http://192.168.2.13/cardtrader/user/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 day for security reason.</p>';
$output.='<p>If you did not request this forgotten password email, no action
is needed, your password will not be reset. However, you may want to log into
your account and change your security password as someone may have guessed it.</p>';
$output.='<p>Thanks,</p>';
$output.='<p>Cardtraders Team</p>';
$body = $output;
$subject = "Password Recovery - Cardtraders";

$email_to = $email;

$mail->setFrom('martinuskleiweg@gmail.com', 'Cardtraders');
$mail->addAddress($email_to, 'Cardtrader User');
$mail->addReplyTo('martinuskleiweg@gmail.com', 'Cardtraders');

$mail->IsHTML(true);
$mail->Subject = $subject;
$mail->Body = $body;

if(!$mail->send()){
echo "Mailer Error: " . $mail->ErrorInfo;
}else{
echo "<div class='error'>
<p>If this is a known email address, an email has been sent with instructions on how to reset your password.
</p>
</div><br /><br /><br />";
	}

		}

}else{
?>



<div class="phppot-container">
  <div class="sign-up-container">
    <div class="signup-align">
      <form name="login" action="" method="post"
        onsubmit="return loginValidation()">
        <div class="signup-heading">Forgot Password</div>

        <?php
        if (! empty($registrationResponse["status"])) {
        ?>
                    <?php
        if ($registrationResponse["status"] == "error") {
            ?>
            <div class="server-response error-msg"><?php echo $registrationResponse["message"]; ?></div>

                    <?php
        }

        else if ($registrationResponse["status"] == "success") {
            ?>
            <div class="server-response success-msg"><?php echo $registrationResponse["message"]; ?></div>
                    <?php
        }
        ?>
        <?php
        }
        ?>


      <div class="row">
          <div class="inline-block">
            <div class="form-label">
              Email<span class="required error" id="email-info"></span>
            </div>
            <input class="input-box-330" type="text" name="email"
              id="email">
          </div>
        </div>
        <div class="row">
          <input class="btn" type="submit" name="forgot-btn" id="forgot-btn"
            value="Forgot Password">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function signupValidation() {
var valid = true;

$("#email").removeClass("error-field");

var email = $("#email").val();

var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

$("#email-info").html("").hide();


if (email == "") {
  $("#email-info").html("required").css("color", "#ee0000").show();
  $("#email").addClass("error-field");
  valid = false;
} else if (email.trim() == "") {
  $("#email-info").html("Invalid email address").css("color", "#ee0000").show();
  $("#email").addClass("error-field");
  valid = false;
} else if (!emailRegex.test(email)) {
  $("#email-info").html("Invalid email address").css("color", "#ee0000")
      .show();
  $("#email").addClass("error-field");
  valid = false;
}
if (valid == false) {
  $('.error-field').first().focus();
  valid = false;
}
return valid;
}
</script>


<?php } ?>


</main>

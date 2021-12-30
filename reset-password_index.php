<main>

<?php
include('db.php');
if (isset($_GET["key"]) && isset($_GET["email"])
&& isset($_GET["action"]) && ($_GET["action"]=="reset")
&& !isset($_POST["action"])){
$key = $_GET["key"];
$email = $_GET["email"];
$curDate = date("Y-m-d H:i:s");
$query = mysqli_query($con,"
SELECT * FROM `password_reset_temp` WHERE `key`='".$key."' and `email`='".$email."';");
$row = mysqli_num_rows($query);
if ($row==""){
$error .= '<h2>Invalid Link</h2>
<h4><p>The link is invalid/expired. Either you did not copy the correct link from the email,
or you have already used the key in which case it is deactivated.</p></h4>';
	}else{
	$row = mysqli_fetch_assoc($query);
	$expDate = $row['expDate'];
	if ($expDate >= $curDate){
	?>
	<div class="phppot-container">
		<div class="sign-up-container">
			<div class="">
				<form name="update" action="" method="post"
					onsubmit="return resetPasswordValidation()">
					<div class="signup-heading">Reset Password</div>
<?php
if (! empty($displayMessage["status"])) {
    if ($displayMessage["status"] == "error") {
        ?>
				    <div class="server-response error-msg"><?php echo $displayMessage["message"]; ?></div>
<?php
    } else if ($displayMessage["status"] == "success") {
        ?>
                    <div class="server-response success-msg"><?php echo $displayMessage["message"]; ?></div>
<?php
    }
}
?>
				<div class="error-msg" id="error-msg"></div>
					<div class="row">
						<div class="inline-block">
							<input type="hidden" name="action" value="update" />
							<div class="form-label">
								Password<span class="required error" id="forgot-password-info"></span>
							</div>
							<input class="input-box-330" type="password" name="password"
								id="password">
						</div>
					</div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Confirm Password<span class="required error"
									id="confirm-password-info"></span>
							</div>
							<input class="input-box-330" type="password"
								name="confirm-password" id="confirm-password">
						</div>
					</div>
					<div class="row">
						<input class="btn" type="submit" name="reset-btn" id="reset-btn"
							value="Reset Password">
					</div>
						<input type="hidden" name="email" value="<?php echo $email;?>"/>
				</form>
			</div>
		</div>
	</div>

	<script>
function resetPasswordValidation() {
	var valid = true;
	$("#password").removeClass("error-field");
	$("#confirm-password").removeClass("error-field");

	var Password = $('#password').val();
    var ConfirmPassword = $('#confirm-password').val();

	if (Password.trim() == "") {
		$("#forgot-password-info").html("required").css("color", "#ee0000").show();
		$("#password").addClass("error-field");
		valid = false;
	}
	if (Password.length < 8){
		$("#signup-password-info").html("At least 8 characters").css("color", "#ee0000").show();
		$("#signup-password").addClass("error-field");
		valid = false;
	}
	if (ConfirmPassword.trim() == "") {
		$("#confirm-password-info").html("required").css("color", "#ee0000").show();
		$("#confirm-password").addClass("error-field");
		valid = false;
	}
	if(Password != ConfirmPassword){
        $("#error-msg").html("Both passwords must be same.").show();
        valid=false;
    }
	if (valid == false) {
		$('.error-field').first().focus();
		valid = false;
	}
	return valid;
}
</script>
<?php
}else{
$error .= "<h2>Link Expired</h2>
<h4><p>The link is expired. You are trying to use the expired link which as valid only 24 hours (1 days after request).<br /><br /></p></h4>";
				}
		}
if($error!=""){
	echo "<div class='error'>".$error."</div><br />";
	}
} // isset email key validate end


if(isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"]=="update")){
$error="";
$pass1 = mysqli_real_escape_string($con,$_POST["password"]);
$pass2 = mysqli_real_escape_string($con,$_POST["confirm-password"]);
$email = $_POST["email"];
$curDate = date("Y-m-d H:i:s");
if ($pass1!=$pass2){
		$error .= "<h4><p>Password do not match, both password should be same.<br /><br /></p></h4>";
		}
	if($error!=""){
		echo "<h4><div class='error'>".$error."</div><br /></h4>";
		}else{


$hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);


mysqli_query($con,
"UPDATE `registered_users` SET `password`='".$hashedPassword."', `trn_date`='".$curDate."' WHERE `email`='".$email."';");

mysqli_query($con,"DELETE FROM `password_reset_temp` WHERE `email`='".$email."';");

echo '<h4><p>Congratulations! Your password has been updated successfully.</p>
<p><a href="login.php">Click here</a> to Login.</p><br /></h4>';
		}
}
?>


<br /><br />

</main>

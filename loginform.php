<?php
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued
}
?>

<?php
use Phppot\Member;

if (! empty($_POST["login-btn"])) {
    require_once __DIR__ . '/Model/Member.php';
    $member = new Member();
    $loginResult = $member->loginMember();
}
?>


<main>
<div class="navbar">Home / Login</div>
<h1>Login</h1>

<div class="phppot-container">
  <div class="sign-up-container">
    			<div class="signup-align">
<form method="post" action="" onsubmit = "return validate()" name = "login" onsubmit="return loginValidation()">
<?php if(!empty($loginResult)){?>
<div class="error-msg"><?php echo $loginResult;?></div>
<?php }?>
        <div class="row">
						<div class="inline-block">
							<div class="form-label">
								Username<span class="required error" id="username-info"></span>
							</div>
							<input class="input-box-330" type="text" name="username"
								id="username">
						</div>
					</div>
          <div class="row">
        <div class="inline-block">
            <div class="form-label">
                Password<span class="required error" id="login-password-info"></span>
            </div>
            <input class="input-box-330" type="password" name="login-password" id="login-password">
        </div>
        </div>
        <div class="row">
  <input class="btn" type="submit" name="login-btn"
    id="login-btn" value="Login">
</div>
</form>

<div class="formtext"><p><a href="forgotpassword.php">Forgot password?</a></p>
<p></p>
<p><a href="forgotpassword.php">New to Cardtraders? Sign up here.</a></p></div>

<script>
function loginValidation() {
var valid = true;
$("#username").removeClass("error-field");
$("#password").removeClass("error-field");

var UserName = $("#username").val();
var Password = $('#login-password').val();

$("#username-info").html("").hide();

if (UserName.trim() == "") {
  $("#username-info").html("required.").css("color", "#ee0000").show();
  $("#username").addClass("error-field");
  valid = false;
}
if (Password.trim() == "") {
  $("#login-password-info").html("required.").css("color", "#ee0000").show();
  $("#login-password").addClass("error-field");
  valid = false;
}
if (valid == false) {
  $('.error-field').first().focus();
  valid = false;
}
return valid;
}
</script>

</main>

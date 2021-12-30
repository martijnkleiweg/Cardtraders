

  <main>
    <div class="phppot-container">
    		<div class="sign-up-container">
    			<div class="">
    				<form name="sign-up" method="post"
    					onsubmit="return signupValidation()">
    					<div class="signup-heading">Join Cardtraders</div>
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

    				<div class="error-msg" id="error-msg"></div>
						<?php if(isset($message)) { ?>
						<div class="message <?php echo $type; ?>"><?php echo $message; ?></div>
						<?php } ?>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Username<span class="required error" id="username-info"></span>
    							</div>
    							<input class="input-box-330" type="text" name="username"
    								id="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
    						</div>
    					</div>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Email<span class="required error" id="email-info"></span>
    							</div>
    							<input class="input-box-330" type="email" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
    						</div>
    					</div>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Password<span class="required error" id="signup-password-info"></span>
    							</div>
    							<input class="input-box-330" type="password"
    								name="signup-password" id="signup-password" value="">
    						</div>
    					</div>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Confirm Password<span class="required error"
    									id="confirm-password-info"></span>
    							</div>
    							<input class="input-box-330" type="password"
    								name="confirm-password" id="confirm-password" value="">
    						</div>
    					</div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    I accept the Terms and Conditions.<span class="required error"
                      id="terms-info"></span>
                  </div>
                  <input class="input-box-330" type="checkbox"
                    name="terms" id="terms">
                </div>
              </div>
    					<div class="row">
    						<input class="btn" type="submit" name="signup-btn"
    							id="signup-btn" value="Sign up">
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>

    	<script>
    function signupValidation() {
    	var valid = true;

    	$("#username").removeClass("error-field");
    	$("#email").removeClass("error-field");
    	$("#password").removeClass("error-field");
    	$("#confirm-password").removeClass("error-field");
      $("#terms").removeClass("error-field");


    	var UserName = $("#username").val();
    	var email = $("#email").val();
    	var Password = $('#signup-password').val();
        var ConfirmPassword = $('#confirm-password').val();
    	var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
      var terms = document.getElementById("terms").checked;

    	$("#username-info").html("").hide();
    	$("#email-info").html("").hide();

    	if (UserName.trim() == "") {
    		$("#username-info").html("required.").css("color", "#ee0000").show();
    		$("#username").addClass("error-field");
    		valid = false;

    	}
      if (UserName.length < 6){
        $("#username-info").html("At least 6 characters").css("color", "#ee0000").show();
        $("#username").addClass("error-field");
        valid = false;

      }
      if (UserName.length > 15){
        $("#username-info").html("Max. 15 characters").css("color", "#ee0000").show();
        $("#username").addClass("error-field");
        valid = false;

      }


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
    	if (Password.trim() == "") {
    		$("#signup-password-info").html("required").css("color", "#ee0000").show();
    		$("#signup-password").addClass("error-field");
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
        if (terms == false) {
          $("#terms-info").html("required").css("color", "#ee0000").show();
          $("#terms").addClass("error-field");
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

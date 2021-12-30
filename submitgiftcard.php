

  <main>
    <div class="phppot-container">
    		<div class="sign-up-container">
    			<div class="">
    				<form name="sign-up" method="post"
    					onsubmit="return cardValidation()">
    					<div class="signup-heading">Sell Giftcard</div>
              <div class="logo_gc3"><img src="images/bol_com.jpg"></div>
              <div class="accountheader">Bol.com</div>

    				<div class="error-msg" id="error-msg"></div>
						<?php if(isset($message)) { ?>
						<div class="message <?php echo $type; ?>"><?php echo $message; ?></div>
						<?php } ?>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Balance (€)<span class="required error" id="balance-info"></span>
    							</div>
    							<input class="input-box-330" type="number" name="balance"
    								id="balance" min="10" max="500">
    						</div>
    					</div>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Code<span class="required error" id="code-info"></span>
    							</div>
    							<input class="input-box-330" type="number" name="code" id="code"
                  minlength= "19" maxlength="19" value="">
    						</div>
    					</div>
    					<div class="row">
    						<div class="inline-block">
    							<div class="form-label">
    								Pincode<span class="required error" id="pincode-info"></span>
    							</div>
    							<input class="input-box-330" type="number"
    								name="pincode" id="pincode" minlength="5" maxlength="5" value="">
    						</div>
    					</div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    Expiration date<span class="required error" id="date-info"></span>
                  </div>
                  <input class="input-box-330" type="date"
                    name="date" id="date">
                </div>
              </div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    Discount (%)<span class="required error"
                      id="discount-info"></span>
                  </div>
                  <input class="input-box-330" type="number"
                    name="discount" id="discount" min="5" max="50" value="10">
                </div>
              </div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    Selling price (€)<span class="required error"
                      id="price-info"></span>
                    </div>
                <input class="input-box-330" type="number"
                  name="price" id="price" readonly>
                <script>

                $('#balance, #discount').change(function(){
                    var balance = parseFloat($('#balance').val()) || 0;
                    var discount = parseFloat($('#discount').val()) || 0;

                    var price = balance - ((discount/100) * (balance));
                    var payoutprice = 0.95 * price;

                    function roundToTwo(num) {
                        return +(Math.round(num + "e+2")  + "e-2");
                    }

                    var price = roundToTwo(price);
                    var payoutprice = roundToTwo(payoutprice);

                    $('#price').val(price);
                    $('#payout').val(payoutprice);

                });

                </script>

              </div>
            </div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    Payout amount (€)<span class="required error"
                      id="payout-info"></span>
                  </div>
                  <input class="input-box-330" type="number"
                    name="payout" id="payout" readonly>
                </div>
              </div>
              <div class="row">
                <div class="inline-block">
                  <div class="form-label">
                    Paypal account for payout<span class="required error"
                      id="email-info"></span>
                  </div>
                  <input class="input-box-330" type="email"
                    name="email" id="email" value="">
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
                    <input type="hidden"
                      name="brand" id="brand" value="Bol.com">
                      <input type="hidden"
                        name="member_id" id="member_id" value="<?php echo $member_id ?>">
                        <!-- <input type="hidden"
                          name="email" id="email" value="<?php echo $email ?>"> -->
                </div>
              </div>
    					<div class="row">
    						<input class="btn" type="submit" name="signup-btn"
    							id="signup-btn" value="Submit">
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>

    	<script>
    function cardValidation() {
    	var valid = true;

    	$("#balance").removeClass("error-field");
    	$("#code").removeClass("error-field");
    	$("#pincode").removeClass("error-field");
      $("#date").removeClass("error-field");
    	$("#discount").removeClass("error-field");
      $("#email").removeClass("error-field");
      $("#terms").removeClass("error-field");


      var balance = $("#balance").val();
      var code = $("#code").val();
      var pincode = $("#pincode").val();
      var date = $("#date").val();
      var dateregex = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
      var discount = $("#discount").val();
      var email = $("#email").val();
      var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
      var terms = document.getElementById("terms").checked;



    	$("#balance-info").html("").hide();
    	$("#email-info").html("").hide();

    	if (balance.trim() == "") {
    		$("#balance-info").html("required.").css("color", "#ee0000").show();
    		$("#balance").addClass("error-field");
    		valid = false;

    	}

      if (code.trim() == "") {
        $("#code-info").html("required.").css("color", "#ee0000").show();
        $("#code").addClass("error-field");
        valid = false;

      }

      if (code.length < 19){
        $("#code-info").html("Invalid code").css("color", "#ee0000").show();
        $("#code").addClass("error-field");
        valid = false;

      }
      if (code.length > 19){
        $("#code-info").html("Invalid code").css("color", "#ee0000").show();
        $("#balance").addClass("error-field");
        valid = false;

      }

      if (pincode.trim() == "") {
        $("#pincode-info").html("required.").css("color", "#ee0000").show();
        $("#pincode").addClass("error-field");
        valid = false;

      }

      if (pincode.length > 6){
        $("#pincode-info").html("Invalid pincode").css("color", "#ee0000").show();
        $("#pincode").addClass("error-field");
        valid = false;

      }

      if (pincode.length < 6){
        $("#pincode-info").html("Invalid pincode").css("color", "#ee0000").show();
        $("#pincode").addClass("error-field");
        valid = false;

      }

      if (date.trim() == "") {
        $("#date-info").html("required.").css("color", "#ee0000").show();
        $("#date").addClass("error-field");
        valid = false;

      }

      if (!dateregex.test(date)) {
    		$("#date-info").html("Invalid date").css("color", "#ee0000")
    				.show();
    		$("#date").addClass("error-field");
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

    	if (discount.trim() == "") {
    		$("#discount-info").html("required").css("color", "#ee0000").show();
    		$("#discount").addClass("error-field");
    		valid = false;
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

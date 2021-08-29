<?php
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued
}
?>
<h2>Form to enter a new record for table: User</h2><form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit = "return validate()" name = "userguestform" >
	<label for="firstname">Username: </label><input type="text" name="firstname" id="firstname" placeholder="firstname" required maxlength="30" minlength="1" value="" onchange="myFunction(this.id , this.value, 'as')"><span id="fbusername">  </span>
  <p><label for="lastname">Full name: </label><input type="text" name="full_name" id="full_name" placeholder="full_name" required maxlength="30" minlength="1" value="" onchange="myFunction(this.id , this.value, 'as')"><span id="fbfullname">  </span></p>
  <p><label for="email">Email: </label><input type="text" name="email" id="email" placeholder="email"  maxlength="50" minlength="4" value="" onchange="myFunction(this.id , this.value, 'reg')"><span id="fbemail">  </span></p>
	<p><label for="submit">Submit: </label><input type="submit" name="submit" id="submit" value="Submit"></p>
</form>

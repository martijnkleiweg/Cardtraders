<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Cardtraders.io</title>
  <meta name="description" content="Trade your gift cards for cash.">
  <meta name="author" content="Cardtraders">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <author name= "Martinus Kleiweg"</author>
  <link rel="stylesheet" href="css/styles.css">

</head>

<body>
<div class="topbar"><div class="topleft"><img src="mailicon.svg" height="12"></div>
<div class="topleft_email">info@cardtraders.io</div>
<div class="topmiddle"></div>
<div class="topright_social"><img src="insta.svg" height="15"></div>
<div class="topright_social"><img src="twitter.svg" height="15"></div>
<div class="topright_social"><img src="facebook.svg" height="15"></div>
</div>

<header>
<nav>
<div class = "logo"><a href="index.php"><img src="logo2.png" width="100%"></a></div>
<div class = "nav2"></div>
<li><a href="buycards.php">Buy</a></li>
<li><a href="sellcards.php">Sell</a></li>
<li><a href="login.php">Login</a></li>
<li><a href="help.php">Help</a></li>
<div class = "nav2"></div>
<div class ="cart"><a href="cart.php"><img src="cart2.svg"></a></div>
</nav>
</header>

<main>
<div class="navbar">Home / Login</div>
<h1>Login</h1>
<form>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit = "return validate()" name = "loginform" >
	<p><label for="username">Username</label></p>
  <p><input type="text" name="username" id="username" placeholder="username" required maxlength="30" minlength="1" value="" onchange="myFunction(this.id , this.value, 'as')"><span id="username">  </span></p>
  <p><label for="password">Password</label></p>
  <p><input type="password" name="password" id="password" placeholder="password" required maxlength="30" minlength="1" value="" onchange="myFunction(this.id , this.value, 'as')"><span id="password">  </span></p>
	<p><div class="button"><input type="submit" name="submit" id="submit" value="Submit"></div></p>
  <p></p>


</form>
<div class="formtext"><p><a href="forgotpassword.php">Forgot password?</a></p>
<p></p>
<p><a href="forgotpassword.php">New to Cardtraders? Sign up here.</a></p></div>
</main>

<footer>
&#169; Cardtraders.io
</footer>

<script src="js/scripts.js"></script>
</body>
</html>
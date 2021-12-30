<?php
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued


}

require "cartloader.php";

session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $member_id = $_SESSION["id"];


    session_write_close();


    //     $url = "./login.php";
    //     header("Location: $url");


}

// else {
//     // user is trying to access this page unauthorized
//     // so let's clear all session variables and redirect him to login page
//     session_unset();
//     session_write_close();
//     $url = "./login.php";
//     header("Location: $url");
// }




?>
<header>

<nav>
<div class = "logo"><a href="index.php"><img src="logo7.png" class="test1"><img src="logo8.png" class="test2"></a></div>
<div class = "nav2">
<li><a href="buycards.php">Buy Cards</a></li>
<li></li>
<li><a href="sellcards.php">Sell Cards</a></li>
<li></li>
<li><?php if (isset($username)) { ?>
  <a href="home.php">Account</a></li>
<?php } else{ ?>
  <a href="login.php">Login</a></li>
<?php } ?>
<li></li>
<li><a href="help.php">Help</a></li>
</div>
<div class ="cart"><a href="cart.php"><i class="fa" style="font-size:30px">&#xf07a;</i></a></div>
<span class='badge badge-warning' id='lblCartCount'>  <?php if (isset($item_quantity)) {
                                                                        echo $item_quantity;
                                                                      }
                                                                      else {
                                                                        echo "0";
                                                                      }
                                                                      ?>
                                                                    </span>
</nav>
</header>

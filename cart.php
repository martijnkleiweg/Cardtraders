<?php

define('ISITSAFETORUN', TRUE);// flag to be tested by all required pages before they run.
// define a range of variables used by the required files

session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $member_id = $_SESSION["id"];


    session_write_close();
} else {
    // user is trying to access this page unauthorized
    // so let's clear all session variables and redirect him to login page
    session_unset();
    session_write_close();
    $url = "./login.php";
    header("Location: $url");
}


// use Phppot\Member;
// if (! empty($_POST["signup-btn"])) {
//     require_once './Model/Member.php';
//     $member = new Member();
//     $registrationResponse = $member->registerMember();
// }


$mytable="registered_users";// The name of the database table we will use
$mytitle ='Cardtraders - Cart'; // The title of the page
$navigation = "Home / Cart";
$myfooter ='&#169; Cardtraders.io';  // The footer text
$mycss="cart/stylecart.css";// The name of the CSS file to be linked or blank if no external file is needed
$myjs="jquery/jquery-3.3.1.js";// The name of the JavaScript file to be linked or blank if no external file is needed
require "head.php";// Provides the page header and links CSS/JavaScript files

require "topbar.php"; //Provides the topbar (social)
require "navigation.php"; //Provides the navigation bar
require "navbar.php";

require_once "cart/cartindex.php";

require "footer.php"; //Provides the footer

require "mydatabase.php";// credentials file includes database name, user, password
require "dbconnect.php";// connects to database

?>

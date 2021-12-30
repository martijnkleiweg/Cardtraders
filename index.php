<?php

define('ISITSAFETORUN', TRUE);// flag to be tested by all required pages before they run.
// define a range of variables used by the required files
session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $member_id = $_SESSION["id"];


    session_write_close();
}


// use Phppot\Member;
// if (! empty($_POST["signup-btn"])) {
//     require_once './Model/Member.php';
//     $member = new Member();
//     $registrationResponse = $member->registerMember();
// }


$mytable="registered_users";// The name of the database table we will use
$mytitle ='Cardtraders'; // The title of the page
$myfooter ='&#169; Cardtraders.io';  // The footer text
$navigation = "Home";
$mycss="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"; //The name of the CSS file to be linked or blank if no external file is needed
$myjs="jquery/jquery-3.3.1.js";// The name of the JavaScript file to be linked or blank if no external file is needed
require "head.php";// Provides the page header and links CSS/JavaScript files

require "topbar.php"; //Provides the topbar (social)
require "navigation.php"; //Provides the navigation bar
require "navbar.php";

require_once "index2.php";

require "footer.php"; //Provides the footer

require "mydatabase.php";// credentials file includes database name, user, password
require "dbconnect.php";// connects to database

?>

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

require "cartloader.php";

?>


<?php


$mytable="registered_users";// The name of the database table we will use
$mytitle ='Cardtraders - My account'; // The title of the page
$navigation = "Home / My account";
$myfooter ='&#169; Cardtraders.io';  // The footer text
$mycss="cart/stylecart.css";// The name of the CSS file to be linked or blank if no external file is needed
$myjs="jquery/jquery-3.3.1.js";// The name of the JavaScript file to be linked or blank if no external file is needed
require "head.php";// Provides the page header and links CSS/JavaScript files

require "topbar.php"; //Provides the topbar (social)
require "navigation.php"; //Provides the navigation bar
require "navbar.php";

require_once "myaccount.php";

require "footer.php"; //Provides the footer

require "mydatabase.php";// credentials file includes database name, user, password
require "dbconnect.php";// connects to database

?>



<?php
function getMemberId($username){

  $servername = "localhost";
  $username = "gcadmin";
  $password = "GCpass@86";
  $dbname = "giftcard";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id FROM registered_users WHERE username = $username";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      $member_id = $row["id"];
    }
  } else {
    echo "0 results";
  }
  $conn->close();
  return $member_id;
}
?>

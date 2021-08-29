<?php
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued
}
$dbhandle = mysqli_connect( $hostname, $username, $password ) or die( "Unable to connect to MySQL");
$selected = mysqli_select_db(  $dbhandle, $mydatabase ) or die("Unable to connect to " . $mydatabase );
//echo "<p>Connected to MySQL database " . $mydatabase . "</p>"
?>

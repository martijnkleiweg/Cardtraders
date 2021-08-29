<?php
// This file holds the credentials to connect to the database.
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued
}
// If this point is reached, ISITSAFETORUN was defined and this file has been called from an appropriate place
$mydatabase='giftcard';
$username='gcadmin';
$password='GCpass@86'; // Note that is especially important for the password to be in single quotes
$hostname = 'localhost';
?>

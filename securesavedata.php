<?php
if(!defined('ISITSAFETORUN')) {
// This provides protection against file being called directly - if it is, ISITSAFETORUN will not be defined
   die('This file does no useful work alone'); // and so this warning message will be issued
}
?>
<?php
if (!empty($_POST))
{
	// Prepared statement with ? placeholders representing data values
   $sql = "INSERT INTO $mytable (firstname, lastname, email) VALUES (?,?,?)";

   // Preparing the statement
   $stmt = mysqli_prepare($dbhandle, $sql );

	// Bind the statement.
   // Notice we use:
   //          * the reference to the prepared statement
   //          * the three data types 'sss', one for each placeholder.  In this case all three are of type String (s)
   //          * the three data values
	mysqli_stmt_bind_param($stmt,'sss',$webdata['firstname'],$webdata['lastname'],$webdata['email']); //http://us3.php.net/manual/en/mysqli-stmt.bind-param.php

   // execute prepared statement
	mysqli_stmt_execute($stmt);

   // display results
   //printf("%d Row inserted.\n", mysqli_stmt_affected_rows($stmt));

   // close statement and connection
	mysqli_stmt_close($stmt);
}
?>

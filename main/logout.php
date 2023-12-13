<?php

include("session_start.php");

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../login/login.php");
exit();
?>

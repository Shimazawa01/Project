<?php
$actServername = "localhost";
$actUsername = "root";
$actPassword = "";
$actDbname = "act_management";

// Create connection
$actConn = new mysqli($actServername, $actUsername, $actPassword, $actDbname);

// Check connection
if ($actConn->connect_error) {
    die("Act Management Connection failed: " . $actConn->connect_error);
}
?>

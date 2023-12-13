<?php
$eventServername = "localhost";
$eventUsername = "root";
$eventPassword = "";
$eventDbname = "user_management";

// Create connection
$userConn = new mysqli($eventServername, $eventUsername, $eventPassword, $eventDbname);

// Check connection
if ($userConn->connect_error) {
    die("Event Management Connection failed: " . $eventConn->connect_error);
}
?>

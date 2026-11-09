<?php
// start session
session_start();

// clear all session variables
session_unset();

// destroy the server session
session_destroy();

// redirect user back to login page
header("Location: login.php");

// stop script execution
exit;
?>
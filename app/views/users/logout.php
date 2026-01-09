<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to the login page or home page
header("Location: login.php"); // Replace 'login.php' with your desired page
exit();
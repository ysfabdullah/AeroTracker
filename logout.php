<?php
session_start(); 

$_SESSION = [];


if (session_id() !== "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();

// Redirect to login page
header("Location: login.html");
exit();
?>
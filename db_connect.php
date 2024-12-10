<?php
$host = 'localhost';  
//this is our hostname
$username = 'root';   
//the username for the database
$password = '';       
//there is no password right now
$database = 'aerotracker'; 
//and then this is our database name called aerotracker. 


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// Database credentials
$DatabaseServer = 'localhost'; 
$DatabaseUsername = 'root'; 
$DatabasePassword = ''; 
$DatabaseName = 'haniims'; 
$DatabasePort = '3306'; 

// Create connection
$conn = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName, $DatabasePort);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

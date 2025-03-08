<?php
// Database connection file
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "movie_ticket_system"; 

//  connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

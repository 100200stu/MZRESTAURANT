<?php
// Database connection configuration
$servername = "localhost";
$username = "Ali123";  // Your DB username
$password = "@12345678910M"; // Your DB password
$dbname = "school"; // Your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
$servername = "localhost";
$username = "root";
$password = ""; // leave blank in XAMPP
$database = "ciclo_suerte";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>

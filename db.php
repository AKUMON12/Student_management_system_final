<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "student_system";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

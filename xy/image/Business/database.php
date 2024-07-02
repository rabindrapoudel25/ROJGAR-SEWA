<?php
$servername = "localhost"; // Change this to your database server name or IP address
$username = "your_username"; // Change this to your database username
$password = "your_password"; // Change this to your database password
$dbname = "business_db"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
$servername = "localhost";
$username = "your_db_username"; // Enter your database username
$password = "your_db_password"; // Enter your database password
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // Enter your database username
$password = ""; // Enter your database password
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error));
    exit();
}

// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];

// Validate input fields
if (empty($email) || empty($password)) {
    echo json_encode(array('success' => false, 'message' => 'Email and password are required.'));
    exit();
}

// Check if email exists
$sql = "SELECT * FROM users WHERE businessEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['password'] === $password) {
        echo json_encode(array('success' => true, 'message' => 'Login successful.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Incorrect password.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Email not found.'));
}

$stmt->close();
$conn->close();
?>

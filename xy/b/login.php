<?php
require 'database.php'; // Include the database connection

$response = array('success' => false, 'message' => 'An error occurred.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, businessName, password, profileImage FROM users WHERE businessEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $businessName, $hashedPassword, $profileImage);
    
    if ($stmt->fetch()) {
        if (password_verify($password, $hashedPassword)) {
            $response['success'] = true;
            $response['message'] = 'Login successful!';
            $response['profileImage'] = $profileImage; // Include the profile image in the response
            $response['businessName'] = $businessName; // Include the business name in the response
        } else {
            $response['message'] = 'Incorrect password.';
        }
    } else {
        $response['message'] = 'Email not found.';
    }
    $stmt->close();
}

echo json_encode($response);
?>

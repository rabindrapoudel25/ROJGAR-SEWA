<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['signup-email'];
    $password = password_hash($_POST['signup-password'], PASSWORD_DEFAULT);
    $phone = $_POST['signup-phone'];
    $gender = $_POST['gender'];

    // Check if email or phone number already exists
    $checkQuery = "SELECT * FROM employe WHERE email = '$email' OR phone = '$phone'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "Error: Email or phone number already exists.";
    } else {
        $sql = "INSERT INTO employe (fullname, email, password, phone, gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fullname, $email, $password, $phone, $gender);

        if ($stmt->execute() === TRUE) {
            $response['success'] = true;
            $response['message'] = "New record created successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "Error: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>

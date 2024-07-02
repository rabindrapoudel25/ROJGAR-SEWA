<?php
header('Content-Type: application/json');

// Connect to your database
$servername = "localhost";
$username = "root"; // Enter your database username
$password = ""; // Enter your database password
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error)));
}

// Retrieve form data
$businessName = $_POST['businessName'];
$businessAddress = $_POST['businessAddress'];
$businessType = $_POST['businessType'];
$businessEmail = $_POST['businessEmail'];
$businessPan = $_POST['businessPan'];
$businessPhoneNumber = $_POST['businessPhoneNumber'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Validate input fields
if (empty($businessName) || empty($businessAddress) || empty($businessType) || empty($businessEmail) || empty($businessPan) || empty($businessPhoneNumber) || empty($password) || empty($confirmPassword)) {
    die(json_encode(array('success' => false, 'message' => 'All fields are required.')));
}

if (strlen($businessName) < 5) {
    die(json_encode(array('success' => false, 'message' => 'Business name must be at least 5 characters long.')));
}

if (!filter_var($businessEmail, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(array('success' => false, 'message' => 'Invalid email format.')));
}

if (!preg_match('/^(0|9)\d{9}$/', $businessPhoneNumber)) {
    die(json_encode(array('success' => false, 'message' => 'Phone number must be 10 digits long and start with 0 or 9.')));
}

if (!preg_match('/^\d{5,10}$/', $businessPan)) {
    die(json_encode(array('success' => false, 'message' => 'PAN number must be between 5 and 10 digits long.')));
}

if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
    die(json_encode(array('success' => false, 'message' => 'Password must be at least 6 characters long and contain both letters and numbers.')));
}

if ($password !== $confirmPassword) {
    die(json_encode(array('success' => false, 'message' => 'Passwords do not match.')));
}

// Check if email, phone number, and PAN number already exist in the database
$sql = "SELECT * FROM users WHERE businessEmail = ? OR businessPhoneNumber = ? OR businessPan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $businessEmail, $businessPhoneNumber, $businessPan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["businessEmail"] === $businessEmail) {
            die(json_encode(array('success' => false, 'message' => 'Email already exists.')));
        }
        if ($row["businessPhoneNumber"] === $businessPhoneNumber) {
            die(json_encode(array('success' => false, 'message' => 'Phone number already exists.')));
        }
        if ($row["businessPan"] === $businessPan) {
            die(json_encode(array('success' => false, 'message' => 'PAN number already exists.')));
        }
    }
}

// Process image uploads
$uploadDir = 'uploads/';
$panDocumentUniqueName = '';
$profileImageUniqueName = '';

// Handle PAN document upload
if (isset($_FILES['businessPanDocument']) && $_FILES['businessPanDocument']['error'] === UPLOAD_ERR_OK) {
    $panDocumentName = $_FILES['businessPanDocument']['name'];
    $panDocumentUniqueName = uniqid() . '_' . $panDocumentName;
    if (!move_uploaded_file($_FILES['businessPanDocument']['tmp_name'], $uploadDir . $panDocumentUniqueName)) {
        die(json_encode(array('success' => false, 'message' => 'Error moving PAN document file.')));
    }
} else {
    die(json_encode(array('success' => false, 'message' => 'PAN document upload failed.')));
}

// Handle profile image upload
if (isset($_FILES['businessProfileImage']) && $_FILES['businessProfileImage']['error'] === UPLOAD_ERR_OK) {
    $profileImageName = $_FILES['businessProfileImage']['name'];
    $profileImageUniqueName = uniqid() . '_' . $profileImageName;
    if (!move_uploaded_file($_FILES['businessProfileImage']['tmp_name'], $uploadDir . $profileImageUniqueName)) {
        die(json_encode(array('success' => false, 'message' => 'Error moving business profile image file.')));
    }
} else {
    die(json_encode(array('success' => false, 'message' => 'Business profile image upload failed.')));
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user data into the database with unique filenames
$sql = "INSERT INTO users (businessName, businessAddress, businessType, businessEmail, businessPan, businessPhoneNumber, password, panDocument, profileImage) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $businessName, $businessAddress, $businessType, $businessEmail, $businessPan, $businessPhoneNumber, $hashedPassword, $panDocumentUniqueName, $profileImageUniqueName);

if ($stmt->execute()) {
    echo json_encode(array('success' => true, 'message' => 'Successfully signed up! Go for login.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>

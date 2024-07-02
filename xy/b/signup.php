<?php
// Connect to your database
$servername = "localhost";
$username = ""; // Enter your database username
$password = ""; // Enter your database password
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to return JSON response
function jsonResponse($message, $status = 200) {
    http_response_code($status);
    echo json_encode(["message" => $message, "success" => $status === 200]);
    exit;
}

// Retrieve form data
$businessName = $_POST['businessName'] ?? '';
$businessAddress = $_POST['businessAddress'] ?? '';
$businessType = $_POST['businessType'] ?? '';
$businessEmail = $_POST['businessEmail'] ?? '';
$businessPan = $_POST['businessPan'] ?? '';
$businessPhoneNumber = $_POST['phoneNumber'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Validate input fields
if (empty($businessName) || empty($businessAddress) || empty($businessType) || empty($businessEmail) || empty($businessPan) || empty($businessPhoneNumber) || empty($password) || empty($confirmPassword)) {
    jsonResponse("All fields are required.", 400);
}

if ($password !== $confirmPassword) {
    jsonResponse("Passwords do not match.", 400);
}

// Check if email, phone number, and PAN number already exist in the database
$sql = "SELECT * FROM users WHERE businessEmail = '$businessEmail' OR businessPhoneNumber = '$businessPhoneNumber' OR businessPan = '$businessPan'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["businessEmail"] === $businessEmail) {
            jsonResponse("Email already exists.", 400);
        }
        if ($row["businessPhoneNumber"] === $businessPhoneNumber) {
            jsonResponse("Phone number already exists.", 400);
        }
        if ($row["businessPan"] === $businessPan) {
            jsonResponse("PAN number already exists.", 400);
        }
    }
}

// Process image uploads
$uploadDir = 'uploads/';
$panDocumentUniqueName = '';
$profileImageUniqueName = '';

// Handle PAN document upload if available
if (isset($_FILES['panDocument']) && $_FILES['panDocument']['error'] === UPLOAD_ERR_OK) {
    $panDocumentName = $_FILES['panDocument']['name'];
    $panDocumentUniqueName = uniqid() . '_' . $panDocumentName;
    if (!move_uploaded_file($_FILES['panDocument']['tmp_name'], $uploadDir . $panDocumentUniqueName)) {
        jsonResponse("Error moving PAN document file.", 500);
    }
} else {
    jsonResponse("PAN document upload failed.", 400);
}

// Handle profile image upload if available
if (isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] === UPLOAD_ERR_OK) {
    $profileImageName = $_FILES['companyLogo']['name'];
    $profileImageUniqueName = uniqid() . '_' . $profileImageName;
    if (!move_uploaded_file($_FILES['companyLogo']['tmp_name'], $uploadDir . $profileImageUniqueName)) {
        jsonResponse("Error moving business profile image file.", 500);
    }
} else {
    jsonResponse("Business profile image upload failed.", 400);
}

// Insert user data into the database with unique filenames
$sql = "INSERT INTO users (businessName, businessAddress, businessType, businessEmail, businessPan, businessPhoneNumber, password, panDocument, profileImage) 
        VALUES ('$businessName', '$businessAddress', '$businessType', '$businessEmail', '$businessPan', '$businessPhoneNumber', '$password', '$panDocumentUniqueName', '$profileImageUniqueName')";

if ($conn->query($sql) === TRUE) {
    jsonResponse("Successfully signed up! Go for login");
} else {
    jsonResponse("Error: " . $sql . "<br>" . $conn->error, 500);
}

$conn->close();
?>

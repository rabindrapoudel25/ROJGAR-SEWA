<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

// Database credentials
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "business_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user's email from the session
$email = $_SESSION['email'];

// Query the database to fetch user details
$sql = "SELECT * FROM users WHERE businessEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // User found, fetch user details
    $row = $result->fetch_assoc();
    $businessName = $row['businessName'];
    $businessAddress = $row['businessAddress'];
    $businessType = $row['businessType'];
    $businessEmail = $row['businessEmail'];
    $businessPan = $row['businessPan'];
    $businessPhoneNumber = $row['businessPhoneNumber'];
    $panDocument = $row['panDocument'];
    $profileImage = $row['profileImage'];

    // Construct URLs for images
    $panDocumentURL = "uploads/" . $panDocument;
    $profileImageURL = "uploads/" . $profileImage;
} else {
    // Error handling if user not found
    echo "Error: User not found.";
    exit();
}

// Handle profile image update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['newProfileImage'])) {
    // Process the uploaded file
    $uploadDir = 'uploads/';
    $newProfileImageName = uniqid() . '_' . basename($_FILES["newProfileImage"]["name"]);
    $newProfileImage = $uploadDir . $newProfileImageName;
    if (move_uploaded_file($_FILES["newProfileImage"]["tmp_name"], $newProfileImage)) {
        // Update profile image filename in the database
        $updateQuery = "UPDATE users SET profileImage='$newProfileImageName' WHERE businessEmail='$email'";
        if ($conn->query($updateQuery) === TRUE) {
            // Update successful, delete the old profile image file if it exists
            if (file_exists($uploadDir . $profileImage)) {
                unlink($uploadDir . $profileImage);
            }
            $profileImage = $newProfileImageName;
            $profileImageURL = "uploads/" . $profileImage;
        } else {
            echo "Error updating profile image in the database: " . $conn->error;
        }
    } else {
        echo "Error uploading profile image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    <p>Business Name: <?php echo htmlspecialchars($businessName); ?></p>
    <p>Business Address: <?php echo htmlspecialchars($businessAddress); ?></p>
    <p>Business Type: <?php echo htmlspecialchars($businessType); ?></p>
    <p>Business Email: <?php echo htmlspecialchars($businessEmail); ?></p>
    <p>Business PAN: <?php echo htmlspecialchars($businessPan); ?></p>
    <p>Business Phone Number: <?php echo htmlspecialchars($businessPhoneNumber); ?></p>
    <p>PAN Document: <a href="<?php echo htmlspecialchars($panDocumentURL); ?>" target="_blank">View</a></p>
    <p>Profile Image: <img src="<?php echo htmlspecialchars($profileImageURL); ?>" alt="Profile Image" width="100" height="100"></p>

    <form id="updateProfileImageForm" action="profile.php" method="post" enctype="multipart/form-data">
        <label for="newProfileImage">Upload New Profile Image:</label>
        <input type="file" name="newProfileImage" id="newProfileImage" accept=".jpg,.jpeg,.png" required>
        <input type="submit" value="Update Profile Image">
    </form>
</body>
</html>

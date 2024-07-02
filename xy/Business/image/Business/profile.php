<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "";
$password = "";
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user's email from the session
$email = $_SESSION['email'];

// Query the database to fetch user details
$sql = "SELECT * FROM users WHERE businessEmail = '$email'";
$result = $conn->query($sql);

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
    $newProfileImageName = uniqid() . '_' . basename($_FILES["newProfileImage"]["name"]); // Generate unique filename
    $newProfileImage = $uploadDir . $newProfileImageName;
    if (move_uploaded_file($_FILES["newProfileImage"]["tmp_name"], $newProfileImage)) {
        // Update profile image filename in the database
        $updateQuery = "UPDATE users SET profileImage='$newProfileImageName' WHERE businessEmail='$email'";
        if ($conn->query($updateQuery) === TRUE) {
            // Update successful, delete the old profile image file if it exists
            if (file_exists($uploadDir . $profileImage)) {
                unlink($uploadDir . $profileImage); // Delete the old profile image file
            }
            $profileImage = $newProfileImageName; // Update the profile image filename
            $profileImageURL = "uploads/" . $profileImage; // Update the profile image URL
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
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .navbar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            overflow-x: hidden;
            padding-top: 20px;
            color: white;
        }
        
        .navbar a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 20px;
            color: white;
            display: block;
        }
        
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .profile-content {
            margin-left: 200px; /* Adjust according to the width of the navbar */
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            margin: 10px 0;
            font-size: 18px;
        }

        img {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            height: auto;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Style to display details like a table */
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: #fff; /* Background color for the details section */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        .details p {
            margin: 0;
            padding: 10px;
        }

        .details strong {
            font-weight: bold;
            color: #333;
        }

        .details a {
            color: blue;
            text-decoration: none;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="about.php">Home </a>
        <a href="add_job.php">Add Job</a>
        <a href="listed_jobs.php">Listed Jobs</a>
        <a href="profile.php">Profile</a>
        <a href="about.php">About Us</a>qwe
    </div>
    
    <!-- Profile Content -->
    <div class="profile-content">
        <h1>User Profile</h1>
        <div class="details">
            <p><strong>Business Name:</strong> <?php echo $businessName; ?></p>
            <p><strong>Business Address:</strong> <?php echo $businessAddress; ?></p>
            <p><strong>Type of Business:</strong> <?php echo $businessType; ?></p>
            <p><strong>Email:</strong> <?php echo $businessEmail; ?></p>
            <p><strong>Business PAN:</strong> <?php echo $businessPan; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $businessPhoneNumber; ?></p>
            <p><strong>PAN Document:</strong> <a href="<?php echo $panDocumentURL; ?>" download>Download PAN Document</a> <br><img src="<?php echo $panDocumentURL; ?>" alt="PAN Document"></p>
            <p><strong>Profile Image:</strong> <a href="<?php echo $profileImageURL; ?>" download>Download Profile Image</a> <br><img src="<?php echo $profileImageURL; ?>" alt="Profile Image"></p>
        </div>

        <!-- Form to upload new profile image -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <h2>Change Profile Image</h2>
            <input type="file" name="newProfileImage" required>
            <input type="submit" value="Upload">
        </form>
    </div>
</body>
</html>

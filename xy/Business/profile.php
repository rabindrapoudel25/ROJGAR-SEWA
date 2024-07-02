<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Enter your database username
$password = ""; // Enter your database password
$dbname = "business_db";

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the logged-in user's profile data
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE businessEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: User not found.";
    exit();
}

// Handle profile image update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'updateProfileImage') {
    if (isset($_FILES['newProfileImage']) && $_FILES['newProfileImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $newProfileImageName = $_FILES['newProfileImage']['name'];
        $newProfileImageUniqueName = uniqid() . '_' . $newProfileImageName;

        if (move_uploaded_file($_FILES['newProfileImage']['tmp_name'], $uploadDir . $newProfileImageUniqueName)) {
            // Update the profile image in the database
            $stmt = $conn->prepare("UPDATE users SET profileImage = ? WHERE businessEmail = ?");
            $stmt->bind_param("ss", $newProfileImageUniqueName, $email);

            if ($stmt->execute()) {
                // Delete the old profile image if it exists and is different from the default
                if ($user['profileImage'] && file_exists($uploadDir . $user['profileImage'])) {
                    unlink($uploadDir . $user['profileImage']);
                }
                $user['profileImage'] = $newProfileImageUniqueName;
                echo "<script>alert('Profile image updated successfully.');</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error moving profile image file.";
        }
    } else {
        echo "Profile image upload failed.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            color: white;
            padding-top: 20px;
            height: 100%;
        }

        .navbar a {
            padding: 10px;
            display: block;
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .profile-card {
            background-color: #fff;
            width: 400px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .profile-card h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .profile-card label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
            color: #555;
        }

        .profile-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile-card input, .profile-card button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .profile-card button {
            background-color: crimson;
            color: white;
            cursor: pointer;
        }

        .profile-card button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="job_post.php">Create Job Post</a>
        <a href="listed_jobs.php">Listed Jobs</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="profile-card">
            <h2>Profile</h2>
            <img src="uploads/<?php echo htmlspecialchars($user['profileImage']); ?>" alt="Profile Image">
            <p>Business Name: <?php echo htmlspecialchars($user['businessName']); ?></p>
            <p>Business Address: <?php echo htmlspecialchars($user['businessAddress']); ?></p>
            <p>Business Type: <?php echo htmlspecialchars($user['businessType']); ?></p>
            <p>Business Email: <?php echo htmlspecialchars($user['businessEmail']); ?></p>
            <p>Business PAN: <?php echo htmlspecialchars($user['businessPan']); ?></p>
            <p>Business Phone Number: <?php echo htmlspecialchars($user['businessPhoneNumber']); ?></p>
            <a href="uploads/<?php echo htmlspecialchars($user['panDocument']); ?>" download>Download PAN Document</a><br>
            <a href="uploads/<?php echo htmlspecialchars($user['profileImage']); ?>" download>Download Profile Image</a>

            <form action="profile.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="updateProfileImage">
                <label for="newProfileImage">Update Profile Image</label>
                <input type="file" id="newProfileImage" name="newProfileImage" required>
                <button type="submit">Update Profile Image</button>
            </form>
        </div>
    </div>
</body>
</html>

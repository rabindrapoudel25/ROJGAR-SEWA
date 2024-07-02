<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: landing.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user']['id'];
    $fullname = $_SESSION['user']['fullname'];
    $email = $_SESSION['user']['email'];
    $coverLetter = $_POST['cover_letter'];
    $cv = $_FILES['cv'];

    // Validate CV upload
    if ($cv['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $cv['error']);
    }

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($cv["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allow only certain file formats
    $allowedTypes = ["pdf", "doc", "docx"];
    if (!in_array($fileType, $allowedTypes)) {
        die("Sorry, only PDF, DOC & DOCX files are allowed.");
    }

    if (move_uploaded_file($cv["tmp_name"], $targetFile)) {
        // Save the application to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "business_db";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO applications (user_id, fullname, email, cover_letter, cv_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userId, $fullname, $email, $coverLetter, $targetFile);

        if ($stmt->execute()) {
            echo "Application submitted successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

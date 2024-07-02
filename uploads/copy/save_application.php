<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to apply."]);
    exit();
}

// Function to generate a unique 6-digit applicant ID
function generateApplicantID() {
    return rand(100000, 999999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user']['id'];
    $applicantName = $_POST['applicant_name'];
    $applicantEmail = $_POST['applicant_email'];
    $coverLetter = $_POST['cover_letter'];
    $jobPostId = $_POST['job_post_id'];
    $applicantID = generateApplicantID();

    // Handle file upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["cv"]["name"]);
    $uploadOk = 1;
    $cvFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo json_encode(["success" => false, "message" => "Sorry, file already exists."]);
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["cv"]["size"] > 5000000) {
        echo json_encode(["success" => false, "message" => "Sorry, your file is too large."]);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($cvFileType != "pdf" && $cvFileType != "doc" && $cvFileType != "docx") {
        echo json_encode(["success" => false, "message" => "Sorry, only PDF, DOC & DOCX files are allowed."]);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(["success" => false, "message" => "Sorry, your file was not uploaded."]);
    } else {
        if (move_uploaded_file($_FILES["cv"]["tmp_name"], $targetFile)) {
            $cvPath = $targetFile;

            // Insert data into database
            $sql = "INSERT INTO job_applications (user_id, applicant_name, applicant_email, cover_letter, cv_path, job_post_id, applicant_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssi", $userId, $applicantName, $applicantEmail, $coverLetter, $cvPath, $jobPostId, $applicantID);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Application submitted successfully!", "applicant_id" => $applicantID]);
            } else {
                echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Sorry, there was an error uploading your file."]);
        }
    }
    $conn->close();
}
?>

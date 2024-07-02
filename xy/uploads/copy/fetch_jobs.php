<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "business_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT job_posts.title, job_posts.salary, job_posts.dateEnd, job_posts.description, job_posts.dateOpen,
               job_posts.interviewDate, job_posts.startTime, job_posts.endTime, 
               users.businessType, users.profileImage, users.businessEmail, users.businessPhoneNumber, 
               users.businessName AS company, users.businessAddress AS location
        FROM job_posts 
        JOIN users ON job_posts.user_id = users.id";

$result = $conn->query($sql);

$jobs = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();

echo json_encode($jobs);
?>

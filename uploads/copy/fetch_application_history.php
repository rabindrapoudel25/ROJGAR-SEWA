<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobportal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$applicant_email = $_GET['email'];
$sql = "SELECT jobs.title, applications.applicant_name, applications.applicant_email, applications.cover_letter, applications.cv_upload FROM applications INNER JOIN jobs ON applications.job_id = jobs.id WHERE applications.applicant_email = '$applicant_email'";
$result = $conn->query($sql);

$applications = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

$conn->close();

echo json_encode($applications);
?>

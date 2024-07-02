

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit;
}

// If the user is logged in, get the user's email
$userEmail = $_SESSION['email'];

// Retrieve form data
$jobTitle = $_POST['jobTitle'];
$company = $_POST['company'];
$city = $_POST['city'];
$street = $_POST['street'];
$salary = $_POST['salary'];
$education = $_POST['education'];
$experience = $_POST['experience'];
$vacancyEndDate = $_POST['vacancyEndDate'];
$description = $_POST['description'];

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

// Get the user's ID based on the email
$sql = "SELECT id FROM users WHERE businessEmail = '$userEmail'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];

    // Insert job vacancy data into the database
    $sql = "INSERT INTO job_vacancies (user_id, jobTitle, company, city, street, salary, education, experience, vacancyEndDate, description) 
            VALUES ('$userId', '$jobTitle', '$company', '$city', '$street', '$salary', '$education', '$experience', '$vacancyEndDate', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>';
        echo 'alert("Job vacancy added successfully.");';
        echo 'window.location.href = "add_job.html";'; // Redirect to add job page
        echo '</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "User not found.";
}

$conn->close();
?>

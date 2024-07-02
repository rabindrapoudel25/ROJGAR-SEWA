<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listed Jobs</title>
<style>
/* CSS for Job Listing */
.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  grid-gap: 20px;
  padding: 20px;
}

.job-card {
  border: 1px solid #ccc;
  padding: 20px;
  border-radius: 5px;
  background-size: cover;
}

/* Additional CSS for formatting */
/* Add your styles here */
</style>
</head>
<body>

<h2>Listed Jobs</h2>

<div class="grid-container">
    <?php
    session_start();
    
    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        die("You are not logged in.");
    }

    // Get the logged-in user's email
    $userEmail = $_SESSION['email'];

    // Connect to the database
    $servername = "localhost";
    $username = ""; // Enter your database username
    $password = ""; // Enter your database password
    $dbname = "business_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to fetch job vacancies for the logged-in user
    $sql = "SELECT j.*, u.businessName, u.businessType, u.businessEmail, u.businessPhoneNumber, u.profileImage 
            FROM job_vacancies j
            INNER JOIN users u ON j.user_id = u.id
            WHERE u.businessEmail = '$userEmail'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="job-card" style="background-image: url(\'uploads/' . $row['profileImage'] . '\');">';
            echo '<h3>' . $row['jobTitle'] . '</h3>';
            echo '<p><strong>Company:</strong> ' . $row['company'] . '</p>';
            echo '<p><strong>Location:</strong> ' . $row['city'] . ', ' . $row['street'] . '</p>';
            echo '<p><strong>Salary:</strong> ' . $row['salary'] . '</p>';
            echo '<p><strong>Education:</strong> ' . $row['education'] . '</p>';
            echo '<p><strong>Experience:</strong> ' . $row['experience'] . '</p>';
            echo '<p><strong>Vacancy End Date:</strong> ' . $row['vacancyEndDate'] . '</p>';
            echo '<p><strong>Description:</strong> ' . $row['description'] . '</p>';
            echo '<p><strong>Business Name:</strong> ' . $row['businessName'] . '</p>';
            echo '<p><strong>Business Type:</strong> ' . $row['businessType'] . '</p>';
            echo '<p><strong>Email:</strong> ' . $row['businessEmail'] . '</p>';
            echo '<p><strong>Phone:</strong> ' . $row['businessPhoneNumber'] . '</p>';
            echo '</div>';
        }
    } else {
        echo "No job vacancies found for the logged-in user.";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
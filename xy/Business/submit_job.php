<?php
session_start();

// Database connection details
$servername = "localhost";
$username = ""; // Enter your database username
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

// Retrieve the logged-in user's ID from the database
$email = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE businessEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
} else {
    echo "Error: User not found.";
    exit();
}

// Handle form submission to save job post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $salary = htmlspecialchars($_POST['salary']);
    $dateOpen = htmlspecialchars($_POST['dateOpen']);
    $dateEnd = htmlspecialchars($_POST['dateEnd']);
    $education = htmlspecialchars($_POST['education']);
    $experience = htmlspecialchars($_POST['experience']);
    $description = htmlspecialchars($_POST['description']);
    $interviewDate = htmlspecialchars($_POST['interviewDate']);
    $interviewTimeFrom = htmlspecialchars($_POST['interviewTimeFrom']);
    $interviewTimeTo = htmlspecialchars($_POST['interviewTimeTo']);

    // Insert job post data into the database
    $sql = "INSERT INTO job_posts (user_id, title, salary, dateOpen, dateEnd, education, experience, description, interviewDate, interviewTimeFrom, interviewTimeTo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssss", $user_id, $title, $salary, $dateOpen, $dateEnd, $education, $experience, $description, $interviewDate, $interviewTimeFrom, $interviewTimeTo);

    if ($stmt->execute()) {
        echo "Job post created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch job posts for the logged-in user
$sql = "SELECT * FROM job_posts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $jobs = $stmt->get_result();
} else {
    echo "Error fetching job posts: " . $stmt->error;
    $jobs = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Posts</title>
    <style>
        /* Your CSS styles remain unchanged */
    </style>
</head>
<body>
    <div class="navbar">
        <a href="job_post.php">Create Job Post</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="job-card">
            <h2>Create Job Post</h2>
            <form action="" method="POST">
                <label for="title">Job Title</label>
                <input type="text" id="title" name="title" required>

                <label for="salary">Salary (Negotiable or Range)</label>
                <input type="text" id="salary" name="salary" required>

                <label for="dateOpen">Date Open</label>
                <input type="date" id="dateOpen" name="dateOpen" required>

                <label for="dateEnd">Date End</label>
                <input type="date" id="dateEnd" name="dateEnd" required>

                <label for="education">Education</label>
                <input type="text" id="education" name="education" required>

                <label for="experience">Experience</label>
                <input type="text" id="experience" name="experience" required>

                <label for="description">Job Description</label>
                <textarea id="description" name="description" required></textarea>

                <label for="interviewDate">Interview Date</label>
                <input type="date" id="interviewDate" name="interviewDate" required>

                <label for="interviewTimeFrom">Interview Time From</label>
                <input type="time" id="interviewTimeFrom" name="interviewTimeFrom" required>

                <label for="interviewTimeTo">Interview Time To</label>
                <input type="time" id="interviewTimeTo" name="interviewTimeTo" required>

                <button type="submit">Submit Job</button>
            </form>
        </div>

        <div class="job-list">
            <h2>Job Posts</h2>
            <?php if ($jobs && $jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="job-item">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p>Salary: <?php echo htmlspecialchars($job['salary']); ?></p>
                        <p>Date Open: <?php echo htmlspecialchars($job['dateOpen']); ?></p>
                        <p>Date End: <?php echo htmlspecialchars($job['dateEnd']); ?></p>
                        <p>Education: <?php echo htmlspecialchars($job['education']); ?></p>
                        <p>Experience: <?php echo htmlspecialchars($job['experience']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($job['description']); ?></p>
                        <p>Interview Date: <?php echo htmlspecialchars($job['interviewDate']); ?></p>
                        <p>Interview Time: From <?php echo htmlspecialchars($job['interviewTimeFrom']); ?> To <?php echo htmlspecialchars($job['interviewTimeTo']); ?></p>
                        <button onclick="showEditForm(<?php echo $job['id']; ?>)">Edit</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No job posts found.</p>
            <?php endif; ?>
        </div>

        <div class="edit-form" id="edit-form">
            <h2>Edit Job Post</h2>
            <form action="update_job.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="job_id" id="edit-job-id">
                <!-- Include fields for editing here -->
                <!-- These fields will be populated dynamically using JavaScript -->
                <button type="submit">Update Job</button>
            </form>
        </div>
    </div>

    <script>
        function showEditForm(jobId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_job_details.php?job_id=" + jobId, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var job = JSON.parse(xhr.responseText);
                    document.getElementById('edit-job-id').value = job.id;
                    // Populate edit form fields with job data
                    // Update this based on your actual form fields in 'update_job.php'
                    // Example: document.getElementById('edit-title').value = job.title;
                    //          document.getElementById('edit-salary').value = job.salary;
                    //          ... and so on
                    document.getElementById('edit-form').style.display = 'block';
                    window.scrollTo(0, 0);
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>

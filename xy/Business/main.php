<?php
session_start();

// Database connection details
$servername = "localhost";
$username = ""; // Add your database username
$password = ""; // Add your database password
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
$sql = "SELECT id FROM users WHERE businessEmail = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
} else {
    echo "Error: User not found.";
    exit();
}

// Handle form submission to save job post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $title = htmlspecialchars($_POST['title']);
    $salary = htmlspecialchars($_POST['salary']);
    $dateOpen = htmlspecialchars($_POST['dateOpen']);
    $dateEnd = htmlspecialchars($_POST['dateEnd']);
    $education = htmlspecialchars($_POST['education']);
    $experience = htmlspecialchars($_POST['experience']);
    $description = htmlspecialchars($_POST['description']);
    $interviewDate = htmlspecialchars($_POST['interviewDate']);
    $startTime = htmlspecialchars($_POST['startTime']);
    $endTime = htmlspecialchars($_POST['endTime']);

    if ($_POST['action'] == 'create') {
        // Insert job post data into the database
        $stmt = $conn->prepare("INSERT INTO job_posts (user_id, title, salary, dateOpen, dateEnd, education, experience, description, interviewDate, startTime, endTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssss", $user_id, $title, $salary, $dateOpen, $dateEnd, $education, $experience, $description, $interviewDate, $startTime, $endTime);

        if ($stmt->execute()) {
            echo "Job post created successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif ($_POST['action'] == 'edit') {
        $job_id = intval($_POST['job_id']);

        // Update job post data in the database
        $stmt = $conn->prepare("UPDATE job_posts SET title = ?, salary = ?, dateOpen = ?, dateEnd = ?, education = ?, experience = ?, description = ?, interviewDate = ?, startTime = ?, endTime = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssssssssssii", $title, $salary, $dateOpen, $dateEnd, $education, $experience, $description, $interviewDate, $startTime, $endTime, $job_id, $user_id);

        if ($stmt->execute()) {
            echo "Job post updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
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

        .welcome-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .job-card, .edit-form {
            background-color: #fff;
            width: 400px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .job-card h2, .edit-form h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .job-card label, .edit-form label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
            color: #555;
        }

        .job-card input, .job-card textarea, .edit-form input, .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .job-card textarea, .edit-form textarea {
            height: 100px;
            resize: vertical;
        }

        .job-card button, .edit-form button {
            background-color: crimson;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .job-card button:hover, .edit-form button:hover {
            background-color: darkred;
        }

        .job-list h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .job-list .job-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .job-list .job-item h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .job-list .job-item p {
            margin: 5px 0;
            color: #555;
        }

        .job-list .job-item button {
            background-color: crimson;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .job-list .job-item button:hover {
            background-color: darkred;
        }

        .edit-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="navbar">
        
        <a href="create_job.php">HOME</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="welcome-message">
            Welcome to Rojgar Sewa! Now you can add different job vacancies from this portal.
        </div>

        <!-- Listed Jobs -->
        <div class="job-list">
            <h2>Listed Jobs</h2>
            <?php if ($jobs && $jobs->num_rows > 0): ?>
                <?php while($job = $jobs->fetch_assoc()): ?>
                    <div class="job-item">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p class="salary">Salary: <?php echo htmlspecialchars($job['salary']); ?></p>
                        <p class="dates">Open: <?php echo htmlspecialchars($job['dateOpen']); ?> | End: <?php echo htmlspecialchars($job['dateEnd']); ?></p>
                        <p class="education">Education: <?php echo htmlspecialchars($job['education']); ?></p>
                        <p class="experience">Experience: <?php echo htmlspecialchars($job['experience']); ?></p>
                        <p class="description">Description: <?php echo htmlspecialchars($job['description']); ?></p>
                        <p class="interview">Interview Date: <?php echo htmlspecialchars($job['interviewDate']); ?> | Time: <?php echo htmlspecialchars($job['startTime']); ?> - <?php echo htmlspecialchars($job['endTime']); ?></p>
                        <button onclick="editJob(<?php echo $job['id']; ?>, '<?php echo addslashes(htmlspecialchars($job['title'])); ?>', '<?php echo addslashes(htmlspecialchars($job['salary'])); ?>', '<?php echo $job['dateOpen']; ?>', '<?php echo $job['dateEnd']; ?>', '<?php echo addslashes(htmlspecialchars($job['education'])); ?>', '<?php echo addslashes(htmlspecialchars($job['experience'])); ?>', '<?php echo addslashes(htmlspecialchars($job['description'])); ?>', '<?php echo $job['interviewDate']; ?>', '<?php echo $job['startTime']; ?>', '<?php echo $job['endTime']; ?>')">Edit</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No job posts found.</p>
            <?php endif; ?>
        </div>

        <!-- Create/Edit Job Form -->
        <div class="job-card">
            <h2>Create Job Post</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="job_id" id="job_id">
                <label for="title">Job Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="salary">Salary:</label>
                <input type="text" id="salary" name="salary" required>

                <label for="dateOpen">Opening Date:</label>
                <input type="date" id="dateOpen" name="dateOpen" required>

                <label for="dateEnd">Closing Date:</label>
                <input type="date" id="dateEnd" name="dateEnd" required>

                <label for="education">Education Required:</label>
                <input type="text" id="education" name="education" required>

                <label for="experience">Experience Required:</label>
                <input type="text" id="experience" name="experience" required>

                <label for="description">Job Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="interviewDate">Interview Date:</label>
                <input type="date" id="interviewDate" name="interviewDate" required>

                <label for="startTime">Interview Start Time:</label>
                <input type="time" id="startTime" name="startTime" required>

                <label for="endTime">Interview End Time:</label>
                <input type="time" id="endTime" name="endTime" required>

                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="edit-form">
            <h2>Edit Job Post</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="job_id" id="edit_job_id">
                <label for="edit_title">Job Title:</label>
                <input type="text" id="edit_title" name="title" required>

                <label for="edit_salary">Salary:</label>
                <input type="text" id="edit_salary" name="salary" required>

                <label for="edit_dateOpen">Opening Date:</label>
                <input type="date" id="edit_dateOpen" name="dateOpen" required>

                <label for="edit_dateEnd">Closing Date:</label>
                <input type="date" id="edit_dateEnd" name="dateEnd" required>

                <label for="edit_education">Education Required:</label>
                <input type="text" id="edit_education" name="education" required>

                <label for="edit_experience">Experience Required:</label>
                <input type="text" id="edit_experience" name="experience" required>

                <label for="edit_description">Job Description:</label>
                <textarea id="edit_description" name="description" required></textarea>

                <label for="edit_interviewDate">Interview Date:</label>
                <input type="date" id="edit_interviewDate" name="interviewDate" required>

                <label for="edit_startTime">Interview Start Time:</label>
                <input type="time" id="edit_startTime" name="startTime" required>

                <label for="edit_endTime">Interview End Time:</label>
                <input type="time" id="edit_endTime" name="endTime" required>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function editJob(id, title, salary, dateOpen, dateEnd, education, experience, description, interviewDate, startTime, endTime) {
            document.getElementById('job_id').value = id;
            document.getElementById('title').value = title;
            document.getElementById('salary').value = salary;
            document.getElementById('dateOpen').value = dateOpen;
            document.getElementById('dateEnd').value = dateEnd;
            document.getElementById('education').value = education;
            document.getElementById('experience').value = experience;
            document.getElementById('description').value = description;
            document.getElementById('interviewDate').value = interviewDate;
            document.getElementById('startTime').value = startTime;
            document.getElementById('endTime').value = endTime;
        }
    </script>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            width: 50%;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-container .profile-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .profile-container .profile-info div {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>User Profile</h2>
    <div class="profile-info">
        <div><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></div>
        <div><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
        <div><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></div>
        <div><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></div>
    </div>
</div>

</body>
</html>

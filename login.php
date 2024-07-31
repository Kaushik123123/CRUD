<?php

session_start();
require 'db.php';  // Assuming db.php contains your database connection code

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from database
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: welcome.php');  // Redirect to welcome page
        exit;
    } else {
        $error_message = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-size: 25px;
            background-color: lightblue; /* Light blue */
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .error-message {
            color: #ff6347; /* Tomato */
            margin-bottom: 10px;
        }
        .try-again-btn {
            background-color: #4682b4; /* Steel blue */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .try-again-btn:hover {
            background-color: #36648b; /* Darker steel blue */
        }
    </style>
</head>
<body>
    <div class="login-box">
        <?php
        if (isset($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
        <a href="login.html" class="try-again-btn">Try again</a>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #1e3c72, #2a5298);
            color: white;
            text-align: center;
            padding-top: 50px;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.5em;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border: 2px solid white;
            border-radius: 5px;
            transition: background 0.3s, border 0.3s;
        }
        a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <h1>Welcome, user!</h1>
    <p>You have successfully logged in.</p>
    <a href="logout.php">Logout</a><br><br>
    <a href="home_page.html">Go to home page</a>
</body>
</html>

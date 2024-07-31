<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $sql = "SELECT * FROM password_resets WHERE token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset_request = $result->fetch_assoc();

    if ($reset_request && strtotime($reset_request['created_at']) > strtotime('-1 hour')) {
        // Show reset password form
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="css/login.css">
        </head>
        <body>
            <div class="container">
                <div class="form-container">
                    <h2>Reset Password</h2>
                    <form action="reset_password.php" method="POST">
                        <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                        <div class="input-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn">Reset Password</button>
                    </form>
                </div>
            </div>
        </body>
        </html>';
    } else {
        echo "Invalid or expired token.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate token
    $sql = "SELECT * FROM password_resets WHERE token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset_request = $result->fetch_assoc();

    if ($reset_request && strtotime($reset_request['created_at']) > strtotime('-1 hour')) {
        // Update password and delete reset token
        $sql = "UPDATE users SET password=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $reset_request['email']);
        if ($stmt->execute()) {
            // Delete the reset request
            $sql = "DELETE FROM password_resets WHERE token=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Your password has been reset successfully.";
        } else {
            echo "Failed to reset password.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>

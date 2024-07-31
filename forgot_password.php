<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generate a unique token
    $token = bin2hex(random_bytes(16));
    $created_at = date('Y-m-d H:i:s');

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Insert reset token into password_resets table
        $sql = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $token, $created_at);
        $stmt->execute();

        // Send reset email
        $reset_link = "http://.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: $reset_link";
        $headers = "From: kaushikrawal4365@gmail.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>

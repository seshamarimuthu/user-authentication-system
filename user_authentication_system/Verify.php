<?php
session_start();
require_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    if ($stmt->execute()) {
        echo "Email verified successfully!";
        header("Location: login.php");

    } else {
        echo "Verification failed. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid token.";
}
?>

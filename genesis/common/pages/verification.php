<?php
session_start();
include('../../partials/connect.php');

if (!isset($_GET['token'])) {
    die("Invalid verification link.");
}

$token = $_GET['token'];

// Fetch user by token
$stmt = $connect->prepare("SELECT Id, IsVerified FROM users WHERE VerificationToken = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("This verification link is invalid or expired.");
}

$user = $result->fetch_assoc();

if ($user['IsVerified']) {
    // Already verified, redirect to parent confirmation page
    header("Location: parent_verification_success.php?learner_id=" . $user['Id']);
    exit();
}

// Update verification status
$update = $connect->prepare("UPDATE users SET IsVerified = 1, VerificationToken = NULL WHERE Id = ?");
$update->bind_param("i", $user['Id']);
$update->execute();
$update->close();
$stmt->close();

// Redirect to parent confirmation page
header("Location: parent_verification_success.php?learner_id=" . $user['Id']);
exit();

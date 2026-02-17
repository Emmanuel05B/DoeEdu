<?php
include_once(__DIR__ . "/../../partials/paths.php"); 
include_once(BASE_PATH . "/partials/session_init.php"); 
include_once(BASE_PATH . "/partials/connect.php"); 

if (!isset($_GET['token'])) {
    echo "Invalid confirmation link.";
    exit();
}

$token = $_GET['token'];

// Find the invitation
$sql = "SELECT Id, Confirmed FROM marketerinvitations WHERE ConfirmationToken = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Invalid or expired confirmation link.";
    exit();
}

$invitation = $result->fetch_assoc();

if ($invitation['Confirmed'] == 1) {
    echo "You have already confirmed this interview.";
    exit();
}

// Update confirmation
$sqlUpdate = "UPDATE marketerinvitations SET Confirmed = 1 WHERE Id = ?";
$stmtUpdate = $connect->prepare($sqlUpdate);
$stmtUpdate->bind_param('i', $invitation['Id']);
$stmtUpdate->execute();

echo "<p style='font-family:Arial, sans-serif; font-size:16px;'>Thank you! Your attendance for the interview has been confirmed.</p>";

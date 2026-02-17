<?php
// delete_marketer_application.php
require_once __DIR__ . '/../../common/config.php';
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");
include_once(BASE_PATH . "/partials/connect.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

// Check if ID is provided
$appId = $_GET['id'] ?? null;

if (!$appId || !is_numeric($appId)) {
    $_SESSION['error'] = "Invalid application ID.";
    header("Location: marketerapplications.php");
    exit();
}

// First, fetch the CV file so we can delete it
$stmt = $connect->prepare("SELECT CV_Matric FROM marketerapplications WHERE Id = ?");
$stmt->bind_param("i", $appId);
$stmt->execute();
$stmt->bind_result($cvFile);
$stmt->fetch();
$stmt->close();

if ($cvFile) {
    $filePath = BASE_PATH . '/uploads/cvs/' . $cvFile;
    if (file_exists($filePath)) {
        unlink($filePath); // delete the uploaded CV file
    }
}

// Delete the application from the database
$stmt = $connect->prepare("DELETE FROM marketerapplications WHERE Id = ?");
$stmt->bind_param("i", $appId);

if ($stmt->execute()) {
    $_SESSION['success'] = "Application deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete application.";
}

$stmt->close();
header("Location: marketerapplications.php");
exit();

<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and get inputs
    $grade = trim($_POST['grade'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $group = trim($_POST['group'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $createdBy = $_SESSION['user_id'] ?? 0; 

    // Simple validation
    if (empty($grade) || empty($subject) || empty($group) || empty($title) || empty($content)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: tutorindex.php");
        exit();
    }

    $sql = "INSERT INTO classnotifications (Grade, Subject, Group_Class, Title, Content, CreatedBy, CreatedAt) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssssi", $grade, $subject, $group, $title, $content, $createdBy);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Notification sent to class successfully!";
        header("Location: tutorindex.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to send notification. Please try again.";
        header("Location: tutorindex.php");
        exit();
    }
    
} else {
    // Invalid access
    $_SESSION['error'] = "Invalid request.";
    header("Location: tutorindex.php");
    exit();
}
?>

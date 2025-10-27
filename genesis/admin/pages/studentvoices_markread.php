<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $connect->prepare("UPDATE studentvoices SET IsRead = 1 WHERE Id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Marked as read.";
    } else {
        $_SESSION['error'] = "Failed to mark as read.";
    }
    $stmt->close();
}

header("Location: voices.php");
exit();
?>

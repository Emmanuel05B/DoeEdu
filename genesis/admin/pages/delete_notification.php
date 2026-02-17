<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

header('Content-Type: application/json');
include_once(BASE_PATH . "/partials/connect.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NotificationId'])) {
    $notificationId = intval($_POST['NotificationId']);

    $stmt = $connect->prepare("DELETE FROM notifications WHERE NotificationId = ?");
    $stmt->bind_param("i", $notificationId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Notification deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting notification.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
exit();

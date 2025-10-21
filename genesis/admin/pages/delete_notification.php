<?php

session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');
include(__DIR__ . "/../../partials/connect.php");

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

<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
    exit;
}

$tutorId = $_SESSION['user_id'];
$notifId = intval($_POST['id']);

// Delete only if the notice belongs to this tutor
$sql = "DELETE FROM classnotifications WHERE NotificationID = ? AND CreatedBy = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ii", $notifId, $tutorId);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(['status' => 'success', 'msg' => 'Notice deleted']);
} else {
    echo json_encode(['status' => 'error', 'msg' => 'Failed to delete']);
}
?>

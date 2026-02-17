<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


if (!isset($_SESSION['user_id'])) exit;

$tutorId = $_SESSION['user_id'];
$grade = $_POST['grade'] ?? '';
$subject = $_POST['subject'] ?? '';
$group = $_POST['group'] ?? '';

if (!$grade || !$subject || !$group) {
    echo "<p class='text-danger'>Invalid parameters.</p>";
    exit;
}

$sql = "SELECT NotificationID, Title, Content, CreatedAt 
        FROM classnotifications 
        WHERE CreatedBy = ? 
          AND Grade = ? 
          AND Group_Class = ? 
          AND Subject = ? 
          AND CreatedAt >= NOW() - INTERVAL 14 DAY
        ORDER BY CreatedAt DESC";

$stmt = $connect->prepare($sql);
$stmt->bind_param("isss", $tutorId, $grade, $group, $subject);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($notif = $result->fetch_assoc()) {
       
        echo '<div class="panel panel-default" id="notif-'.$notif['NotificationID'].'">';
        echo '<div class="panel-heading d-flex justify-content-between align-items-center">';
        echo '<strong>Date:</strong> ' . htmlspecialchars($notif['CreatedAt']);
        // Delete icon only, aligned right and red
        echo '<button class="btn btn-xs text-danger delete-notice p-0" style="float:right; border:none; background:none;" data-id="'.$notif['NotificationID'].'">';
        echo '<i class="fa fa-trash"></i>';
        echo '</button>';
        echo '</div>';
        echo '<div class="panel-body">';
        echo '<strong>' . htmlspecialchars($notif['Title']) . ':</strong> ' . htmlspecialchars($notif['Content']);
        echo '</div>';
        echo '</div>';

    }
} else {
    echo '<p class="text-muted text-center">No notices sent to this class in the past 2 weeks.</p>';
}
$stmt->close();
?>

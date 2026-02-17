<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


// Validate activityId
if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}

$activityId = intval($_GET['activityId']);

// Delete related questions
$qstmt = $connect->prepare("DELETE FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qstmt->close();

// Delete activity assignments
$astmt = $connect->prepare("DELETE FROM onlineactivitiesassignments WHERE OnlineActivityId = ?");
$astmt->bind_param("i", $activityId);
$astmt->execute();
$astmt->close();

// Delete the activity itself
$dstmt = $connect->prepare("DELETE FROM onlineactivities WHERE Id = ?");
$dstmt->bind_param("i", $activityId);
$dstmt->execute();
$dstmt->close();

// Redirect back to the activities list with a success flag
header("Location: myactivities.php?deleted=1");
exit();
?>

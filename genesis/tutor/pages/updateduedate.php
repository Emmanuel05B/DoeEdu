<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get and sanitize inputs
    $activityId = intval($_POST['activityId']);
    $newDueDate = $_POST['newDueDate'];
    $grade      = $_POST['gra'];
    $subjectId  = intval($_POST['sub']);
    $group      = $_POST['group'];

    if (!$activityId || !$newDueDate || !$grade || !$subjectId || !$group) {
        die("Invalid input.");
    }

    // Get ClassID for this grade, subject, group
    $stmt = $connect->prepare("SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1");
    $stmt->bind_param("sis", $grade, $subjectId, $group);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $classId = intval($row['ClassID']);
    } else {
        die("Class not found.");
    }
    $stmt->close();

    // Update DueDate in onlineactivitiesassignments table
    $stmt = $connect->prepare("UPDATE onlineactivitiesassignments SET DueDate = ? WHERE OnlineActivityId = ? AND ClassID = ?");
    $stmt->bind_param("sii", $newDueDate, $activityId, $classId);
    
    if ($stmt->execute()) {
        // Redirect back with success
        header("Location: assignedquizzes.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&updated=1");
        exit();
    } else {
        die("Failed to update due date: " . $stmt->error);
    }

    $stmt->close();
    $connect->close();
} else {
    die("Invalid request.");
}

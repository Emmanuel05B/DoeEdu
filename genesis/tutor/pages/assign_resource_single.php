<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Get and validate input via POST
$resourceId = isset($_POST['resourceId']) ? intval($_POST['resourceId']) : 0;
$classId    = isset($_POST['classId']) ? intval($_POST['classId']) : 0;
$grade      = isset($_POST['grade']) ? $_POST['grade'] : '';
$subjectId  = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : 0;
$group      = isset($_POST['group']) ? $_POST['group'] : '';
$assignedBy = $_SESSION['user_id'];

// Basic validation
if ($resourceId <= 0 || $classId <= 0 || !$grade || !$subjectId || !$group) {
    die("Invalid input. Please try again.");
}


// Check if assignment already exists
$stmt = $connect->prepare("SELECT 1 FROM resourceassignments WHERE ResourceID = ? AND ClassID = ?");
$stmt->bind_param("ii", $resourceId, $classId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&message=already_assigned");
    exit();
}
$stmt->close();

// Insert new assignment
$stmt = $connect->prepare("INSERT INTO resourceassignments (ResourceID, ClassID, AssignedBy) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $resourceId, $classId, $assignedBy);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&message=assigned_success");
    exit();
} else {
    $stmt->close();
    die("Database error: Could not assign resource.");
}

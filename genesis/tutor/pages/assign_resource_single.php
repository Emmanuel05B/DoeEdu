<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Get and validate input
$resourceId = isset($_GET['resourceId']) ? intval($_GET['resourceId']) : 0;
$classId = isset($_GET['classId']) ? intval($_GET['classId']) : 0;
$assignedBy = $_SESSION['user_id'];

if ($resourceId <= 0 || $classId <= 0) {
    die("Invalid resource or class ID.");
}

// Check if assignment already exists
$stmt = $connect->prepare("SELECT 1 FROM resourceassignments WHERE ResourceID = ? AND ClassID = ?");
$stmt->bind_param("ii", $resourceId, $classId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: studyresources.php?message=already_assigned");
    exit();
}
$stmt->close();

// Insert new assignment
$stmt = $connect->prepare("INSERT INTO resourceassignments (ResourceID, ClassID, AssignedBy) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $resourceId, $classId, $assignedBy);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: studyresources.php?message=assigned_success");
    exit();
} else {
    $stmt->close();
    die("Database error: Could not assign resource.");
}

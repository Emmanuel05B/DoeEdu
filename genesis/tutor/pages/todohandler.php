<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../partials/connect.php");
// Collect the data
$taskName = trim($_POST['task_name'] ?? '');

// Due Date: user input or today
$dueDate = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');

// Due Time: user input or now
$dueTime = !empty($_POST['due_time']) ? $_POST['due_time'] : date('H:i');

// Combine into MySQL DATETIME format
$dueDateTime = $dueDate . ' ' . $dueTime; // e.g., "2025-08-30 20:46"

// Priority: default Medium if not provided
$priority = trim($_POST['priority'] ?? 'Medium');

// Category: default General
$category = "General";

$creatorId = $_SESSION['user_id']; 


// Basic validation
if (empty($taskName)) {
    header("Location: tutorindex.php?status=error&message=" . urlencode("Task Name is required."));
    exit();
}

// Insert into database
$sql = "INSERT INTO TodoList (CreatorId, TaskText, DueDate, Priority, Category, CreationDate) 
        VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = $connect->prepare($sql);
$stmt->bind_param("sssss", $creatorId, $taskName, $dueDateTime, $priority, $category);

if ($stmt->execute()) {
    header("Location: tutorindex.php?status=success&message=" . urlencode("Task added successfully!"));
    exit();
} else {
    header("Location: tutorindex.php?status=error&message=" . urlencode("Error adding task: " . $stmt->error));
    exit();
}
?>

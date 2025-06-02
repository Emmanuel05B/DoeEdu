<?php
session_start();
include('../partials/connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

// Collect the data from the form
$taskName = $_POST['task_name'];
$dueDate = $_POST['due_date']; // Assuming format is 'Y-m-d'
$dueTime = $_POST['due_time']; // Assuming format is 'H:i'
$priority = $_POST['priority'];
$creatorId = $_SESSION['user_id']; // Assuming user ID is stored in session

// Set the category to a default value (or get it from the form if needed)
$category = "General"; // You can adjust this based on user input or remove if not needed

// Combine date and time into a single datetime format
$dueDateTime = $dueDate . ' ' . $dueTime;

// Validate data (simple example)
if (empty($taskName) || empty($dueDate) || empty($dueTime) || empty($priority)) {
    die("All fields are required.");
}

// Insert the data into the database
$sql = "INSERT INTO TodoList (CreatorId, TaskText, DueDate, Priority, Category, CreationDate) 
        VALUES (?, ?, ?, ?, ?, NOW())";

// Prepare the SQL statement
$stmt = $connect->prepare($sql);

// Bind parameters to the SQL query
$stmt->bind_param("sssss", $creatorId, $taskName, $dueDateTime, $priority, $category);

// Execute the statement and check for success
if ($stmt->execute()) {
    // Redirect back with success status
    header("Location: adminindex.php?status=success");
} else {
    // Handle errors
    die("Error inserting task: " . $stmt->error);
}
?>

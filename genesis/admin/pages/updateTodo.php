<?php
session_start();
include('../../partials/connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/login.php");
    exit();
}

// Get the TodoId from the URL
if (isset($_GET['todo_id'])) {
    $todoId = $_GET['todo_id'];

    // Validate the TodoId
    if (is_numeric($todoId)) {
        // Fetch the existing task details from the database
        $sql = "SELECT * FROM TodoList WHERE TodoId = ? AND CreatorId = ?";
        $stmt = $connect->prepare($sql);
        $creatorId = $_SESSION['user_id'];
        $stmt->bind_param("ii", $todoId, $creatorId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the task exists
        if ($result->num_rows > 0) {
            $task = $result->fetch_assoc();
        } else {
            die("Task not found or you do not have permission to edit it.");
        }
    } else {
        die("Invalid TodoId");
    }
} else {
    die("TodoId is required");
}

// Handle form submission for updating the task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $dueDate = $_POST['due_date'];
    $dueTime = $_POST['due_time'];
    $priority = $_POST['priority'];
    $category = $_POST['category'];

    // Combine date and time into a single datetime value
    $dueDateTime = $dueDate . ' ' . $dueTime;

    // Update the task in the database
    $updateSql = "UPDATE TodoList 
                  SET TaskText = ?, DueDate = ?, Priority = ?, Category = ? 
                  WHERE TodoId = ? AND CreatorId = ?";

    $stmtUpdate = $connect->prepare($updateSql);
    $stmtUpdate->bind_param("ssssii", $taskName, $dueDateTime, $priority, $category, $todoId, $creatorId);

    if ($stmtUpdate->execute()) {
        // Redirect to the admin page with success message
        header("Location: adminindex.php?status=updated");
    } else {
        // Handle error
        die("Error updating task: " . $stmtUpdate->error);
    }
}
?>

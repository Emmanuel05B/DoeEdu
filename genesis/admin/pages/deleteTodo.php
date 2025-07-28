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
        // Prepare the SQL statement to delete the task
        $sql = "DELETE FROM TodoList WHERE TodoId = ? AND CreatorId = ?";
        
        // Prepare the statement
        $stmt = $connect->prepare($sql);
        
        // Get the CreatorId from session
        $creatorId = $_SESSION['user_id'];

        // Bind parameters
        $stmt->bind_param("ii", $todoId, $creatorId);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the admin page with success message
            header("Location: adminindex.php?status=deleted");
        } else {
            // Handle error
            die("Error deleting task: " . $stmt->error);
        }
    } else {
        die("Invalid TodoId");
    }
} else {
    die("TodoId is required");
}
?>

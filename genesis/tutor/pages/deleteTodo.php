<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  


// Get the TodoId from the URL
if (isset($_GET['todo_id'])) {
    $todoId = $_GET['todo_id'];

    // Validate the TodoId
    if (is_numeric($todoId)) {
        // Prepare the SQL statement to delete the task
        $sql = "DELETE FROM todolist WHERE TodoId = ? AND CreatorId = ?";
        
        // Prepare the statement
        $stmt = $connect->prepare($sql);
        
        // Get the CreatorId from session
        $creatorId = $_SESSION['user_id'];

        // Bind parameters
        $stmt->bind_param("ii", $todoId, $creatorId);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the admin page with success message
            header("Location: tutorindex.php?status=deleted");
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

<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if (isset($_POST["submit"])) {

    $TeacherId = $_SESSION['user_id'];  // Logged-in teacher

    // Fetch teacher surname safely
    $stmt = $connect->prepare("SELECT Surname FROM users WHERE Id = ?");
    $stmt->bind_param("i", $TeacherId); 
    $stmt->execute();
    $stmt->bind_result($Surname);
    $stmt->fetch();
    $stmt->close();

    // Get form data from POST
    $graid = $_POST['graid'];
    $subject = $_POST['subid'];
    $group = $_POST['group'];
    $activityname = trim($_POST['activityname']);    
    $chaptername = trim($_POST['chaptername']);
    $activitytotal = (int) $_POST['activitytotal']; 

    // Basic validation
    if (empty($activityname) || empty($chaptername) || $activitytotal <= 0) {
        die("All fields are required and total must be greater than 0.");
    }

    // Check that the subject exists to prevent foreign key errors
    $checkSub = $connect->prepare("SELECT SubjectId FROM subjects WHERE SubjectId = ?");
    $checkSub->bind_param("i", $subject);
    $checkSub->execute();
    $checkSub->store_result();
    if ($checkSub->num_rows === 0) {
        die("Invalid Subject ID: $subject. Cannot create activity.");
    }
    $checkSub->close();

    // Prepare SQL insert statement
    $insertStmt = $connect->prepare("
        INSERT INTO activities
        (ActivityName, SubjectId, ActivityDate, MaxMarks, Creator, Grade, ChapterName, GroupName)
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)
    ");

    $insertStmt->bind_param(
        "siisiss",
        $activityname,
        $subject,
        $activitytotal,
        $Surname,
        $graid,
        $chaptername,
        $group
    );

    if ($insertStmt->execute()) {
        // Get the ID of the newly created activity
        $activityid = $insertStmt->insert_id;

        // Redirect to class.php with the new activity ID
        header("Location: class.php?aid=$activityid");
        $insertStmt->close();
        $connect->close();
        exit();
    } else {
        echo "Error: " . $insertStmt->error;
    }

    $insertStmt->close();
    $connect->close();
    exit();
}
?>

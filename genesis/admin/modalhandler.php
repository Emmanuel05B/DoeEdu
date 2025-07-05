<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php'); 


if (isset($_POST["submit"])) {

$TeacherId = $_SESSION['user_id'];  // For user

// Prepare the SQL statement to prevent SQL injection
$usql = "SELECT Surname FROM users WHERE Id = ?";
$stmt = $connect->prepare($usql);
$stmt->bind_param("i", $TeacherId); 
$stmt->execute();
$stmt->bind_result($surname);
$stmt->fetch();
// Close the statement
$Surname = $surname;
$stmt->close();


// get form data  passed via hidden inputs
$graid = $_POST['graid'];
$subject = $_POST['subid'];
$chaptername = $_POST['chaid'];

$activityname = $_POST['activityname'];    
$activitytotal = $_POST['activitytotal']; 

// Prepare the SQL insert statement   //create a table for activities

$insertStmt = $connect->prepare("INSERT INTO activities
    (ActivityName, SubjectId, ActivityDate, MaxMarks, Creator, Grade, ChapterName) 
    VALUES (?, ?,NOW(), ?, ?, ?, ?)");

// Bind the parameters..........come back for subject
    $insertStmt->bind_param("siisis",  $activityname, $subject, $activitytotal,$Surname, $graid, $chaptername);

// Execute the prepared statement
if ($insertStmt->execute()) {

    //after creating this new/recent activity.. I want its Id so that i can go with it to class.php.    this will be the activity id where ...the id is the biggest
    //$sql = "SELECT * FROM activities ORDER BY ActivityDate DESC, ActivityId DESC LIMIT 1";

    $sql = "SELECT * FROM activities ORDER BY ActivityId DESC LIMIT 1";
    $results = $connect->query($sql);

    // Check if there is any result
    if ($results->num_rows > 0) {
    $finalres = $results->fetch_assoc();
    $activityid = $finalres['ActivityId'];

    header("Location: class.php?aid=$activityid");

    } else {
    echo "No records found.";
    }
     
 

    exit(); 
} else {
    echo "Error: " . $insertStmt->error;
}

// Close the database connection
$connect->close();
exit();
}
?>
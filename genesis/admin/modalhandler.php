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
$chapter = $_POST['chaid'];

/*      skipped coz of line 57
if ($subid == 1) {
    $subject = "Mathematics";
} elseif ($subid == 2) {
    $subject = "Physical Science";
} else {
    $subject = "Unknown Subject";  /// Optional: for other subid values
}
*/

$activityname = $_POST['activityname'];    
$activitytotal = $_POST['activitytotal']; 

// Prepare the SQL insert statement   //create a table for activities

$insertStmt = $connect->prepare("INSERT INTO activities
    (ActivityName, SubjectId, ActivityDate, MaxMarks, Creator, Grade, ChapterId) 
    VALUES (?, ?,NOW(), ?, ?, ?, ?)");

// Bind the parameters..........come back for subject
    $insertStmt->bind_param("siisii",  $activityname, $subject, $activitytotal,$Surname, $graid, $chapter);

// Execute the prepared statement
if ($insertStmt->execute()) {

    //after creating this new/recent activity.. I want its Id so that i can go with it to class.php.    this will be the activity id where ...the id is the biggest
    
    $sql = "SELECT * FROM activities WHERE ActivityId = Max";
    $results = $connect->query($sql);
    $finalres = $results->fetch_assoc(); 
     
    $chapters = $finalres['ActivityId'];

    header("Location: class.php?gid=$graid&sid=$subject&cid=$chapters");

    exit(); 
} else {
    echo "Error: " . $insertStmt->error;
}

// Close the database connection
$connect->close();
exit();
}
?>
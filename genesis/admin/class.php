
<?php
include('../partials/connect.php'); 


if (isset($_POST["submit"])) {

// get form data
$learnerid = $_POST['learnerid'];
$parentid = $_POST['parentid'];

$activityname = $_POST['activityname'];    
$activitytotal = $_POST['activitytotal']; 

// Prepare the SQL insert statement   //create a table for activities

$insertStmt = $connect->prepare("INSERT INTO details
    (PAP,PartakesSpontaneously, LearnerId, CreatedAt) 
    VALUES (?, ?, ?, NOW())");

// Bind the parameters
    $insertStmt->bind_param("sii",   
    $activityname, 
    $activitytotal, 
    $learnerid
);


// Execute the prepared statement
if ($insertStmt->execute()) {
    header("Location: index.php?lid=$learnerid&pid=$parentid");

    exit(); 
} else {
    echo "Error: " . $insertStmt->error;
}

// Close the database connection
$connect->close();
exit();
}
?>
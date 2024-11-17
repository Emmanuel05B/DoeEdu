

<?php
include('../partials/connect.php');

$id = $_GET['id'];

$sql="DELETE FROM learner WHERE LearnerId = $id";

if(mysqli_query($connect, $sql))
{
    echo "<script> alert('Succesfully Deleted The Learner');
            window.location.href='deletelearner.php';
            </script>";
    
}else{
    echo "<script> alert('Unsuccesfully Deleted The Learner');
            window.location.href='deletelearner.php';
            </script>";
}


?>


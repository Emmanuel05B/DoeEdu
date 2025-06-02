
<!DOCTYPE html>
<html>

<?php include("adminpartials/head.php"); //affects the alert styling ?>  
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php'); 

if (isset($_POST["updateby"])) {

    //$teacherid = $_SESSION['user_id'];  //for logged-in teacher
    // get form data
    $newamount = $_POST['newamount'];  //get the amound paid to reduce the debt

    $learnerid = $_POST['learnerid'];   //VIA HIDDEN

    
/* The  idea is that everytime a leaner makes a payment..what ever amound he is paying should add up 
with the existing amount that the learner has paid previously.

totalpaid = new-amount + totalpaid... then update the totalpaid

totalowe = totalfees - totalpaid

*/
               
    $sql = "SELECT * FROM learners WHERE LearnerId = $learnerid";
    $results = $connect->query($sql);
    $final = $results->fetch_assoc();

    $totalfees = $final['TotalFees'];   //get total fees
    $totalpaid = $final['TotalPaid'];   //get total paid before the new payment

    $totalpaid = $newamount + $totalpaid;    //add the new pay to the existing totalpaid

    $totalowe = $totalfees - $totalpaid;      //now i have total owe.
    // Prepare the SQL statements       
    $sql = "UPDATE learners SET TotalPaid = ?, TotalOwe = ? WHERE LearnerId = ?";
    $UpdateStmt = $connect->prepare($sql);
    $UpdateStmt->bind_param("ddi", $totalpaid, $totalowe, $learnerid);
    
    // Execute the query
    if ($UpdateStmt->execute()) {
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Successfully Updated Goal",
            text: "Data has been saved for the Learner.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "finances.php?id=' . $learnerid . '";
            }
        });
        </script>';
    } else {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Submission Error",
            text: "Failed to Update Goal.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "finances.php?id=' . $learnerid . '";
            }
        });
        </script>';
    }

    // Close the database connection
    $UpdateStmt->close();
    $connect->close();
    exit();
}
?>

</body>
</html>

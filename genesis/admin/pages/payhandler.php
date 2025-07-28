<!DOCTYPE html>
<html>

<?php include("../adminpartials/head.php"); // affects the alert styling ?>  
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/login.php");
    exit();
}

include('../../partials/connect.php'); 

if (isset($_POST["updateby"])) {

    // Get form data
    $newamount = $_POST['newamount'];  // amount paid to reduce the debt
    $learnerid = $_POST['learnerid']; // hidden input

    // Get current payment details
    $sql = "SELECT * FROM learners WHERE LearnerId = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $learnerid);
    $stmt->execute();
    $result = $stmt->get_result();
    $final = $result->fetch_assoc();
    $stmt->close();

    if (!$final) {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Learner Not Found",
            text: "The learner ID does not exist.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "finances.php";
        });
        </script>';
        exit();
    }

    $totalfees = $final['TotalFees'];   // get total fees
    $totalpaid = $final['TotalPaid'];   // get total paid before the new payment

    // Calculate new totals
    $totalpaid += $newamount;    
    $totalowe = $totalfees - $totalpaid;      

    // Update with LastUpdated = NOW()
    $sql = "UPDATE learners SET TotalPaid = ?, TotalOwe = ?, LastUpdated = NOW() WHERE LearnerId = ?";
    $UpdateStmt = $connect->prepare($sql);
    $UpdateStmt->bind_param("ddi", $totalpaid, $totalowe, $learnerid);

    // Execute update
    if ($UpdateStmt->execute()) {
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Payment Updated Successfully",
            text: "The learner\'s payment information has been saved.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "finances.php?id=' . $learnerid . '";
        });
        </script>';
    } else {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Update Failed",
            text: "Unable to update the payment record. Please try again.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "finances.php?id=' . $learnerid . '";
        });
        </script>';
    }

    $UpdateStmt->close();
    $connect->close();
    exit();
}
?>

</body>
</html>

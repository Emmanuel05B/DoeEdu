
<?php

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if (isset($_POST["updateby"])) {

    // Get form data
    $newamount = $_POST['newamount'];  // amount paid to reduce the debt
    $learnerid = $_POST['learnerid']; // hidden input d

    // Get current payment details
    $sql = "SELECT * FROM learners WHERE LearnerId = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $learnerid);
    $stmt->execute();
    $result = $stmt->get_result();
    $final = $result->fetch_assoc();
    $stmt->close();

    if (!$final) {
        header("Location: finances.php?id=" . urlencode($learnerid) . "&notfound=1");
        exit;
        
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
        header("Location: finances.php?id=" . urlencode($learnerid) . "&paid=1");
        exit;
        
    } else {
        header("Location: finances.php?id=" . urlencode($learnerid) . "&notpaid=1");
        exit;
        
    }

    $UpdateStmt->close();
    $connect->close();
    exit();
}
?>


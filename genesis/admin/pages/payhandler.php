<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if (isset($_POST["updateby"])) {

    // Get form data
    $newamount = floatval($_POST['newamount']);  // amount to pay
    $learnerid = intval($_POST['learnerid']);    // hidden input

    // Fetch current finance record
    $sql = "SELECT * FROM finances WHERE LearnerId = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $learnerid);
    $stmt->execute();
    $result = $stmt->get_result();
    $finance = $result->fetch_assoc();
    $stmt->close();

    if (!$finance) {
        header("Location: finances.php?id=" . urlencode($learnerid) . "&notfound=1");
        exit();
    }

    // Calculate new totals
    $totalPaid = $finance['TotalPaid'] + $newamount;
    $balance   = $finance['TotalFees'] - $totalPaid;

    // Update finances table
    $updateSql = "
        UPDATE finances 
        SET TotalPaid = ?, 
            Balance = ?, 
            LastPaymentDate = NOW(), 
            UpdatedAt = NOW() 
        WHERE LearnerId = ?
    ";
    $updateStmt = $connect->prepare($updateSql);
    $updateStmt->bind_param("ddi", $totalPaid, $balance, $learnerid);

    if ($updateStmt->execute()) {
        header("Location: finances.php?id=" . urlencode($learnerid) . "&paid=1");
        exit();
    } else {
        header("Location: finances.php?id=" . urlencode($learnerid) . "&notpaid=1");
        exit();
    }

    $updateStmt->close();
    $connect->close();
}
?>

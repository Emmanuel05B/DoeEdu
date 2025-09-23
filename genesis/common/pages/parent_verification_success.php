<?php
session_start();
include('../../partials/connect.php');

if (!isset($_GET['learner_id'])) {
    die("Invalid request.");
}

$learnerId = (int)$_GET['learner_id'];

// Fetch learner info
$stmt = $connect->prepare("SELECT Name, Surname FROM users WHERE Id = ?");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Learner not found.");
}

$learner = $result->fetch_assoc();
$learnerName = $learner['Name'] . " " . $learner['Surname'];
$stmt->close();

// Fetch learner subjects and fees
$stmt = $connect->prepare("
    SELECT s.SubjectName, ls.NumberOfTerms, ls.ContractFee
    FROM learnersubject ls
    JOIN subjects s ON ls.SubjectId = s.SubjectId
    WHERE ls.LearnerId = ?
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$result = $stmt->get_result();

$subjectRows = "";
$totalFees = 0;
while ($row = $result->fetch_assoc()) {
    $subjectRows .= "<tr>
                        <td>{$row['SubjectName']}</td>
                        <td>{$row['NumberOfTerms']} months</td>
                        <td>R {$row['ContractFee']}</td>
                     </tr>";
    $totalFees += (float)$row['ContractFee'];
}
$stmt->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Parent Verification Success</title>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background-color: #e8eff1;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        padding: 30px;
    }

    img {
        display: block;
        margin: 0 0 20px 0;
        max-width: 150px;
        height: auto;
    }

    h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .message {
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 16px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      margin-bottom: 15px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #5e88afff;
      color: white;
    }

    .total {
        font-weight: bold;
    }

    .print-btn {
        width: 100%;
        padding: 10px 0;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }

    .print-btn:hover {
        background-color: #0056b3;
    }

    #acknowledge {
        margin-top: 20px;
        font-size: 14px;
        text-align: center;
    }

    #payment-info {
        background-color: #f4f4f4;
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
        font-size: 14px;
    }

    #payment-info h4 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #333;
    }

    #payment-info p {
        margin: 5px 0;
    }

</style>


<?php 
 include(__DIR__ . "/../partials/queries.php");   //included in queries is src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
 ?>

</head>
<body>

<div class="container">
    <!-- Logo -->
    <img src="../../admin/images/westtt.png" alt="DoE Logo">

    <h3>Parent Verification Success</h3>

    <p class="message">Thank you! You have successfully verified and approved your child's registration.</p>

    <p><strong>Child:</strong> <?= htmlspecialchars($learnerName) ?></p>

    <h4>Registered Subjects & Fees</h4>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Duration</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
            <?= $subjectRows ?>
            <tr>
                <td colspan="2" class="total">Total Fees</td>
                <td class="total">R <?= number_format($totalFees, 2) ?></td>
            </tr>
        </tbody>
    </table>
    <div id="payment-info">
        <h4>Payment Options</h4>
        <p>✅ Monthly instalments from R150/month.</p>
        <p>✅ Pay once-off or split into monthly payments.</p>
        <p>✅ Custom plans available upon request.</p>
        <p>🏦 Account Number: FNB - <strong>1234567890</strong></p>
        <h4>Contact</h4>
        <p>📧 thedistributorsofedu@gmail.com</p>
        <p>📞 +27 8XXXXXXXXX</p>
        
    </div>


    <div id="acknowledge">
        By verifying, you acknowledge awareness of the fees above and approve your child's registration.
    </div>

    <button class="print-btn" onclick="window.print()">Print / Save</button>
</div>






<script>
// Optional SweetAlert popup on page load
Swal.fire({
    icon: 'success',
    title: 'Verification Successful!',
    html: 'You have approved <strong><?= htmlspecialchars($learnerName) ?></strong>\'s registration.<br>Total fees: R <?= number_format($totalFees, 2) ?>',
    confirmButtonText: 'OK',
});



</script>

</body>
</html>

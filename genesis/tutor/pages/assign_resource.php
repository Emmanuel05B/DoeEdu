<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$resourceIds = $_POST['resourceIds'] ?? [];
$classId = intval($_POST['classId'] ?? 0);
$assignedBy = $_SESSION['user_id'];

// Validate input
if (empty($resourceIds) || $classId <= 0) {
    $_SESSION['bulk_assign_status'] = 'error';
    $_SESSION['bulk_assign_message'] = 'Please select at least one resource and a valid class.';
    header("Location: studyresources.php");
    exit();
}

$successCount = 0;
$alreadyAssignedCount = 0;
$errors = [];

foreach ($resourceIds as $resId) {
    $resId = intval($resId);
    if ($resId <= 0) continue;

    // Check if already assigned
    $checkStmt = $connect->prepare("SELECT 1 FROM resourceassignments WHERE ResourceID = ? AND ClassID = ?");
    $checkStmt->bind_param("ii", $resId, $classId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $alreadyAssignedCount++;
        $checkStmt->close();
        continue;
    }
    $checkStmt->close();

    // Insert assignment
    $insertStmt = $connect->prepare("INSERT INTO resourceassignments (ResourceID, ClassID, AssignedBy) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iii", $resId, $classId, $assignedBy);
    
    if ($insertStmt->execute()) {
        $successCount++;
    } else {
        $errors[] = $resId;
    }
    $insertStmt->close();
}

// Prepare feedback
if ($successCount > 0) {
    $_SESSION['bulk_assign_status'] = 'success';
    $_SESSION['bulk_assign_message'] = "$successCount resource(s) assigned successfully.";
} elseif ($alreadyAssignedCount > 0 && $successCount === 0) {
    $_SESSION['bulk_assign_status'] = 'info';
    $_SESSION['bulk_assign_message'] = "All selected resources were already assigned to this class.";
} else {
    $_SESSION['bulk_assign_status'] = 'error';
    $_SESSION['bulk_assign_message'] = "Failed to assign resources. Try again.";
}

header("Location: studyresources.php");
exit();

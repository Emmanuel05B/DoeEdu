<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

$resourceId = intval($_GET['resourceId'] ?? 0);
$classId    = intval($_GET['classId'] ?? 0);
$grade      = $_GET['gra'] ?? '';
$subjectId  = intval($_GET['sub'] ?? 0);
$group      = $_GET['group'] ?? '';

if ($resourceId <= 0 || $classId <= 0) {
    header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&delete_failed=1");
    exit();
}

$stmt = $connect->prepare("DELETE FROM resourceassignments WHERE ResourceID = ? AND ClassID = ?");
$stmt->bind_param("ii", $resourceId, $classId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // Successfully unassigned 
        $stmt->close();
        header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&deleted=1");
        exit();
    } else {
        // Resource was not assigned
        $stmt->close();
        header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&delete_failed=1");
        exit();
    }
} else {
    $stmt->close();
    header("Location: resources.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&delete_failed=1");
    exit();
}
?>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Get GET values
$activityId = isset($_GET['activityId']) ? intval($_GET['activityId']) : 0;
$grade      = $_GET['gra'] ?? '';
$subjectId  = $_GET['sub'] ?? '';
$group      = $_GET['group'] ?? '';

// Validate input
if (!$activityId || !$grade || !$subjectId || !$group) {
    die("Missing required fields.");
}

// Find ClassID for this grade, subject, and group
$stmt = $connect->prepare("SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1");
$stmt->bind_param("sis", $grade, $subjectId, $group);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $classId = $row['ClassID'];
} else {
    die("Class not found.");
}
$stmt->close();

// Delete the assignment
$stmt = $connect->prepare("DELETE FROM onlineactivitiesassignments WHERE OnlineActivityId = ? AND ClassID = ?");
$stmt->bind_param("ii", $activityId, $classId);

if ($stmt->execute()) {
    $stmt->close();
    $connect->close();
    header("Location: assignedquizzes.php?gra=" . urlencode($grade) . "&sub=" . urlencode($subjectId) . "&group=" . urlencode($group) . "&deleted=1");
    exit();
} else {
    $errorMsg = $stmt->error;
    $stmt->close();
    $connect->close();
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Failed to unassign activity",
            text: "Error: ' . addslashes($errorMsg) . '",
            backdrop: true,
            confirmButtonText: "OK"
        });
    </script>';
    exit();
}
?>

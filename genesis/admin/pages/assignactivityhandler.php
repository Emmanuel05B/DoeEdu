<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Get POST values
$activityId = $_POST['activityId'] ?? null;
$grade      = $_POST['grade'] ?? '';
$subjectId  = $_POST['subject'] ?? '';
$group      = $_POST['group'] ?? '';
$dueDate    = $_POST['dueDate'] ?? '';

// Validate input
if (!$activityId || !$grade || !$subjectId || !$group || !$dueDate) {
    die("Missing required fields. date");
}

// Find ClassID for this grade, subject, and group
$stmt = $connect->prepare("SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1");
$stmt->bind_param("iis", $grade, $subjectId, $group);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $classId = $row['ClassID'];
} else {
    die("Class not found.");
}
$stmt->close();

// Check if activity already assigned
$stmt = $connect->prepare("SELECT COUNT(*) AS cnt FROM onlineactivitiesassignments WHERE OnlineActivityId = ? AND ClassID = ?");
$stmt->bind_param("ii", $activityId, $classId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row['cnt'] > 0) {
    die("This activity has already been assigned to this class.");
}
$stmt->close();



// Insert assignment into onlineactivitiesassignments
$stmt = $connect->prepare("
    INSERT INTO onlineactivitiesassignments (OnlineActivityId, ClassID, DueDate)
    VALUES (?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: (" . $connect->errno . ") " . $connect->error);
}

$stmt->bind_param("iis", $activityId, $classId, $dueDate);

if ($stmt->execute()) {
    $stmt->close();
    $connect->close();

    header("Location: assignedquizzes.php?gra=$grade&sub=$subjectId&group=$group&assigned=1");

    exit();
} else {
    $errorMsg = $stmt->error;
    $stmt->close();
    $connect->close();

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Failed to assign activity",
            text: "Error: ' . addslashes($errorMsg) . '",
            backdrop: true,
            confirmButtonText: "OK"
        });
    </script>';
    exit();
}


?>

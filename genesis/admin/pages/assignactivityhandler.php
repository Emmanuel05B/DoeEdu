<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Get POST values
$activityId = $_POST['activityId'] ?? null;
$grade      = $_POST['grade'] ?? '';
$subjectId  = $_POST['subject'] ?? '';
$group      = $_POST['group'] ?? '';

// Validate input
if (!$activityId || !$grade || !$subjectId || !$group) {
    die("Missing required fields.");
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
    INSERT INTO onlineactivitiesassignments (OnlineActivityId, ClassID) 
    VALUES (?, ?)
");

if (!$stmt) {
    die("Prepare failed: (" . $connect->errno . ") " . $connect->error);
}

$stmt->bind_param("ii", $activityId, $classId);

if ($stmt->execute()) {
    $stmt->close();
    $connect->close();

    header("Location: generateactivity.php?gra=$grade&sub=$subjectId&group=$group&assigned=1");

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

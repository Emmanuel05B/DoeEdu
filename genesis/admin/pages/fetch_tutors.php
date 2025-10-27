<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
if (!isset($_GET['subjectId'], $_GET['gradeName'])) {
    echo json_encode([]);
    exit();
}

$subjectId = intval($_GET['subjectId']);
$gradeName = $_GET['gradeName'];

// Fetch tutors registered for this subject + grade
$stmt = $connect->prepare("
    SELECT u.Id, u.Name, u.Surname 
    FROM users u
    JOIN tutorsubject ts ON u.Id = ts.TutorId
    JOIN subjects s ON ts.SubjectId = s.SubjectId
    JOIN grades g ON s.GradeId = g.GradeId
    WHERE ts.SubjectId = ? AND g.GradeName = ?
    ORDER BY u.Name, u.Surname
");
$stmt->bind_param("is", $subjectId, $gradeName);
$stmt->execute();
$result = $stmt->get_result();

$tutors = [];
while ($row = $result->fetch_assoc()) {
    $tutors[] = $row;
}

$stmt->close();

echo json_encode($tutors);



<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schoolId'], $_POST['grade'])) {
    $schoolId = intval($_POST['schoolId']);
    $gradeName = $_POST['grade'];

    // First, get the GradeId for this school + grade
    $stmt = $connect->prepare("SELECT GradeId FROM grades WHERE SchoolId = ? AND GradeName = ?");
    $stmt->bind_param("is", $schoolId, $gradeName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($gradeRow = $result->fetch_assoc()) {
        $gradeId = $gradeRow['GradeId'];

        // Get subjects for this grade
        $stmtSubj = $connect->prepare("SELECT SubjectName FROM subjects WHERE GradeId = ? ORDER BY SubjectName ASC");
        $stmtSubj->bind_param("i", $gradeId);
        $stmtSubj->execute();
        $resultSubj = $stmtSubj->get_result();

        $subjects = [];
        while ($row = $resultSubj->fetch_assoc()) {
            $subjects[] = $row['SubjectName'];
        }
        $stmtSubj->close();

        header('Content-Type: application/json');
        echo json_encode($subjects);
        exit();
    }
    $stmt->close();
}

http_response_code(400);
echo json_encode([]);
exit();
?>

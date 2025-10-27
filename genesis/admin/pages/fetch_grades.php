<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schoolId'])) {
    $schoolId = intval($_POST['schoolId']);

    $stmt = $connect->prepare("SELECT GradeName FROM grades WHERE SchoolId = ? ORDER BY GradeName ASC");
    $stmt->bind_param("i", $schoolId);
    $stmt->execute();
    $result = $stmt->get_result();

    $grades = [];
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row['GradeName'];
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($grades);
    exit();
}

http_response_code(400);
echo json_encode([]);
exit();
?>

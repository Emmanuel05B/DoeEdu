<?php
require_once __DIR__ . '/../../common/config.php'; 
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/connect.php");

if(isset($_GET['grade']) && !empty($_GET['grade'])){
    $gradeName = $_GET['grade'];

    $stmt = $connect->prepare("
        SELECT s.SubjectId, s.SubjectName
        FROM subjects s
        JOIN grades g ON s.GradeId = g.GradeId
        WHERE g.GradeName = ?
        ORDER BY s.SubjectName
    ");
    $stmt->bind_param("s", $gradeName);
    $stmt->execute();
    $res = $stmt->get_result();

    $subjects = [];
    while($row = $res->fetch_assoc()){
        $subjects[] = $row;
    }

    echo json_encode($subjects);
}

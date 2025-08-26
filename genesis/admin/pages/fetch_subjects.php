<?php
include(__DIR__ . "/../../partials/connect.php");

if(isset($_POST['gradeId'])){
    $gradeId = intval($_POST['gradeId']);
    $stmt = $connect->prepare("SELECT SubjectId, SubjectName, ThreeMonthsPrice, SixMonthsPrice, TwelveMonthsPrice FROM subjects WHERE GradeId = ? ORDER BY SubjectName ASC");
    $stmt->bind_param("i", $gradeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = [];
    while($row = $result->fetch_assoc()){
        $subjects[] = $row;
    }
    echo json_encode($subjects);
}
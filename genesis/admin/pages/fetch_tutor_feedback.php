<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
// Ensure tutor_id is provided
$tutor_id = $_POST['tutor_id'] ?? '';
$type = $_POST['type'] ?? 'summary'; // default to summary

if (empty($tutor_id)) {
    echo json_encode(['details' => [], 'avg_rating'=>null, 'count'=>0, 'comments'=>[]]);
    exit;
}

$response = [
    'avg_rating' => null,
    'count' => 0,
    'comments' => [],
    'details' => []
];

if ($type === 'oneonone') {
    $stmt = $connect->prepare("
        SELECT SubjectName AS subject, OverallRating AS rating, Understanding AS comment
        FROM tutorfeedback
        WHERE TutorId = ?
    ");
    $stmt->bind_param('i', $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ratings = [];
    $comments = [];

    while ($row = $result->fetch_assoc()) {
        $response['details'][] = [
            'subject' => $row['subject'] ?? 'N/A',
            'rating' => $row['rating'] ?? 0,
            'comment' => $row['comment'] ?? ''
        ];
        if (isset($row['rating'])) $ratings[] = $row['rating'];
        if (!empty($row['comment'])) $comments[] = $row['comment'];
    }

    if (count($ratings) > 0) {
        $response['avg_rating'] = round(array_sum($ratings)/count($ratings), 1);
        $response['count'] = count($ratings);
    }
    $response['comments'] = array_slice($comments, -5); // last 5 comments

} elseif ($type === 'classmeeting') {
    $stmt = $connect->prepare("
        SELECT s.SubjectName AS subject, OverallSatisfaction AS rating, Comments AS comment
        FROM meetingfeedback m
        LEFT JOIN subjects s ON m.SubjectId = s.SubjectId
        WHERE TutorId = ?
    ");
    $stmt->bind_param('i', $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ratings = [];
    $comments = [];

    while ($row = $result->fetch_assoc()) {
        $response['details'][] = [
            'subject' => $row['subject'] ?? 'N/A',
            'rating' => $row['rating'] ?? 0,
            'comment' => $row['comment'] ?? ''
        ];
        if (isset($row['rating'])) $ratings[] = $row['rating'];
        if (!empty($row['comment'])) $comments[] = $row['comment'];
    }

    if (count($ratings) > 0) {
        $response['avg_rating'] = round(array_sum($ratings)/count($ratings), 1);
        $response['count'] = count($ratings);
    }
    $response['comments'] = array_slice($comments, -5);

} elseif ($type === 'summary') {
    // Fetch all ratings from both tables for summary
    $ratings = [];
    $comments = [];
    $details = [];

    // One-on-one feedback
    $stmt1 = $connect->prepare("
        SELECT SubjectName AS subject, OverallRating AS rating, Understanding AS comment
        FROM tutorfeedback
        WHERE TutorId = ?
    ");
    $stmt1->bind_param('i', $tutor_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    while ($row = $result1->fetch_assoc()) {
        $ratings[] = $row['rating'];
        if (!empty($row['comment'])) $comments[] = $row['comment'];
        $details[] = [
            'subject' => $row['subject'] ?? 'One-on-One',
            'rating' => $row['rating'] ?? 0,
            'comment' => $row['comment'] ?? ''
        ];
    }

    // Class meeting feedback
    $stmt2 = $connect->prepare("
        SELECT s.SubjectName AS subject, OverallSatisfaction AS rating, Comments AS comment
        FROM meetingfeedback m
        LEFT JOIN subjects s ON m.SubjectId = s.SubjectId
        WHERE TutorId = ?
    ");
    $stmt2->bind_param('i', $tutor_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    while ($row = $result2->fetch_assoc()) {
        $ratings[] = $row['rating'];
        if (!empty($row['comment'])) $comments[] = $row['comment'];
        $details[] = [
            'subject' => $row['subject'] ?? 'Class Meeting',
            'rating' => $row['rating'] ?? 0,
            'comment' => $row['comment'] ?? ''
        ];
    }

    $response['details'] = $details;

    if (count($ratings) > 0) {
        $response['avg_rating'] = round(array_sum($ratings)/count($ratings), 1);
        $response['count'] = count($ratings);
    }
    $response['comments'] = array_slice($comments, -5);

    $stmt1->close();
    $stmt2->close();
}

echo json_encode($response);
$connect->close();

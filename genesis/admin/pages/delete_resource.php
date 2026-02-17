<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

header('Content-Type: text/plain');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo 'unauthorized';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    http_response_code(400);
    echo 'invalid';
    exit;
}

include_once(BASE_PATH . "/partials/connect.php");


$resourceId = intval($_POST['id']);

// Step 1: Fetch resource
$stmt = $connect->prepare("SELECT FilePath FROM resources WHERE ResourceID = ?");
$stmt->bind_param("i", $resourceId);
$stmt->execute();
$result = $stmt->get_result();
$resource = $result->fetch_assoc();
$stmt->close();

if (!$resource) {
    echo 'not_found';
    exit;
}

// Step 2: Delete file from server
$filePath = RESOURCES_PATH . '/' . $resource['FilePath'];
if (file_exists($filePath)) {
    unlink($filePath); // attempt to delete file 
}

// Step 3: Delete from database
$stmt = $connect->prepare("DELETE FROM resources WHERE ResourceID = ?");
$stmt->bind_param("i", $resourceId);
if ($stmt->execute()) {
    echo 'deleted';
} else {
    echo 'db_error';
}
$stmt->close();

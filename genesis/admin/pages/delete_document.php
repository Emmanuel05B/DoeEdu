<?php
require_once __DIR__ . '/../../common/config.php';
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: ourdocs.php");
    exit();
}


$stmt = $connect->prepare("
    SELECT FilePath 
    FROM documents 
    WHERE Id = ? AND IsDeleted = 0
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    $filePath = DOCUMENTS_PATH . '/' . $row['FilePath'];

    // Delete physical file (optional but recommended)
     
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Soft delete record
    $update = $connect->prepare("
        UPDATE documents 
        SET IsDeleted = 1 
        WHERE Id = ?
    ");
    $update->bind_param("i", $id);
    $update->execute();
}

header("Location: ourdocs.php?deleted=1");
exit();

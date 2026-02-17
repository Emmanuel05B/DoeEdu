

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
    exit('Invalid request');
}

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$uploadedBy  = $_SESSION['user_id'];

$file = $_FILES['resource_file'] ?? null;

if (!$title || !$file) {
    header("Location: ourdocs.php?upload_failed=1");
    exit();
}

$uploadDir = DOCUMENTS_PATH . '/';

// Allowed extensions (simple & safe)
$allowedExtensions = [
    'pdf', 'doc', 'docx', 'xls', 'xlsx',
    'ppt', 'pptx', 'txt', 'csv',
    'zip', 'rar', '7z'
];

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions)) {
    header("Location: ourdocs.php?upload_failed=1&type=invalid");
    exit();
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    header("Location: ourdocs.php?upload_failed=1");
    exit();
}

// Ensure folder exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Sanitize + unique filename
$cleanName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", basename($file['name']));
$uniqueFileName = uniqid('doc_') . '_' . $cleanName;
$targetPath = $uploadDir . $uniqueFileName;

// Move file
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    header("Location: ourdocs.php?upload_failed=1");
    exit();
}

// Insert into documents table
$sql = "INSERT INTO documents 
        (Title, Description, FilePath, UploadedBy) 
        VALUES (?, ?, ?, ?)";

$stmt = $connect->prepare($sql);
$stmt->bind_param(
    "sssi",
    $title,
    $description,
    $uniqueFileName,
    $uploadedBy
);

if ($stmt->execute()) {
    header("Location: ourdocs.php?uploaded=1");
    exit();
}

// Fallback error
header("Location: ourdocs.php?upload_failed=1");
exit();



<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");
?>

<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$title = trim($_POST['title'] ?? '');
$classId = trim($_POST['classId'] ?? '');
$visibility = trim($_POST['visibility'] ?? 'private');
$description = trim($_POST['description'] ?? '');
$uploadedBy = $_SESSION['user_id'] ?? null;

$file = $_FILES['resource_file'];
$uploadDir = '../../uploads/resources/';


$mimeType = $file['type'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Determine resource type based on MIME or extension
switch (true) {
    case str_contains($mimeType, 'image'):
        $resourceType = 'image';
        break;
    case str_contains($mimeType, 'video'):
        $resourceType = 'video';
        break;
    case str_contains($mimeType, 'audio'):
        $resourceType = 'audio';
        break;
    case in_array($extension, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']):
        $resourceType = 'document';
        break;
    case in_array($extension, ['zip', 'rar', '7z']):
        $resourceType = 'compressed';
        break;
    case in_array($extension, ['txt', 'csv']):
        $resourceType = 'text';
        break;
    case $extension === 'pdf':
        $resourceType = 'pdf';
        break;
    default:
        $resourceType = 'other';
        break;
}


$allowedTypes = [
    'application/pdf',

    // Images
    'image/jpeg',
    'image/png',
    'image/jpg',
    'image/gif',
    'image/webp',

    // Microsoft Office
    'application/msword', // .doc
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
    'application/vnd.ms-excel', // .xls
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
    'application/vnd.ms-powerpoint', // .ppt
    'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx

    // Videos
    'video/mp4',
    'video/x-msvideo', // .avi
    'video/quicktime', // .mov
    'video/x-matroska', // .mkv
    'video/webm',

    // Zipped 
    'application/zip',
    'application/x-zip-compressed',
    'multipart/x-zip', // rare but included just in case
    'application/x-compressed',

    // Text files
    'text/plain', // .txt
    'text/csv',

    // Compressed
    'application/zip',
    'application/x-rar-compressed',
    'application/x-7z-compressed',

    // Audio (if needed)
    'audio/mpeg', // .mp3
    'audio/wav',
    'audio/mp4',
    'audio/x-m4a',
    'audio/ogg'
    
];


// Validation
if (!$title || !$classId || !$resourceType || !$uploadedBy) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Missing Fields',
            text: 'Please fill all required fields.',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
    exit();
}

if (!in_array($file['type'], $allowedTypes)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Unsupported file type',
            text: 'File type {$file['type']} is not allowed.',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
    exit();
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Upload error',
            text: 'Error code: {$file['error']}',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
    exit();
}

// Make sure upload folder exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$fileName = basename($file['name']);
$uniqueFileName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $fileName);
$targetPath = $uploadDir . $uniqueFileName;

// Get SubjectID and Grade from ClassID
$classQuery = "SELECT SubjectID, Grade FROM classes WHERE ClassID = ?";
$classStmt = $connect->prepare($classQuery);
$classStmt->bind_param("i", $classId);
$classStmt->execute();
$classResult = $classStmt->get_result();

if ($classResult->num_rows === 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid class selected',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
    exit();
}

$classRow = $classResult->fetch_assoc();
$subjectId = $classRow['SubjectID'];
$grade = $classRow['Grade'];

// Move file
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Upload failed',
            text: 'Could not save uploaded file.',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
    exit();
}

// Insert into `resources`
$insertSql = "INSERT INTO resources 
    (Title, FilePath, ResourceType, SubjectID, Grade, Description, Visibility, UploadedBy) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $connect->prepare($insertSql);
$stmt->bind_param("sssisssi", 
    $title,
    $uniqueFileName,
    $resourceType,
    $subjectId,
    $grade,
    $description,
    $visibility,
    $uploadedBy
);

if ($stmt->execute()) {

    header("Location: studyresources.php?uploaded=1");
    exit;
    
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Database error',
            text: 'Could not save resource info.',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
}
?>


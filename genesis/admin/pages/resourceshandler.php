<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$title = trim($_POST['title'] ?? '');
$classId = trim($_POST['classId'] ?? '');
$resourceType = trim($_POST['resource_type'] ?? '');
$visibility = trim($_POST['visibility'] ?? 'private');
$description = trim($_POST['description'] ?? '');
$uploadedBy = $_SESSION['user_id'] ?? null;

$file = $_FILES['resource_file'];
$uploadDir = '../../uploads/resources/';

$allowedTypes = [
    'application/pdf',
    'image/jpeg',
    'image/png',
    'image/jpg',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'video/mp4'
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
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Resource uploaded!',
        }).then(() => {
            window.location = 'studyresources.php';
        });
    </script>";
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

<div class="wrapper"></div>
</body>
</html>

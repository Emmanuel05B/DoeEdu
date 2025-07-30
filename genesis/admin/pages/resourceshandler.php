<?php
session_start();
include('../../common/partials/connect.php');

if (!isset($_SESSION['Id'])) {
    die("Unauthorized.");
}

$uploadedBy = $_SESSION['Id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $title = trim($_POST['title']);
    $subjectGrade = $_POST['subject_grade']; // Expecting something like "3|Grade 11"
    $resourceType = $_POST['resource_type'];
    $visibility = $_POST['visibility'];
    $description = trim($_POST['description'] ?? '');

    // Split SubjectID and Grade
    list($subjectId, $grade) = explode('|', $subjectGrade);

    // File upload handling
    $file = $_FILES['resource_file'];
    $uploadDir = '../../uploads/resources/';
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'video/mp4'];

    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['error_message'] = "Unsupported file type.";
        header("Location: studyresources.php");
        exit();
    }

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($file['name']);
        $targetPath = $uploadDir . uniqid() . "_" . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO resources (Title, FilePath, ResourceType, SubjectID, Grade, Description, Visibility, UploadedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $title, $targetPath, $resourceType, $subjectId, $grade, $description, $visibility, $uploadedBy);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Resource uploaded successfully.";
            } else {
                $_SESSION['error_message'] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Failed to move uploaded file.";
        }
    } else {
        $_SESSION['error_message'] = "Upload error code: " . $file['error'];
    }

    header("Location: studyresources.php");
    exit();
} else {
    header("Location: studyresources.php");
    exit();
}

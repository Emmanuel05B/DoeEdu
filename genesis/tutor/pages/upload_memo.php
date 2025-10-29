<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


$subject = $_POST['subject'] ?? '';
$grade = $_POST['grade'] ?? '';
$level = $_POST['level'] ?? '';   // LevelName now
$chapter = $_POST['chapter'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['memo_pdf']) || $_FILES['memo_pdf']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Please upload a valid PDF file.'];
        header("Location: create_questions.php?subject=" . urlencode($subject) . "&grade=" . urlencode($grade) . "&level=" . urlencode($level) . "&chapter=" . urlencode($chapter));
        exit();
    }

    // Validate file type
    $fileTmpPath = $_FILES['memo_pdf']['tmp_name'];
    $fileName = $_FILES['memo_pdf']['name'];
    $fileType = mime_content_type($fileTmpPath);

    if ($fileType !== 'application/pdf') {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Uploaded file must be a PDF.'];
        header("Location: create_questions.php?subject=" . urlencode($subject) . "&grade=" . urlencode($grade) . "&level=" . urlencode($level) . "&chapter=" . urlencode($chapter));
        exit();
    }

    // Create upload directory if not exists
    $uploadDir = PQ_MEMOS_PATH . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Slugify function for safe filenames
    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text ?: 'file';
    }

    $baseFileName = slugify("{$subject}_{$grade}_{$level}_{$chapter}");
    $newFileName = $baseFileName . '_' . time() . '.pdf';
    $destPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to move uploaded file.'];
        header("Location: create_questions.php?subject=" . urlencode($subject) . "&grade=" . urlencode($grade) . "&level=" . urlencode($level) . "&chapter=" . urlencode($chapter));
        exit();
    }

    // Check if memo exists
    $stmt = $connect->prepare("SELECT Id, MemoFilename FROM memos WHERE SubjectName = ? AND GradeName = ? AND LevelName = ? AND Chapter = ?");
    
    
    if (!$stmt) {
    die("Prepare failed: (" . $connect->errno . ") " . $connect->error);
}
    
    $stmt->bind_param("ssss", $subject, $grade, $level, $chapter);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingMemo = $result->fetch_assoc();
    $stmt->close();

    if ($existingMemo) {
        // Delete old file if exists
        if ($existingMemo['MemoFilename'] && file_exists($uploadDir . $existingMemo['MemoFilename'])) {
            unlink($uploadDir . $existingMemo['MemoFilename']);
        }
        // Update memo record
        $stmt = $connect->prepare("UPDATE memos SET MemoFilename = ?, UploadedAt = NOW() WHERE Id = ?");
        $stmt->bind_param("si", $newFileName, $existingMemo['Id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new memo record
        $stmt = $connect->prepare("INSERT INTO memos (SubjectName, GradeName, LevelName, Chapter, MemoFilename, UploadedAt) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $subject, $grade, $level, $chapter, $newFileName);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Memo uploaded successfully.'];
    header("Location: create_questions.php?subject=" . urlencode($subject) . "&grade=" . urlencode($grade) . "&level=" . urlencode($level) . "&chapter=" . urlencode($chapter));
    exit();
}
?>

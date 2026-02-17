<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


// Validate POST
if (!isset($_POST['activityId']) || !is_numeric($_POST['activityId'])) {
    echo "<script>
        window.onload = function() {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Request',
                text: 'Invalid activity ID.'
            }).then(() => window.history.back());
        }
    </script>";
    exit();
}

$activityId = intval($_POST['activityId']);
$tutorId = $_SESSION['user_id']; 
$title = trim($_POST['Title']);
$instructions = trim($_POST['Instructions']);

// Fetch activity
$stmt = $connect->prepare("SELECT TutorId, ImagePath, MemoPath FROM onlineactivities WHERE Id = ?");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>
        window.onload = function() {
            Swal.fire({
                icon: 'error',
                title: 'Not Found',
                text: 'Activity not found.'
            }).then(() => window.history.back());
        }
    </script>";
    exit();
}

$activity = $result->fetch_assoc();
$stmt->close();

// Get user type
$userStmt = $connect->prepare("SELECT UserType FROM users WHERE Id = ?");
$userStmt->bind_param("i", $tutorId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

// Permission check: Tutor who created it OR Director
if ($activity['TutorId'] != $tutorId && $user['UserType'] != 0) {
    // Example: permission denied
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Permission Denied',
        'text' => 'You are not allowed to update this activity.'
    ];
    header("Location: viewactivity.php?activityId={$activityId}");
    exit();
}

// Handle image
$imagePath = $activity['ImagePath'];
if (isset($_FILES['ImagePath']) && $_FILES['ImagePath']['error'] === 0) {
    if (!is_dir(QUIZ_IMAGES_PATH)) mkdir(QUIZ_IMAGES_PATH, 0755, true);

    $ext = strtolower(pathinfo($_FILES['ImagePath']['name'], PATHINFO_EXTENSION));
    $newFileName = "activity_{$activityId}_" . time() . "." . $ext;
    $targetFile = QUIZ_IMAGES_PATH . '/' . $newFileName;

    if (move_uploaded_file($_FILES['ImagePath']['tmp_name'], $targetFile)) {
        if (!empty($imagePath)) {
            $oldFile = str_replace(QUIZ_IMAGES_URL, QUIZ_IMAGES_PATH, $imagePath);
            if (file_exists($oldFile)) unlink($oldFile);
        }
        $imagePath = QUIZ_IMAGES_URL . '/' . $newFileName;
    }
}

// Handle memo
$memoPath = $activity['MemoPath'];
if (isset($_FILES['MemoPath']) && $_FILES['MemoPath']['error'] === 0) {
    if (!is_dir(QUIZ_MEMOS_PATH)) mkdir(QUIZ_MEMOS_PATH, 0755, true);

    $ext = strtolower(pathinfo($_FILES['MemoPath']['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Invalid File',
            'text' => 'The memo must be a PDF file.'
        ];
        header("Location: viewactivity.php?activityId={$activityId}");
        exit();
    }

    $newFileName = "memo_{$activityId}_" . time() . "." . $ext;
    $targetFile = QUIZ_MEMOS_PATH . '/' . $newFileName;

    if (move_uploaded_file($_FILES['MemoPath']['tmp_name'], $targetFile)) {
        if (!empty($memoPath)) {
            $oldFile = str_replace(QUIZ_MEMOS_URL, QUIZ_MEMOS_PATH, $memoPath);
            if (file_exists($oldFile)) unlink($oldFile);
        }
        $memoPath = QUIZ_MEMOS_URL . '/' . $newFileName;
    }
}


// Update
$updateStmt = $connect->prepare("UPDATE onlineactivities SET Title = ?, Instructions = ?, ImagePath = ?, MemoPath = ? WHERE Id = ?");
$updateStmt->bind_param("ssssi", $title, $instructions, $imagePath, $memoPath, $activityId);

if ($updateStmt->execute()) {
    // Example: success
    $_SESSION['alert'] = [
    'icon' => 'success',
    'title' => 'Updated!',
    'text' => 'The activity has been successfully updated.'
    ];
    header("Location: viewactivity.php?activityId={$activityId}");
    exit();
    
} else {
    // Example: update failed
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Update Failed',
        'text' => 'Could not update activity. Please try again.'
    ];
    header("Location: viewactivity.php?activityId={$activityId}");
    exit();
}

$updateStmt->close();
$connect->close();
?>


<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


$tutorId = $_SESSION['user_id'];

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: schedule.php");
    exit();
}

$action = $_POST['action'] ?? '';
$day = $_POST['day'] ?? '';
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';

if (empty($day) || empty($start) || empty($end)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Missing Fields',
        'text' => 'Please fill in all required fields.'
    ];
    header("Location: schedule.php");
    exit();
}

if ($start >= $end) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Invalid Time Range',
        'text' => 'Start time must be before end time.'
    ];
    header("Location: schedule.php");
    exit();
}

try {
    if ($action === 'delete') {
        // 🗑 DELETE THE SLOT
        $sql = "DELETE FROM tutoravailability 
                WHERE TutorId = ? AND DayOfWeek = ? AND StartTime = ? AND EndTime = ? AND AvailabilityType = 'OnceOff'";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("isss", $tutorId, $day, $start, $end);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Availability Deleted',
            'text' => "Your once-off availability for {$day} ({$start} - {$end}) has been removed."
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Invalid Action',
            'text' => 'Unsupported operation requested.'
        ];
    }

} catch (Exception $e) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'text' => 'There was a problem deleting your availability. Please try again.'
    ];
}

header("Location: schedule.php");
exit();
?>

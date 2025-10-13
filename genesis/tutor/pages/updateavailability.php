<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../common/login.php");
    exit();
}

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

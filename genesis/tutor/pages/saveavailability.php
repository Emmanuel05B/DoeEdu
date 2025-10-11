<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id'])) {
    
    header("Location: ../common/login.php");
    exit();
}

$tutorId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = $_POST['days'] ?? [];
    $startTimes = $_POST['start'] ?? [];
    $endTimes = $_POST['end'] ?? [];

    if (empty($days)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'No days selected',
            'text' => 'Please select at least one day.'
        ];
        header("Location: schedule.php");
        exit();
    }

    // Delete old availability
    $deleteSql = "DELETE FROM tutoravailability WHERE TutorId = ? AND AvailabilityType = 'Recurring'";
    $stmt = $connect->prepare($deleteSql);
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $stmt->close();

    // Insert new availability
    $insertSql = "INSERT INTO tutoravailability (TutorId, DayOfWeek, StartTime, EndTime, AvailabilityType)
                  VALUES (?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($insertSql);

    $errors = [];
    $type = "Recurring";
    foreach ($days as $day) {
        $start = $startTimes[$day] ?? null;
        $end = $endTimes[$day] ?? null;

        if (!$start || !$end) {
            $errors[] = "Missing start or end time for $day.";
            continue;
        }
        if ($start >= $end) {
            $errors[] = "Start time must be before end time for $day.";
            continue;
        }

        $stmt->bind_param("issss", $tutorId, $day, $start, $end, $type);
        $stmt->execute();
    }
    $stmt->close();

    if (!empty($errors)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Validation Errors',
            'text' => implode("\n", $errors)
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Availability Saved',
            'text' => 'Your weekly availability has been updated successfully.'
        ];
    }

    header("Location: schedule.php");
    exit();

} else {
    header("Location: schedule.php");
    exit();
}
?>

<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../common/login.php");
    exit();
}

$tutorId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['parenttitle'] ?? '';
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';

    if (empty($day) || empty($start) || empty($end)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Missing Information',
            'text' => 'Please fill in all fields before saving.'
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
        // 🔍 Step 1: Check for duplicate or overlapping slots
        $checkSql = "
            SELECT * 
            FROM tutoravailability 
            WHERE TutorId = ? 
              AND DayOfWeek = ?
              AND (
                    (? BETWEEN StartTime AND EndTime)
                 OR (? BETWEEN StartTime AND EndTime)
                 OR (StartTime BETWEEN ? AND ?)
                 OR (EndTime BETWEEN ? AND ?)
                 OR (StartTime = ? AND EndTime = ?)
              )
        ";

        $stmt = $connect->prepare($checkSql);
        // ✅ Correct number of placeholders (10 total)
        $stmt->bind_param(
            "isssssssss",
            $tutorId,
            $day,
            $start, $end,
            $start, $end,
            $start, $end,
            $start, $end
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Time Conflict',
                'text' => "You already have an availability that overlaps with {$start} - {$end} on {$day}."
            ];
            $stmt->close();
            header("Location: schedule.php");
            exit();
        }
        $stmt->close();

        // ✅ Step 2: Insert new availability
        $sql = "INSERT INTO tutoravailability (TutorId, DayOfWeek, StartTime, EndTime, AvailabilityType)
                VALUES (?, ?, ?, ?, 'OnceOff')";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("isss", $tutorId, $day, $start, $end);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Availability Added',
            'text' => "Once-off availability for {$day} ({$start} - {$end}) was saved successfully."
        ];
    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Database Error',
            'text' => 'There was an issue saving your availability. Please try again.'
        ];
    }

    header("Location: schedule.php");
    exit();

} else {
    header("Location: schedule.php");
    exit();
}
?>

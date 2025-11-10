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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = $_POST['days'] ?? [];
    $startTimes = $_POST['start'] ?? [];
    $endTimes = $_POST['end'] ?? [];

    if (empty($days)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'No days selected',
            'message' => 'Please select at least one day.'
        ];
        header("Location: schedule.php");
        exit();
    }

    // Delete old recurring availability
    $deleteSql = "DELETE FROM tutoravailability WHERE TutorId = ? AND AvailabilityType = 'Recurring'";
    $stmt = $connect->prepare($deleteSql);
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $stmt->close();

    // Prepare insert statement
    $insertSql = "INSERT INTO tutoravailability (TutorId, DayOfWeek, StartTime, EndTime, AvailabilityType)
                  VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $connect->prepare($insertSql);

    $errors = [];
    $type = "Recurring";

    foreach ($days as $day) {
        $start = $startTimes[$day] ?? null;
        $end = $endTimes[$day] ?? null;

        // Basic validations
        if (!$start || !$end) {
            $errors[] = "Missing start or end time for $day.";
            continue;
        }
        if ($start >= $end) {
            $errors[] = "Start time must be before end time for $day.";
            continue;
        }

        // Check for overlapping availabilities (Recurring or OnceOff)
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
        $stmtCheck = $connect->prepare($checkSql);
        $stmtCheck->bind_param(
            "isssssssss",
            $tutorId,
            $day,
            $start, $end,
            $start, $end,
            $start, $end,
            $start, $end
        );
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Conflict detected for {$day}: {$start} - {$end} overlaps with an existing slot.";
            $stmtCheck->close();
            continue; // Skip insert
        }
        $stmtCheck->close();

        // insert new availability
        $stmtInsert->bind_param("issss", $tutorId, $day, $start, $end, $type);
        $stmtInsert->execute();
    }

    $stmtInsert->close();

    
    if (!empty($errors)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Validation Errors',
            'message' => implode("\\n", $errors)
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Availability Saved',
            'message' => 'Your weekly availability has been updated successfully.'
        ];
    }

    header("Location: schedule.php");
    exit();

} else {
    header("Location: schedule.php");
    exit();
}
?>

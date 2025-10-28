<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


// Get tutor and learner IDs
$tutorId   = intval($_GET['tutor']);   
$learnerId = intval($_GET['learner']); 

// --- Step 1: Fetch all booked or blocked slots ---
$bookedStmt = $connect->prepare("
    SELECT SlotDateTime, Status, LearnerId
    FROM tutorsessions
    WHERE TutorId = ?
      AND SlotDateTime >= NOW()
      AND (
            Status IN ('Pending', 'Confirmed', 'Completed')           -- Block for everyone
            OR (Status = 'Declined' AND LearnerId = ?)  -- Block only for this learner
          )
");
$bookedStmt->bind_param("ii", $tutorId, $learnerId);
$bookedStmt->execute();
$bookedRes = $bookedStmt->get_result();

$blocked = [];
while ($r = $bookedRes->fetch_assoc()) {
    $blocked[] = (new DateTime($r['SlotDateTime']))->format('Y-m-d H:i');
}
$bookedStmt->close();

// --- Step 2: Fetch tutor availability ---

// Recurring availability
$recurringStmt = $connect->prepare("
    SELECT DayOfWeek, StartTime, EndTime
    FROM tutoravailability
    WHERE TutorId = ? AND AvailabilityType = 'Recurring'
");
$recurringStmt->bind_param("i", $tutorId);
$recurringStmt->execute();
$recurringRes = $recurringStmt->get_result();
$recurringAvailability = $recurringRes->fetch_all(MYSQLI_ASSOC);
$recurringStmt->close();

// Once-Off availability
$onceOffStmt = $connect->prepare("
    SELECT DayOfWeek, StartTime, EndTime
    FROM tutoravailability
    WHERE TutorId = ? AND AvailabilityType = 'OnceOff'
");
$onceOffStmt->bind_param("i", $tutorId);
$onceOffStmt->execute();
$onceOffRes = $onceOffStmt->get_result();
$onceOffAvailability = $onceOffRes->fetch_all(MYSQLI_ASSOC);
$onceOffStmt->close();

// --- Step 3: Generate available slots ---
$slots = [];
$today = new DateTime();
$interval = new DateInterval('P1D');

// Recurring --->  next 14 days
$recEndDate = (clone $today)->modify('+14 days');
$period = new DatePeriod($today, $interval, $recEndDate);

foreach ($period as $date) {
    $dayName = $date->format('l');

    foreach ($recurringAvailability as $slot) {
        if ($slot['DayOfWeek'] === $dayName) {
            $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $slot['StartTime']);
            $endDateTime   = new DateTime($date->format('Y-m-d') . ' ' . $slot['EndTime']);
            $slotValue     = $startDateTime->format('Y-m-d H:i');

            if (!in_array($slotValue, $blocked)) {
                $slots[] = [
                    'start' => $startDateTime,
                    'end'   => $endDateTime,
                    'type'  => 'Recurring'
                ];
            }
        }
    }
}

// Once-Off --> next 7 days
$onceEndDate = (clone $today)->modify('+7 days');
$weekPeriod = new DatePeriod($today, $interval, $onceEndDate);

foreach ($weekPeriod as $date) {
    $dayName = $date->format('l');

    foreach ($onceOffAvailability as $slot) {
        if ($slot['DayOfWeek'] === $dayName) {
            $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $slot['StartTime']);
            $endDateTime   = new DateTime($date->format('Y-m-d') . ' ' . $slot['EndTime']);
            $slotValue     = $startDateTime->format('Y-m-d H:i');

            if (!in_array($slotValue, $blocked)) {
                $slots[] = [
                    'start' => $startDateTime,
                    'end'   => $endDateTime,
                    'type'  => 'Once-Off'
                ];
            }
        }
    }
}

// --- Step 4: Sort all slots by datetime ascending ---
usort($slots, function($a, $b) {
    return $a['start'] <=> $b['start'];
});

// --- Step 5: Output as <option> tags ---
foreach ($slots as $slot) {
    $startStr = $slot['start']->format('l, d M Y H:i');
    $endStr   = $slot['end']->format('H:i');
    $valueStr = $slot['start']->format('Y-m-d H:i');
    $typeLabel = ($slot['type'] === 'Once-Off') ? ' (Once-Off)' : '';
    echo "<option value='$valueStr'>{$startStr} - {$endStr}{$typeLabel}</option>";
}
?>

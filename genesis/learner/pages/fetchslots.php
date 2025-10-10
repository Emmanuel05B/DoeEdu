<?php
include(__DIR__ . "/../../partials/connect.php");

$tutorId = intval($_GET['tutor']);

// Get booked slots for this tutor within the next 14 days
$bookedStmt = $connect->prepare("
    SELECT SlotDateTime 
    FROM tutorsessions 
    WHERE TutorId = ? AND SlotDateTime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 14 DAY)
");
$bookedStmt->bind_param("i", $tutorId);
$bookedStmt->execute();
$bookedRes = $bookedStmt->get_result();
$booked = [];
while ($r = $bookedRes->fetch_assoc()) {
    $booked[] = $r['SlotDateTime'];
}
$bookedStmt->close();

// Get tutor availability
$availStmt = $connect->prepare("
    SELECT DayOfWeek, StartTime, EndTime
    FROM tutoravailability
    WHERE TutorId = ?
");
$availStmt->bind_param("i", $tutorId);
$availStmt->execute();
$availRes = $availStmt->get_result();
$availability = [];
while ($row = $availRes->fetch_assoc()) {
    $availability[] = $row;
}
$availStmt->close();

// Generate upcoming slots for the next 14 days
$today = new DateTime();
$endDate = (clone $today)->modify('+14 days');
$interval = new DateInterval('P1D');
$period = new DatePeriod($today, $interval, $endDate);

foreach ($period as $date) {
    $dayName = $date->format('l'); // Monday, Tuesday, etc.

    foreach ($availability as $slot) {
        if ($slot['DayOfWeek'] === $dayName) {
            // Combine date with start and end times
            $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $slot['StartTime']);
            $endDateTime = new DateTime($date->format('Y-m-d') . ' ' . $slot['EndTime']);

            // Only add if not booked
            $slotValue = $startDateTime->format('Y-m-d H:i');
            if (!in_array($slotValue, $booked)) {
                echo "<option value='$slotValue'>" . $startDateTime->format('l, d M Y H:i') . " - " . $endDateTime->format('H:i') . "</option>";
            }
        }
    }
}
?>

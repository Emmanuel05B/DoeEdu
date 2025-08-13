<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$status = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tutorId = 2;
    $learnerId = $_SESSION['user_id'];
    $subject = $_POST['subject'] ?? '';
    $slot = $_POST['slot'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (empty($subject) || empty($slot)) {
        $status = "error";
        $message = "Subject and slot selection are required.";
    } else {
        $dt = DateTime::createFromFormat('Y-m-d H:i', $slot);
        if (!$dt) {
            $status = "error";
            $message = "Invalid date/time format.";
        } else {
            $slotDateTime = $dt->format('Y-m-d H:i:s');

            $now = new DateTime();
            $limit = (clone $now)->modify('+14 days');
            if ($dt < $now || $dt > $limit) {
                $status = "error";
                $message = "Selected slot is outside the allowed 14-day booking window.";
            } else {
                // Check if already booked
                $checkStmt = $connect->prepare("SELECT COUNT(*) FROM tutorsessions WHERE TutorId = ? AND SlotDateTime = ?");
                $checkStmt->bind_param("is", $tutorId, $slotDateTime);
                $checkStmt->execute();
                $checkStmt->bind_result($count);
                $checkStmt->fetch();
                $checkStmt->close();

                if ($count > 0) {
                    $status = "error";
                    $message = "This slot has just been taken. Please choose another.";
                } else {
                    $insertStmt = $connect->prepare("INSERT INTO tutorsessions (TutorId, LearnerId, SlotDateTime, Subject, Notes, Status) VALUES (?, ?, ?, ?, ?, 'Pending')");
                    $insertStmt->bind_param("iisss", $tutorId, $learnerId, $slotDateTime, $subject, $notes);
                    if ($insertStmt->execute()) {
                        $status = "success";
                        $message = "Session booked! It’s pending confirmation.";
                    } else {
                        $status = "error";
                        $message = "Something went wrong. Please try again.";
                    }
                    $insertStmt->close();
                }
            }
        }
    }
} else {
    header("Location: booking.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Booking Status</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
  Swal.fire({
    icon: '<?= $status ?>',
    title: '<?= $status === "success" ? "Success!" : "Oops!" ?>',
    text: '<?= addslashes($message) ?>',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'booking.php';
  });
</script>
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>

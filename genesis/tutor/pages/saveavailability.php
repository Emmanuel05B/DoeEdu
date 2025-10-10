<!DOCTYPE html>
<html>
<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

// Include SweetAlert2 scripts (you can also move these to head partial)
?>
<head>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<?php
if (!isset($_SESSION['user_id'])) {
    echo "<script>
    Swal.fire({
      icon: 'error',
      title: 'Unauthorized',
      text: 'Please login first.',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    }).then(() => {
      window.location.href = '../common/login.php';
    });
    </script>";
    exit();
}

$tutorId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = $_POST['days'] ?? [];
    $startTimes = $_POST['start'] ?? [];
    $endTimes = $_POST['end'] ?? [];

    if (empty($days)) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'No days selected',
          text: 'Please select at least one day.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        }).then(() => {
          window.history.back();
        });
        </script>";
        exit();
    }

    // Delete old availability
    $deleteSql = "DELETE FROM tutoravailability WHERE TutorId = ?";
    $stmt = $connect->prepare($deleteSql);
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $stmt->close();

    // Insert new availability
    $insertSql = "INSERT INTO tutoravailability (TutorId, DayOfWeek, StartTime, EndTime) VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($insertSql);

    $errors = [];
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

        $stmt->bind_param("isss", $tutorId, $day, $start, $end);
        $stmt->execute();
    }
    $stmt->close();

    if (!empty($errors)) {
        $errorText = implode("\\n", $errors);
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Validation Errors',
          html: '" . nl2br(htmlspecialchars($errorText)) . "',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        }).then(() => {
          window.history.back();
        });
        </script>";
        exit();
    } else {
        echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Availability Saved',
          text: 'Your weekly availability has been updated successfully.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'schedule.php';
        });
        </script>";
        exit();
    }

} else {
    // Redirect if not POST
    header("Location: schedule.php");
    exit();
}
?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Tutor</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('../../partials/connect.php');

$tutorId = intval($_POST['tutorId']);
$classId = intval($_POST['classId']);

$stmt = $connect->prepare("UPDATE classes SET TutorID = ? WHERE ClassID = ?");
$stmt->bind_param("ii", $tutorId, $classId);

if ($stmt->execute()) {
    echo "
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Tutor Assigned!',
        text: 'Tutor was successfully assigned to the class.',
        confirmButtonText: 'OK'
      }).then(() => {
        window.location.href = 'assigntutorclass.php';
      });
    </script>";
} else {
    echo "
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to assign tutor. Please try again.',
        confirmButtonText: 'OK'
      }).then(() => {
        window.location.href = 'assigntutorclass.php';
      });
    </script>";
}

$stmt->close();
?>
</body>
</html>

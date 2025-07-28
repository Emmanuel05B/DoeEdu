<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$tutorId = 0;
$alertScript = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $tutorId = intval($_POST['tutor_id']);
    $firstname = trim($_POST['firstname']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contactnumber']);
    $subjectIds = isset($_POST['subject_ids']) ? $_POST['subject_ids'] : [];

    $connect->begin_transaction();

    try {
        // Update users table
        $stmtUser = $connect->prepare("UPDATE users SET Name = ?, Surname = ?, Email = ?, Contact = ? WHERE Id = ?");
        $stmtUser->bind_param("ssssi", $firstname, $surname, $email, $contact, $tutorId);
        $stmtUser->execute();
        $stmtUser->close();

        // Delete existing subjects
        $stmtDelete = $connect->prepare("DELETE FROM tutorsubject WHERE TutorId = ?");
        $stmtDelete->bind_param("i", $tutorId);
        $stmtDelete->execute();
        $stmtDelete->close();

        // Insert new subjects
        if (!empty($subjectIds)) {
            $stmtInsert = $connect->prepare("INSERT INTO tutorsubject (TutorId, SubjectId) VALUES (?, ?)");
            foreach ($subjectIds as $subId) {
                $subId = intval($subId);
                $stmtInsert->bind_param("ii", $tutorId, $subId);
                $stmtInsert->execute();
            }
            $stmtInsert->close();
        }

        $connect->commit();

        $alertScript = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Tutor details updated successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'updatetutors.php?id={$tutorId}';
                });
            </script>
        ";
    } catch (Exception $e) {
        $connect->rollback();
        $errorMessage = addslashes($e->getMessage());
        $alertScript = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update tutor details: {$errorMessage}',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            </script>
        ";
    }
} else {
    header("Location: updatetutors.php?id={$tutorId}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Updating Tutor Details</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <?php echo $alertScript; ?>
</body>
</html>

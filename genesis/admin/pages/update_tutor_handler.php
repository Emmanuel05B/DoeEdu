<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


$tutorId = isset($_POST['tutor_id']) ? intval($_POST['tutor_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connect->begin_transaction();

    try {
        // =========================
        // 1. Update Personal Info
        // =========================
        if (isset($_POST['update_personal'])) {
            $firstname = trim($_POST['firstname']);
            $surname = trim($_POST['surname']);
            $email = trim($_POST['email']);
            $contact = "0000000000";

            $stmt = $connect->prepare("UPDATE users SET Name = ?, Surname = ?, Email = ?, Contact = ? WHERE Id = ?");
            $stmt->bind_param("ssssi", $firstname, $surname, $email, $contact, $tutorId);
            $stmt->execute();
            $stmt->close();

            $connect->commit();
            header("Location: updatetutors.php?id=" . urlencode($tutorId) . "&updated_personal=1");
            exit;
        }

        // =========================
        // 2. Update Subjects
        // =========================
        if (isset($_POST['update_subjects'])) {
            $subjectIds = isset($_POST['subject_ids']) ? $_POST['subject_ids'] : [];

            // Delete old subjects
            $stmt = $connect->prepare("DELETE FROM tutorsubject WHERE TutorId = ?");
            $stmt->bind_param("i", $tutorId);
            $stmt->execute();
            $stmt->close();

            // Insert new subjects
            if (!empty($subjectIds)) {
                $stmt = $connect->prepare("INSERT INTO tutorsubject (TutorId, SubjectId) VALUES (?, ?)");
                foreach ($subjectIds as $subId) {
                    $subId = intval($subId);
                    $stmt->bind_param("ii", $tutorId, $subId);
                    $stmt->execute();
                }
                $stmt->close();
            }

            $connect->commit();
            header("Location: updatetutors.php?id=" . urlencode($tutorId) . "&updated_subjects=1");
            exit;
        }

        // =========================
        // 3. Add Finance Payment
        // =========================
        if (isset($_POST['update_finance'])) {
            $amount = floatval($_POST['amount']);
            $notes = trim($_POST['notes']);

            $stmt = $connect->prepare("INSERT INTO tutorpayments (TutorId, Amount, Notes, PaymentDate) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("ids", $tutorId, $amount, $notes);
            $stmt->execute();
            $stmt->close();

            $connect->commit();
            header("Location: updatetutors.php?id=" . urlencode($tutorId) . "&updated_finance=1");
            exit;
        }

    } catch (Exception $e) {
        $connect->rollback();
        $errorMessage = urlencode($e->getMessage());
        header("Location: updatetutors.php?id={$tutorId}&error={$errorMessage}");
        exit;
    }

} else {
    // Invalid access
    header("Location: updatetutors.php?id={$tutorId}");
    exit();
}
?>

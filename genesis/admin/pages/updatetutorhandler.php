<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


$tutorId = 0;

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

        // Delete existing subjects d
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

        header("Location: updatetutors.php?id=" . urlencode($tutorId) . "&updated=1");
        exit;

    } catch (Exception $e) {
        $connect->rollback();
        $errorMessage = addslashes($e->getMessage());
        header("Location: updatetutors.php?id=" . urlencode($errorMessage) . "&notupdated=1");
        exit;
        
    }
} else {
    header("Location: updatetutors.php?id={$tutorId}");
    exit();
}
?> 



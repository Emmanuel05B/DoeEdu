<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $learnerId = intval($_POST['learnerId'] ?? 0);
    $subjectId = intval($_POST['subjectId'] ?? 0);

    if ($learnerId && $subjectId) {

        // Get LearnerSubjectId
        $stmt = $connect->prepare("
            SELECT LearnerSubjectId 
            FROM learnersubject 
            WHERE LearnerId = ? AND SubjectId = ? AND Status = 'Active' 
            LIMIT 1
        ");
        $stmt->bind_param("ii", $learnerId, $subjectId);
        $stmt->execute();
        $stmt->bind_result($learnerSubjectId);
        $stmt->fetch();
        $stmt->close();

        if ($learnerSubjectId) {
            $connect->begin_transaction();
            try {
                // Step 1: Get SubjectID (you already have it, optional)
                // Step 2: Find ClassID for learner + subject
                $stmt = $connect->prepare("
                    SELECT lc.ClassID
                    FROM learnerclasses lc
                    INNER JOIN classes c ON lc.ClassID = c.ClassID
                    WHERE lc.LearnerID = ? AND c.SubjectID = ?
                    LIMIT 1
                ");
                $stmt->bind_param("ii", $learnerId, $subjectId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $classId = $row['ClassID'];

                    // Step 3: Decrement class count and set status 
                    $stmt2 = $connect->prepare("
                        UPDATE classes 
                        SET CurrentLearnerCount = CurrentLearnerCount - 1,
                            Status = 'Not Full'
                        WHERE ClassID = ?
                    ");
                    $stmt2->bind_param("i", $classId);
                    $stmt2->execute();
                    $stmt2->close();

                    // Step 4: Copy to history table
                    $stmtHist = $connect->prepare("
                        INSERT INTO learnerclasshistory (LearnerID, ClassID, SubjectId, GroupName, Reason)
                        SELECT lc.LearnerID, lc.ClassID, c.SubjectID, c.GroupName, 'Completed'
                        FROM learnerclasses lc
                        INNER JOIN classes c ON lc.ClassID = c.ClassID
                        WHERE lc.LearnerID = ? AND lc.ClassID = ?
                        LIMIT 1
                    ");
                    $stmtHist->bind_param("ii", $learnerId, $classId);
                    $stmtHist->execute();
                    $stmtHist->close();

                    // -----------------------------

                    // Step 5: Remove from learnerclasses ..
                    $stmt3 = $connect->prepare("DELETE FROM learnerclasses WHERE LearnerID = ? AND ClassID = ?");
                    $stmt3->bind_param("ii", $learnerId, $classId);
                    $stmt3->execute();
                    $stmt3->close();
                }
                $stmt->close();

                // Step 6: Update subject status
                $newStatus = "Completed";  
                $stmt = $connect->prepare("
                    UPDATE learnersubject
                    SET Status = ?
                    WHERE LearnerSubjectId = ? AND LearnerId = ?
                ");
                $stmt->bind_param("sii", $newStatus, $learnerSubjectId, $learnerId);
                $stmt->execute();
                $stmt->close();

                $connect->commit();
                $_SESSION['success'] = "Subject dropped successfully.";
            } catch (Exception $e) {
                $connect->rollback();
                $_SESSION['error'] = "Failed to drop subject: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Subject not found for this learner.";
        }
    }
}

// Redirect back to dashboard
header("Location: adminindex.php");
exit;

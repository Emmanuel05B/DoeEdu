<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$learnerId = intval($_POST['LearnerId'] ?? 0);
$action = $_POST['Action'] ?? '';

if (!$learnerId || !$action) {
    die("Invalid request.");
}

// --------------------
// 1. UPDATE OR DEREGISTER EXISTING SUBJECT
// --------------------

//ok, we have to stop using the combined learnerSubjectId. .. we have LearnerId and SubjectId
if (str_starts_with($action, "UpdateSubject_") || str_starts_with($action, "DeregisterSubject_")) {
    $isUpdate = str_starts_with($action, "UpdateSubject_");
    $isDrop   = str_starts_with($action, "DeregisterSubject_");

    $learnerSubjectId = intval(preg_replace("/^(UpdateSubject_|DeregisterSubject_)/", "", $action));
    $subData = $_POST['Subjects'][$learnerSubjectId] ?? [];

    if ($isUpdate && $subData) {
        // Use transaction
        $connect->begin_transaction();
        try {
            $stmt = $connect->prepare("
                UPDATE learnersubject 
                SET ContractStartDate=?, ContractExpiryDate=?, ContractFee=?, Status=? 
                WHERE LearnerSubjectId=? AND LearnerId=?
            ");
            $stmt->bind_param(
                "ssdssi",
                $subData['ContractStartDate'],
                $subData['ContractExpiryDate'],
                $subData['ContractFee'],
                $subData['Status'],
                $learnerSubjectId,
                $learnerId
            );
            $stmt->execute();
            $stmt->close();

            $connect->commit();
        } catch (Exception $e) {
            $connect->rollback();
            // handle error if needed
        }
    }

    if ($isDrop) {
        $connect->begin_transaction();
        try {
            // Remove subject
            $stmt = $connect->prepare("DELETE FROM learnersubject WHERE LearnerSubjectId=? AND LearnerId=?");
            $stmt->bind_param("ii", $learnerSubjectId, $learnerId);
            $stmt->execute();
            $stmt->close();

            /*/  remove learner from classes
             */

            // Step 1: Get the ClassID for this learner + subject
            $stmt = $connect->prepare("
                SELECT lc.ClassID 
                FROM learnerclasses lc
                INNER JOIN classes c ON lc.ClassID = c.ClassID
                INNER JOIN learnersubject ls ON c.SubjectID = ls.SubjectID
                WHERE lc.LearnerID = ? AND ls.LearnerSubjectId = ?
            ");
            $stmt->bind_param("ii", $learnerId, $learnerSubjectId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $classId = $row['ClassID'];

                // Step 2: Decrement CurrentLearnerCount
                $stmt2 = $connect->prepare("UPDATE classes SET CurrentLearnerCount = CurrentLearnerCount - 1 WHERE ClassID = ?");
                $stmt2->bind_param("i", $classId);
                $stmt2->execute();
                $stmt2->close();

                // Step 3: Delete learner from learnerclasses
                $stmt3 = $connect->prepare("DELETE FROM learnerclasses WHERE LearnerID = ? AND ClassID = ?");
                $stmt3->bind_param("ii", $learnerId, $classId);
                $stmt3->execute();
                $stmt3->close();
            }

            $stmt->close();


            //leaner is not yet fully deleted.. the class creation logic should go in reverse if needed.

            //Im thinking, we need GroupName and subjectId and +Grade Name to decrement CurrentLearnerCount in classes. 
            // ..then Get ClassId and go use it and Learner Id to delete from learnerclasses table

            // CurrentLearnerCount in classes has to be down by 1
            //and correclty delete this leaner for this subject from learnerclasses
            //Id    LearnerID   ClassID    AssignedAt

            $connect->commit();
        } catch (Exception $e) {
            $connect->rollback();
            // handle error if needed
        }
    }

    header("Location: updatelearner.php?id=$learnerId&updated=1");
    exit();
}


// --------------------
// 2. REGISTER NEW SUBJECT
// --------------------
if ($action === "RegisterNewSubject") {
    $newSub = $_POST['NewSubject'] ?? [];
    $subjectId = $newSub['SubjectId'] ?? 0;

    if ($subjectId) {
        // Check if learner is already registered for this subject
        $stmtCheck = $connect->prepare("
            SELECT COUNT(*) AS cnt 
            FROM learnersubject 
            WHERE LearnerId = ? AND SubjectId = ?
        ");
        $stmtCheck->bind_param("ii", $learnerId, $subjectId);
        $stmtCheck->execute();
        $res = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        if ($res['cnt'] > 0) {
            header("Location: updatelearner.php?id=$learnerId&error=already_registered");
            exit();
        }

        try {
            // Start transaction
            $connect->begin_transaction();

            // ------------------------
            // INSERT NEW SUBJECT
            // ------------------------
            $Status = 'Active';
            $gradeName = $newSub['GradeName'];

            $stmt = $connect->prepare("
                INSERT INTO learnersubject 
                (LearnerId, SubjectId, TargetLevel, CurrentLevel, ContractStartDate, ContractExpiryDate, ContractFee, Status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "iiisssds",
                $learnerId,
                $subjectId,
                $newSub['TargetLevel'],
                $newSub['CurrentLevel'],
                $newSub['ContractStartDate'],
                $newSub['ContractExpiryDate'],
                $newSub['ContractFee'],
                $Status
            );
            $stmt->execute();
            $stmt->close();

            // ------------------------
            // CLASS ASSIGNMENT
            // ------------------------
            $stmtSub = $connect->prepare("
                SELECT DefaultTutorId, MaxClassSize 
                FROM subjects WHERE SubjectId = ?
            ");
            $stmtSub->bind_param("i", $subjectId);
            $stmtSub->execute();
            $subRes = $stmtSub->get_result()->fetch_assoc();
            $stmtSub->close();

            $maxLearnersPerClass = $subRes['MaxClassSize'] ?? 5;
            $tutorId             = $subRes['DefaultTutorId'] ?? 25;

            $stmtClass = $connect->prepare("
                SELECT ClassID, CurrentLearnerCount 
                FROM classes 
                WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' 
                ORDER BY CreatedAt ASC 
                LIMIT 1
            ");
            $stmtClass->bind_param("ii", $subjectId, $gradeName);
            $stmtClass->execute();
            $resultClass = $stmtClass->get_result();

            if ($resultClass->num_rows > 0) {
                $class     = $resultClass->fetch_assoc();
                $classId   = (int)$class['ClassID'];
                $newCount  = ((int)$class['CurrentLearnerCount']) + 1;
                $classStat = ($newCount >= $maxLearnersPerClass) ? 'Full' : 'Not Full';

                $update = $connect->prepare("UPDATE classes SET CurrentLearnerCount = ?, Status = ? WHERE ClassID = ?");
                $update->bind_param("isi", $newCount, $classStat, $classId);
                $update->execute();
                $update->close();  
                    
            } else {
                $stmtGroup = $connect->prepare("
                    SELECT GroupName 
                    FROM classes 
                    WHERE SubjectID = ? AND Grade = ? 
                    ORDER BY GroupName DESC 
                    LIMIT 1
                ");
                $stmtGroup->bind_param("is", $subjectId, $gradeName);
                $stmtGroup->execute();
                $groupResult = $stmtGroup->get_result();

                if ($groupResult->num_rows > 0) {
                    $lastGroupName = $groupResult->fetch_assoc()['GroupName'];
                    $newGroupName = chr(ord($lastGroupName) + 1); // A → B → C, etc.
                } else {
                    $newGroupName = 'A';
                }
                $stmtGroup->close();

                $classStat = 'Not Full';
                $newCount  = 1;

                $insertClass = $connect->prepare("
                    INSERT INTO classes 
                        (SubjectID, Grade, GroupName, CurrentLearnerCount, TutorID, Status, CreatedAt) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, NOW())
                ");
                $insertClass->bind_param("ississ", $subjectId, $gradeName, $newGroupName, $newCount, $tutorId, $classStat);
                $insertClass->execute();
                $classId = $connect->insert_id;
                $insertClass->close();
            }

            $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
            $assign->bind_param("ii", $learnerId, $classId);
            $assign->execute();
            $assign->close();

            // ------------------------
            // FINANCES
            // ------------------------
            $stmtTotal = $connect->prepare("
                SELECT SUM(ContractFee - IFNULL(DiscountAmount,0)) AS TotalFees
                FROM learnersubject
                WHERE LearnerId = ?
            ");
            $stmtTotal->bind_param("i", $learnerId);
            $stmtTotal->execute();
            $resultTotal = $stmtTotal->get_result()->fetch_assoc();
            $stmtTotal->close();

            $totalFees = (float)$resultTotal['TotalFees'];

            $insertFin = $connect->prepare("
                INSERT INTO finances (LearnerId, TotalFees, TotalPaid, PaymentStatus, UpdatedAt) 
                VALUES (?, ?, 0, 'Unpaid', NOW())
                ON DUPLICATE KEY UPDATE TotalFees = VALUES(TotalFees)
            ");
            $insertFin->bind_param("id", $learnerId, $totalFees);
            $insertFin->execute();
            $insertFin->close();

            // Commit transaction
            $connect->commit();

        } catch (Exception $e) {
            $connect->rollback(); // Undo all changes
           
            $errMsg = urlencode($e->getMessage());
            header("Location: updatelearner.php?id=$learnerId&error=$errMsg");
            exit();
        }
    }

    header("Location: updatelearner.php?id=$learnerId&updated=1");
    exit();
}


// --------------------
// 3. UPDATE PERSONAL INFO
// --------------------
if ($action === "UpdatePersonalInfo") {
    $stmt = $connect->prepare("
        UPDATE users u
        LEFT JOIN learners l ON u.Id = l.LearnerId
        SET u.Name=?, u.Surname=?, u.Email=?, u.Contact=?,
            l.ParentName=?, l.ParentSurname=?, l.ParentEmail=?, l.ParentContactNumber=?
        WHERE u.Id=?
    ");
    $stmt->bind_param(
        "ssssssssi",
        $_POST['firstname'],
        $_POST['surname'],
        $_POST['email'],
        $_POST['contactnumber'],
        $_POST['parentfirstname'],
        $_POST['parentsurname'],
        $_POST['parentemail'],
        $_POST['parentcontactnumber'],
        $learnerId
    );
    $stmt->execute();
    $stmt->close();

    header("Location: updatelearner.php?id=$learnerId&updated=1");
    exit();
}

// --------------------
// UNKNOWN ACTION
// --------------------
die("Unknown action.");

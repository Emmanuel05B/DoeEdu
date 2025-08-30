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
// 1. UPDATE EXISTING SUBJECT
// --------------------
if (str_starts_with($action, "UpdateSubject_")) {
    $learnerSubjectId = intval(str_replace("UpdateSubject_", "", $action));   //shouldnt this just be SubjectId??
    $subData = $_POST['Subjects'][$learnerSubjectId] ?? [];

    if ($subData) {
        // Update contract info
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
            $subData['Status'],    //'Active','Suspended','Completed','Cancelled') DEFAULT  is 'Active'
            $learnerSubjectId,
            $learnerId
        );
        $stmt->execute();
        $stmt->close();

        // Handle optional Action
        $subjectAction = $subData['Action'] ?? '';     //Drop, Extend or Cut Short
        if ($subjectAction === "Deregister") {
            $connect->query("DELETE FROM learnersubject WHERE LearnerSubjectId=$learnerSubjectId AND LearnerId=$learnerId");
            // we might also wanna remove him from the classes as well just to ensure that...
        } elseif ($subjectAction === "Extend") {
            // Example: extend expiry by 1 month
            $connect->query("UPDATE learnersubject SET ContractExpiryDate = DATE_ADD(ContractExpiryDate, INTERVAL 1 MONTH) WHERE LearnerSubjectId=$learnerSubjectId");
        } elseif ($subjectAction === "CutShort") {
            $connect->query("UPDATE learnersubject SET ContractExpiryDate = CURDATE() WHERE LearnerSubjectId=$learnerSubjectId");
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
    if (!empty($newSub['SubjectId'])) {
        $stmt = $connect->prepare("
            INSERT INTO learnersubject 
            (LearnerId, SubjectId, TargetLevel, CurrentLevel, ContractStartDate, ContractExpiryDate, ContractFee) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iissd",
            $learnerId,
            $newSub['SubjectId'],
            $newSub['TargetLevel'],
            $newSub['CurrentLevel'],
            $newSub['ContractStartDate'],
            $newSub['ContractExpiryDate'],
            $newSub['ContractFee']
        );
        $stmt->execute();
        $stmt->close();
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

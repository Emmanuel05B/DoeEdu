<?php
include_once(__DIR__ . "/../../partials/paths.php"); 

include_once(BASE_PATH . "/partials/session_init.php"); 

include_once(BASE_PATH . "/partials/connect.php"); 

if (!isset($_GET['token'])) {
    die("Invalid verification link.");
}

$token = $_GET['token'];

// Fetch user by token
$stmt = $connect->prepare("SELECT Id, IsVerified FROM users WHERE VerificationToken = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("This verification link is invalid or expired.");
}

$user = $result->fetch_assoc();
$learnerId = $user['Id'];

if ($user['IsVerified']) {
    header("Location: parent_verification_success.php?learner_id=" . $learnerId);
    exit();
}

// Start transaction
$connect->begin_transaction();

try {
    // VERIFY USER
    $update = $connect->prepare("UPDATE users SET IsVerified = 1, VerificationToken = NULL WHERE Id = ?");
    $update->bind_param("i", $learnerId);
    $update->execute();
    $update->close();

    // FETCH GRADE NAME
    $stmtGrade = $connect->prepare("SELECT Grade FROM learners WHERE LearnerId = ?");
    $stmtGrade->bind_param("i", $learnerId);
    $stmtGrade->execute();
    $resGrade = $stmtGrade->get_result();

    if ($resGrade->num_rows === 0) {
        throw new Exception("Learner grade not found.");
    }

    $gradeRow = $resGrade->fetch_assoc();
    $gradeName = $gradeRow['Grade'];
    $stmtGrade->close();

    // FETCH SUBJECT IDS
    $stmtSub = $connect->prepare("SELECT SubjectId FROM learnersubject WHERE LearnerId = ?");
    $stmtSub->bind_param("i", $learnerId);
    $stmtSub->execute();
    $resSub = $stmtSub->get_result();

    if ($resSub->num_rows === 0) {
        throw new Exception("No subjects found for this learner.");
    }

    $subjectIds = [];
    while ($row = $resSub->fetch_assoc()) {
        $subjectIds[] = $row['SubjectId'];
    }
    $stmtSub->close();

    // CLASS ASSIGNMENTS
    foreach ($subjectIds as $sid) {
        // Get subject defaults
        $stmtSub = $connect->prepare("
            SELECT ThreeMonthsPrice, SixMonthsPrice, TwelveMonthsPrice, DefaultTutorId, MaxClassSize 
            FROM subjects WHERE SubjectId = ?
        ");
        $stmtSub->bind_param("i", $sid);
        $stmtSub->execute();
        $subRes = $stmtSub->get_result()->fetch_assoc();
        $stmtSub->close();

        // Default values if missing
        $maxLearnersPerClass = $subRes['MaxClassSize'] ?? 5;
        $tutorId = $subRes['DefaultTutorId'] ?? 3;
        

        // Try find existing available class
        $stmtClass = $connect->prepare("
            SELECT ClassID, CurrentLearnerCount 
            FROM classes 
            WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' 
            ORDER BY CreatedAt ASC 
            LIMIT 1
        ");
        $stmtClass->bind_param("is", $sid, $gradeName);
        $stmtClass->execute();
        $resultClass = $stmtClass->get_result();

        if ($resultClass->num_rows > 0) {
            $class = $resultClass->fetch_assoc();
            $classId = (int)$class['ClassID'];
            $newCount = ((int)$class['CurrentLearnerCount']) + 1;
            $classStat = ($newCount >= $maxLearnersPerClass) ? 'Full' : 'Not Full';

            $updateClass = $connect->prepare("UPDATE classes SET CurrentLearnerCount = ?, Status = ? WHERE ClassID = ?");
            $updateClass->bind_param("isi", $newCount, $classStat, $classId);
            $updateClass->execute();
            $updateClass->close();
        } else {
            // Create new group
            $stmtGroup = $connect->prepare("
                SELECT GroupName 
                FROM classes 
                WHERE SubjectID = ? AND Grade = ? 
                ORDER BY GroupName DESC 
                LIMIT 1
            ");
            $stmtGroup->bind_param("is", $sid, $gradeName);
            $stmtGroup->execute();
            $groupResult = $stmtGroup->get_result();

            if ($groupResult->num_rows > 0) {
                $lastGroupName = $groupResult->fetch_assoc()['GroupName'];
                $newGroupName = chr(ord($lastGroupName) + 1);
            } else {
                $newGroupName = 'A';
            }
            $stmtGroup->close();

            $classStat = 'Not Full';
            $newCount = 1;

            $insertClass = $connect->prepare("
                INSERT INTO classes (SubjectID, Grade, GroupName, CurrentLearnerCount, TutorID, Status, CreatedAt)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $insertClass->bind_param("ississ", $sid, $gradeName, $newGroupName, $newCount, $tutorId, $classStat);
            $insertClass->execute();
            $classId = $connect->insert_id;
            $insertClass->close();
        }

        // Assign learner to class
        $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
        $assign->bind_param("ii", $learnerId, $classId);
        $assign->execute();
        $assign->close();
    }

    // FINANCES
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


    $connect->commit();

    header("Location: parent_verification_success.php?learner_id=" . $learnerId);
    exit();

} catch (Exception $e) {
    // ROLLBACK ON ANY ERROR
    $connect->rollback();
    die("An error occurred during verification: " . $e->getMessage());
}
?>

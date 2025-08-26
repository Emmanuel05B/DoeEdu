<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Transaction start
$connect->begin_transaction();

try {
    // Collect learner details
    $name      = $_POST['name'];
    $surname   = $_POST['surname'];

    $email     = $_POST['email'];
    $contact   = $_POST['contactnumber'];
    $learnertitle    = $_POST['learnertitle'];
    $grade     = (int)$_POST['grade'];   //this is grade ID, i need grade name
   
    
    $stmtGrade = $connect->prepare("SELECT GradeName FROM grades WHERE GradeId = ?");
    $stmtGrade->bind_param("i", $grade);
    $stmtGrade->execute();
    $result = $stmtGrade->get_result();

    if($row = $result->fetch_assoc()){
        $gradeName = $row['GradeName'];
    } else {
        $gradeName = "Unknown";  // fallback
    }

    $stmtGrade->close();


    $nockouttime       = $_POST['knockout_time'];

    // Parent details
    $pname     = $_POST['parentname'];
    $psurname  = $_POST['parentsurname'];
    $pcontact  = $_POST['parentcontact'];
    $pemail    = $_POST['parentemail'];
    $ptitle   = $_POST['parenttitle'];

    $password = $_POST['password'];  //from the hidden field

    $verificationToken = bin2hex(random_bytes(32));
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into users table
    $insertUser = $connect->prepare("
        INSERT INTO users (Surname, Name, UserPassword, Gender, Contact, Email, IsVerified, VerificationToken, RegistrationDate, UserType) 
        VALUES (?, ?, ?, ?, ?, ?, 0, ?, Now(), 2)");
    $insertUser->bind_param("ssssiss", $surname, $name, $hashedPassword, $learnertitle, $contact, $email, $verificationToken);
    $insertUser->execute();
    $learnerId = $connect->insert_id;
    $insertUser->close();

    // Insert extra learner data into learners table
    
    //even here, inser grade name not gradeId
    $insertLearner = $connect->prepare("
        INSERT INTO learners (LearnerId, Grade, LearnerKnockoffTime, ParentTitle, ParentName, ParentSurname, ParentEmail, ParentContactNumber) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insertLearner->bind_param("isssssss", $learnerId, $gradeName, $nockouttime, $ptitle, $pname, $psurname, $pemail, $pcontact);
    $insertLearner->execute();
    $insertLearner->close();

    // SUBJECTS
    foreach ($_POST['SubjectID'] as $i => $sid) {    //SubjectID  from the form
        $sid = (int)$sid;
        $duration = $_POST['Duration'][$i];
        $currentLevel = $_POST['CurrentLevel'][$i];
        $targetLevel  = $_POST['TargetLevel'][$i];

        $stmtSub = $connect->prepare("
            SELECT ThreeMonthsPrice, SixMonthsPrice, TwelveMonthsPrice, DefaultTutorId, MaxClassSize 
            FROM subjects WHERE SubjectId = ?
        ");
        $stmtSub->bind_param("i", $sid);
        $stmtSub->execute();
        $subRes = $stmtSub->get_result()->fetch_assoc();
        $stmtSub->close();

        $price = (float)$duration;
        if ($price == $subRes['ThreeMonthsPrice']) $months = 3;
        elseif ($price == $subRes['SixMonthsPrice']) $months = 6;
        elseif ($price == $subRes['TwelveMonthsPrice']) $months = 12;
        else $months = 0;

        $contractFee = $price;     
        $startDate = new DateTime();
        $endDate = clone $startDate;
        $endDate->modify("+".($months*30)." days");

        $DiscountAmount = NULL;
        $Status = 'Active';

        $contractStartDate = $startDate->format('Y-m-d');       
        $contractExpiryDate = $endDate->format('Y-m-d'); // make sure $endDate is a DateTime object


        $insertLS = $connect->prepare("
            INSERT INTO learnersubject 
                (LearnerId, SubjectId, NumberOfTerms, TargetLevel, CurrentLevel, ContractStartDate, ContractExpiryDate, ContractFee, DiscountAmount, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
       
        $insertLS->bind_param("iiissssdds", $learnerId, $sid, $months, $targetLevel, $currentLevel, $contractStartDate, $contractExpiryDate, $contractFee, $DiscountAmount, $Status);
        $insertLS->execute();
        $insertLS->close();

        // -------------------
        // CLASS ASSIGNMENT
        // -------------------

        // I should use the grade Id, to get the grade Name fro grades

        $maxLearnersPerClass = $subRes['MaxClassSize'] ?? 5;
        $tutorId             = $subRes['DefaultTutorId'] ?? 25;

        $stmtClass = $connect->prepare("
            SELECT ClassID, CurrentLearnerCount 
            FROM classes 
            WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' 
            ORDER BY CreatedAt ASC 
            LIMIT 1
        ");
        $stmtClass->bind_param("ii", $sid, $grade);
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
            $stmtGroup->bind_param("ii", $sid, $grade);
            $stmtGroup->execute();
            $groupResult = $stmtGroup->get_result();

            if ($groupResult->num_rows > 0) {
                $last = $groupResult->fetch_assoc()['GroupName'];
                $last = strtoupper(trim($last));
                if ($last === '' || preg_match('/[^A-Z]/', $last)) {
                    $newGroupName = 'A';
                } else {
                    $carry = 1;
                    $chars = str_split(strrev($last));
                    for ($j = 0; $j < count($chars); $j++) {
                        $n = ord($chars[$j]) - 65 + $carry;
                        if ($n >= 26) { $chars[$j] = 'A'; $carry = 1; }
                        else { $chars[$j] = chr(65 + $n); $carry = 0; break; }
                    }
                    if ($carry === 1) $chars[] = 'A';
                    $newGroupName = strrev(implode('', $chars));
                }
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
            $insertClass->bind_param("ississ", $sid, $gradeName, $newGroupName, $newCount, $tutorId, $classStat);
            $insertClass->execute();
            $classId = $connect->insert_id;
            $insertClass->close();
        }

        $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
        $assign->bind_param("ii", $learnerId, $classId);
        $assign->execute();
        $assign->close();
    } // End of subjects loop

    // -------------------
    // FINANCES (single row per learner)
    // -------------------
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

    $_SESSION['success'] = "Learner registered successfully!";
    header("Location: addlearners.php");
    exit();

} catch (Exception $e) {
    $connect->rollback();
    $_SESSION['error'] = "Error registering learner: " . $e->getMessage();
    header("Location: addlearners.php");
    exit();
}

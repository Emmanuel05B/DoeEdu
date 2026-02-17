<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

include_once(BASE_PATH . "/partials/connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../../vendor/autoload.php';

// Transaction start
$connect->begin_transaction();

try {
    // Collect learner details
    $name      = $_POST['name'];
    $surname   = $_POST['surname'];
    $email     = $_POST['email'];////
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


    $nockouttime = $_POST['knockout_time'];

    // Parent details
    $pname     = $_POST['parentname'];
    $psurname  = $_POST['parentsurname'];
    $pcontact  = $_POST['parentcontact'];
    $pemail    = $_POST['parentemail'];
    $ptitle   = $_POST['parenttitle'];


    $password = trim($_POST["new_password"]);
    $confirmPassword = trim($_POST["confirm_password"]);
    $token = $_POST["invite_token"];

    $errors = [];
    // Basic validation
    if ($password !== $confirmPassword) {
        $errors[] = "The passwords do not match";
    }

    // Additional password rules
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(strlen($password) < 8 || !$uppercase || !$lowercase || !$number || !$specialChars) {
       $_SESSION['error'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        header('Location: registration.php?token=' . $token);
        exit();
    }


    

    // Validate required fields
    if (empty($email) || empty($pemail) || empty($grade) || empty($name) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if the learner already exists
      $check = $connect->prepare("SELECT Id FROM users WHERE Email = ?");
      $check->bind_param("s", $email);
      $check->execute();
      $check->store_result();
      if ($check->num_rows > 0) {
         
            $_SESSION['error'] = "This email has already Registered, use a different email. ";
            header('Location: registration.php?token=' . $token);
            exit();
      }
      $check->close();

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

    foreach ($_POST['SubjectID'] as $i => $sid) {
        $sid = (int)$sid;

        // Making sure Duration exists for this index
        $duration = isset($_POST['Duration'][$i]) ? (int)$_POST['Duration'][$i] : 0;

        if($duration > 0) {  // only proceed if duration is valid
            $currentLevel = isset($_POST['CurrentLevel'][$i]) ? $_POST['CurrentLevel'][$i] : '';
            $targetLevel  = isset($_POST['TargetLevel'][$i]) ? $_POST['TargetLevel'][$i] : '';

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

            // CLASS ASSIGNMENT
        }
    }


    // Commit transaction
    sendEmailToLearner($email, $name, $verificationToken);
    
    sendEmailToParent($pemail, $pname, $name, $verificationToken, $connect, $learnerId);
    $connect->commit();

    $_SESSION['success'] = "Registered successfully! One final step — a verification email has been sent to the parent. You’ll be able to log in once they approve.";
    header('Location: registration.php?token=' . $token);
    exit();

} catch (Exception $e) {
    $connect->rollback();
    $_SESSION['error'] = "Error registering learner: " . $e->getMessage();
    header('Location: registration.php?token=' . $token);

    exit();
}


// Send email to parent

function sendEmailToParent($pemail, $pname, $name, $verificationToken, $connect, $learnerId) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_ADDRESS'];
        $mail->Password = $_ENV['EMAIL_APP_PASSWORD'];   // update with correct password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'DoE_Genesis');
        $mail->addAddress($pemail, $pname);
        $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'DoEGenesis');

        // Fetch learner subjects and fees
        $stmt = $connect->prepare("
            SELECT s.SubjectName, ls.NumberOfTerms, ls.ContractFee
            FROM learnersubject ls
            JOIN subjects s ON ls.SubjectId = s.SubjectId
            WHERE ls.LearnerId = ?
        ");
        $stmt->bind_param("i", $learnerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $subjectRows = "";
        $totalFees = 0;
        while ($row = $result->fetch_assoc()) {
            $subjectRows .= "<tr>
                                <td>{$row['SubjectName']}</td>
                                <td>{$row['NumberOfTerms']} months</td>
                                <td>R {$row['ContractFee']}</td>
                             </tr>";
            $totalFees += (float)$row['ContractFee'];
        }
        $stmt->close();

        $mail->isHTML(true);
        $mail->Subject = 'Your Child Registration & Fees Approval - DoE';

        $mail->Body = "
        <p>Dear $pname,</p>
        <p>Your child <strong>$name</strong> has been successfully registered with the Distributors of Education.</p>

        <p>Please review the subjects and fees below. By verifying, you acknowledge awareness of the costs and approve your child's registration:</p>

        <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Duration</th>
                    <th>Fee</th>
                </tr>
            </thead>
            <tbody>
                $subjectRows
                <tr>
                    <td colspan='2'><strong>Total</strong></td>
                    <td><strong>R $totalFees</strong></td>
                </tr>
            </tbody>
        </table>

        <p style='text-align:center; margin:20px 0;'>
            <a href='" . COMMON_URL . "/verification.php?token=$verificationToken'
               style='background-color: #008CBA; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
               Verify & Approve Registration
            </a>
        </p>

        <p>Once verified, you will receive updates on your child's progress, upcoming sessions, and announcements.</p>
        <p>If you did not expect this email or the link does not work, please contact us so we can assist you.</p>

        <br>
        <p>Warm regards,</p>
        <p><strong>DoE Team</strong></p>
        ";

        $mail->send();

    } catch (Exception $e) {
        // Optional: log error or handle failure
    }
}


// Send email to learner with info telling them to let their parents to verify them. ese they wont be able to login.

function sendEmailToLearner($email, $name, $verificationToken) {
  $mail = new PHPMailer(true);
  try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = $_ENV['EMAIL_ADDRESS'];
      $mail->Password = $_ENV['EMAIL_APP_PASSWORD']; 
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;

      $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'DoE_Genesis');
      $mail->addAddress($email, $name);
      $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'DoEGenesis');

      $mail->isHTML(true);
      $mail->Subject = 'Welcome to DoE';
      $mail->Body = "
      <p>Dear $name,</p>
      <p>🎉 Welcome to the Distributors of Education! Your registration was successful.</p>
      <p>
        There’s just <strong>one final step</strong> ✅ — your guardian needs to approve your account.  
        They have received an email with a verification link sent to the address you provided during registration.  
        Once they complete it, you’ll be able to log in and start attending classes 📚.
      </p>
    
      <p>
        If your guardian doesn’t receive the email, please contact us and provide the correct parent/guardian email address  
        so that we can resend the approval link.
      </p>
      <p>If you have any questions, feel free to contact us.</p>
      <br><p>Best regards,</p><p><strong>DoE Team</strong></p>";

      $mail->send();


      

  } catch (Exception $e) {

    $_SESSION['error'] = "Email Send Failed " . $e->getMessage();
    header('Location: registration.php?token=' . $token);

    exit();

  }
}



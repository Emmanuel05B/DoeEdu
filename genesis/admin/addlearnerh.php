<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<?php
session_start();

// Redirect if session 'email' is not set (user not logged in)
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');
include("adminpartials/head.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../vendor/autoload.php';

// Initialize error handling f
$errors = [];

$userId = $_SESSION['user_id'];

// Handle form submission for parent details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Learner info from POST
  $learner_name = $_POST['name'];
  $learner_surname = $_POST['surname'];
  $learner_email = $_POST['email'];
  $learner_contactnumber = $_POST['contactnumber'];
  $learner_grade = $_POST['grade'];
  $learner_knockout_time = $_POST['knockout_time'];  // Kept as is for other purposes
  $LearnerTitle = $_POST['learnertitle'];


  $password = $_POST['password'];

  // Parent info from POST
  $parent_name = trim($_POST['parentname']);
  $parent_surname = trim($_POST['parentsurname']);
  $parent_email = $_POST['parentemail'];
  $parent_contactnumber = $_POST['parentcontact'];
  $parent_title = $_POST['parenttitle'];

  // Subject prices which automatically determines the number of terms
  $maths = $_POST['maths'];
  $physics = $_POST['physics'];

  // Subject levels
  $mathsCurrent = $_POST['math-current'];
  $mathsTarget = $_POST['math-target'];
  $physicsCurrent = $_POST['physics-current'];
  $physicsTarget = $_POST['physics-target'];

  // Validate required fields
  if (empty($parent_name) || empty($parent_email) || empty($parent_contactnumber) || empty($parent_surname)) {
    $errors[] = "All fields are required.";
  }

  // Validate email format
  if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Begin database transaction
  $connect->begin_transaction();

  if (empty($errors)) {
    try {
      // Check if the learner already exists
      $check = $connect->prepare("SELECT Id FROM users WHERE Email = ?");
      $check->bind_param("s", $learner_email);
      $check->execute();
      $check->store_result();
      if ($check->num_rows > 0) {
         
          echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Learner Exists</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Learner Exists',
                    text: 'A learner with this email already exists.',
                    confirmButtonText: 'Go Back'
                }).then(() => {
                    window.location = 'addlearners.php'; 
                });
            </script>
            </body>
            </html>";
            exit;
      }
      $check->close();

      
      // If learner does not exist, insert learner data
     
        $total_fees = $maths + $physics; // Calculate total fees

        // Adjust fees based on conditions..
        if($total_fees == 900.00){
          $total_fees = 850.00;
        }else if($total_fees == 1500.00){
          $total_fees = 1250.00;
        }else if($total_fees == 2398.00){
          $total_fees = 1950.00;
        } else {
          // Default case if none of the statuses match
          $total_fees = $total_fees;
        }

        $total_paid = 0; // Set default value for now
        $total_owe = $total_fees;

        //first insert in to the users, then to learner, then finsnces, then 
        $verificationToken = bin2hex(random_bytes(32));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        

        //-------------------------------- insert into users table first------------------------------------

        $stmt = $connect->prepare("INSERT INTO users (Surname, Name, UserPassword, Gender, Contact, Email, 
        IsVerified, VerificationToken, RegistrationDate, UserType) 
        VALUES (?, ?, ?, ?, ?, ?, 0, ?, Now(), 2)");

        $stmt->bind_param("ssssiss",
        $learner_surname,$learner_name,$hashedPassword,$LearnerTitle,$learner_contactnumber,$learner_email,$verificationToken);

        if ($stmt->execute()) {
          $LearnerIDFromUsers = $connect->insert_id; // Get the learner ID after insertion
          $stmt->close();
        }else{
            // could not save the learner in to users
            echo "<script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Registration Failed',
                      text: 'Could not save learner in users table.',
                      confirmButtonText: 'OK'
                  }).then(() => {
                      window.location = 'addlearners.php'; 
                  });
                </script>";
            exit;
        }

        //---------------------------------insert into learners table secondly ------------------------------------
        $stmt = $connect->prepare("INSERT INTO learners (
            LearnerId, Grade, RegistrationDate, LearnerKnockoffTime, 
            Math, Physics, TotalFees, TotalPaid, TotalOwe, 
            ParentTitle, ParentName, ParentSurname, ParentEmail, 
            ParentContactNumber
        ) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        /*
        if (!$stmt) {
            die("Prepare failed: " . $connect->error);
        }  */
        $stmt->bind_param("isddddsssssss", 
            $LearnerIDFromUsers, $learner_grade, $learner_knockout_time, $maths, 
            $physics, $total_fees, $total_paid, $total_owe, $parent_title, $parent_name, $parent_surname, 
            $parent_email, $parent_contactnumber);

        if ($stmt->execute()) {
          $learner_id = $connect->insert_id; // Get the learner ID after insertion
          $stmt->close();

          //*************************** Insert into finances table************************** */
          
          $total_fees = $maths + $physics; // Calculate total fees
          $total_paid = 0; // Set default value for now

          //third table to insert into..... wait
          $stmt = $connect->prepare("INSERT INTO finances (LearnerId, Grade, TotalFees, TotalPaid, Math, Physics) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("iiiddd", $learner_id, $learner_grade, $total_fees, $total_paid, $maths, $physics);
          $stmt->execute();
          $stmt->close();

          //************************************************************************************ */

          //-------------------------// Insert subjects (Math and Physics)--------------------------------------
          
          function calculateContractExpiry($fee, $registration_date) {
            $number_of_terms = 0;

            switch ($fee) {
                case 450.00:
                    $registration_date->modify('+3 months');
                    $number_of_terms = 1;
                    break;
                case 750.00:
                    $registration_date->modify('+6 months');
                    $number_of_terms = 2;
                    break;
                case 1199.00:
                    $registration_date->modify('+1 year');
                    $number_of_terms = 3;
                    break;
                default:
                    $registration_date = null;
                    break;
            }

            // Return the expiry date, number of terms, and status
            if ($registration_date) {
                $contract_expiry_date = $registration_date->format('Y-m-d H:i:s');
                $status = 'Active';
            } else {
                $contract_expiry_date = null;
                $status = 'Not Active';
            }

            return [$contract_expiry_date, $status, $number_of_terms];
          }

          // For Maths
          if ($maths != 0) {

            // Value of $subject_id for maths depends on $learner_grade
            if($learner_grade == 12){
              $subject_id = 3;
            } else if ($learner_grade == 11) {
              $subject_id = 2;
            }else if ($learner_grade == 10) {
              $subject_id = 1;
            } else {
              echo '<h1>Grade does not exist</h1>';
            }

            $registration_date = new DateTime();  // Current date
            list($contract_expiry_date, $status, $number_of_terms) = calculateContractExpiry($maths, $registration_date);

            // Insert into LearnersSubject table for maths
            $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, 
            NumberOfTerms, ContractExpiryDate, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt2->bind_param("iiiiiss", 
            $learner_id, $subject_id, $mathsTarget, $mathsCurrent, $number_of_terms, $contract_expiry_date, $status);
            $stmt2->execute();
            $stmt2->close();

            // --------------------- CLASS ASSIGNMENT LOGIC -----------------------

            $maxLearnersPerClass = 15;

            // First, try to find an existing available class
            $stmtClass = $connect->prepare("SELECT ClassID, CurrentLearnerCount FROM classes 
              WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' ORDER BY CreatedAt ASC LIMIT 1");

            $stmtClass->bind_param("ii", $subject_id, $learner_grade);
            $stmtClass->execute();
            $result = $stmtClass->get_result();

            if ($result->num_rows > 0) {
                $class = $result->fetch_assoc();
                $classId = $class['ClassID'];
                $currentCount = $class['CurrentLearnerCount'] + 1;

                // Update class with new count and possibly status
                $status = ($currentCount >= $maxLearnersPerClass) ? 'Full' : 'Not Full';
                $update = $connect->prepare("UPDATE classes SET CurrentLearnerCount = ?, Status = ? WHERE ClassID = ?");
                $update->bind_param("isi", $currentCount, $status, $classId);
                $update->execute();
                $update->close();

            } else {
                // No available(Not Full) class — create a new one
                $groupQuery = $connect->prepare("SELECT GroupName FROM classes 
                    WHERE SubjectID = ? AND Grade = ? ORDER BY GroupName DESC LIMIT 1");
                $groupQuery->bind_param("ii", $subject_id, $learner_grade);
                $groupQuery->execute();
                $groupResult = $groupQuery->get_result();
                
                if ($groupResult->num_rows > 0) {
                    $lastGroupName = $groupResult->fetch_assoc()['GroupName'];
                    $newGroupName = chr(ord($lastGroupName) + 1); // A → B → C, etc.
                } else {
                    $newGroupName = 'A'; // First class
                }

                $status = 'Not Full';
                $currentCount = 1;
                $tutorId = 25; // Optional: fetch based on subject-grade-tutor link if you want

                $insertClass = $connect->prepare("INSERT INTO classes 
                  (SubjectID, Grade, GroupName, CurrentLearnerCount, TutorID, Status, CreatedAt)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())");

                $insertClass->bind_param("iisiss", $subject_id, $learner_grade, $newGroupName, $currentCount, $tutorId, $status);
                $insertClass->execute();
                $classId = $connect->insert_id;
                $insertClass->close();
            }

            $stmtClass->close();

            // ---------- Link learner to this class ----------
            $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
            $assign->bind_param("ii", $learner_id, $classId);
            $assign->execute();
            $assign->close();

          }

          // For Physics
          if ($physics != 0) {

            // Value of $subject_id for physics depends on $learner_grade
            if($learner_grade == 12){
              $subject_id = 6;
            } else if ($learner_grade == 11) {
              $subject_id = 5;
            }else if ($learner_grade == 10) {
              $subject_id = 4;
            } else {
              echo '<h1>Grade does not exist</h1>';
            }

            $registration_date = new DateTime();  // Current date
            list($contract_expiry_date, $status, $number_of_terms) = calculateContractExpiry($physics, $registration_date);

            // Insert into LearnersSubject table for physics
            $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, 
            NumberOfTerms, ContractExpiryDate, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt2->bind_param("iiiiiss", 
            $learner_id, $subject_id, $physicsTarget, $physicsCurrent, $number_of_terms, $contract_expiry_date, $status);
            $stmt2->execute();
            $stmt2->close();


            // --------------------- CLASS ASSIGNMENT LOGIC -----------------------

            $maxLearnersPerClass = 15;

            // First, try to find an existing available class
            $stmtClass = $connect->prepare("SELECT ClassID, CurrentLearnerCount FROM classes 
              WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' ORDER BY CreatedAt ASC LIMIT 1");

            $stmtClass->bind_param("ii", $subject_id, $learner_grade);
            $stmtClass->execute();
            $result = $stmtClass->get_result();

            if ($result->num_rows > 0) {
                $class = $result->fetch_assoc();
                $classId = $class['ClassID'];
                $currentCount = $class['CurrentLearnerCount'] + 1;

                // Update class with new count and possibly status
                $status = ($currentCount >= $maxLearnersPerClass) ? 'Full' : 'Not Full';
                $update = $connect->prepare("UPDATE classes SET CurrentLearnerCount = ?, Status = ? WHERE ClassID = ?");
                $update->bind_param("isi", $currentCount, $status, $classId);
                $update->execute();
                $update->close();

            } else {
                // No available class — create a new one
                $groupQuery = $connect->prepare("SELECT GroupName FROM classes 
                    WHERE SubjectID = ? AND Grade = ? ORDER BY GroupName DESC LIMIT 1");
                $groupQuery->bind_param("ii", $subject_id, $learner_grade);
                $groupQuery->execute();
                $groupResult = $groupQuery->get_result();
                
                if ($groupResult->num_rows > 0) {
                    $lastGroupName = $groupResult->fetch_assoc()['GroupName'];
                    $newGroupName = chr(ord($lastGroupName) + 1); // A → B → C, etc.
                } else {
                    $newGroupName = 'A'; // First class
                }

                $status = 'Not Full';
                $currentCount = 1;
                $tutorId = 25; // Optional: fetch based on subject-grade-tutor link if you want

                $insertClass = $connect->prepare("INSERT INTO classes 
                  (SubjectID, Grade, GroupName, CurrentLearnerCount, TutorID, Status, CreatedAt)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())");

                $insertClass->bind_param("iisiss", $subject_id, $learner_grade, $newGroupName, $currentCount, $tutorId, $status);
                $insertClass->execute();
                $classId = $connect->insert_id;
                $insertClass->close();
            }

            $stmtClass->close();

            // ---------- Link learner to this class ----------
            $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
            $assign->bind_param("ii", $learner_id, $classId);
            $assign->execute();
            $assign->close();



          }
          //-----------------------------------------------------------------------------------------------------

          $connect->commit(); // Commit the transaction

            // Send confirmation emails
          //sendEmailToParent($parent_email, $parent_name, $learner_name);
          sendEmailToLearner($learner_email, $learner_name, $verificationToken);


        } else {
          $connect->rollback();
          echo '<!DOCTYPE html>
            <html>
            <head>
              <title>Registration Failed</title>
              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
            <script>
              Swal.fire({
                  icon: "error",
                  title: "Registration Failed",
                  text: "Learner data could not be inserted.",
                  confirmButtonColor: "#d33",
                  confirmButtonText: "OK"
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "addlearners.php";
                  }
              });
            </script>
            </body>
            </html>';
            exit();

        }
      

    } catch (Exception $e) {
      // Rollback on any error
      $connect->rollback();

          echo '<!DOCTYPE html>
            <html>
            <head>
              <title>Registration Failed</title>
              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
            <script>
              Swal.fire({
                  icon: "error",
                  title: "Registration Failed",
                  text: "An error occurred during registration. ' . addslashes($e->getMessage()) . '",
                  confirmButtonColor: "#d33",
                  confirmButtonText: "OK"
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "addlearners.php";
                  }
              });
            </script>
            </body>
            </html>';

    }
  }
}


/*/ Send email to parent
function sendEmailToParent($parent_email, $parent_name, $learner_name) {
  $mail = new PHPMailer(true);
  try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'thedistributorsofedu@gmail.com';
      $mail->Password = 'bxuxtebkzbibtvej';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;

      $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
      $mail->addAddress($parent_email, $parent_name);
      $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis');

      $mail->isHTML(true);
      $mail->Subject = 'Your Child is Registered with DoE';
      $mail->Body = "
      <p>Dear $parent_name,</p>
      <p>Your child <strong>$learner_name</strong> has been successfully registered with the Distributors of Education.</p>
      <p>You will be updated with progress reports, announcements, and upcoming sessions.</p>
      <p>Thank you for choosing us to support your child's learning journey.</p>
      <br><p>Warm regards,</p><p><strong>DoE Team</strong></p>";

      $mail->send();
  } catch (Exception $e) {
      // You could log errors if needed
  }
}
*/

// Send email to learner with verification link
function sendEmailToLearner($learner_email, $learner_name, $verificationToken) {
  $mail = new PHPMailer(true);
  try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'thedistributorsofedu@gmail.com';
      $mail->Password = 'bxuxtebkzbibtvej';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;

      $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
      $mail->addAddress($learner_email, $learner_name);
      $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis');

      $mail->isHTML(true);
      $mail->Subject = 'Welcome to DoE - Please Verify Your Email';
      $mail->Body = "
      <p>Dear $learner_name,</p>
      <p>Welcome to the Distributors of Education! You have been successfully registered.</p>
      <p>Please verify your email address to activate your account:</p>
      <a href='http://localhost/DoeEdu/genesis/common/verification.php?token=$verificationToken' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Email</a>
      <p>If you have any questions, feel free to contact us.</p>
      <br><p>Best regards,</p><p><strong>DoE Team</strong></p>";

      if ($mail->send()) {

        echo '<!DOCTYPE html>
          <html>
          <head>
            <title>Success</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          </head>
          <body>
          <script>
            Swal.fire({
                icon: "success",
                title: "Registration Successful",
                text: "Emails sent to both parent and learner.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "addlearners.php";
                }
            });
          </script>
          </body>
          </html>';

          exit;

      }

  } catch (Exception $e) {

    echo '<!DOCTYPE html>
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <title>Email Send Failed</title>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      </head>
      <body>
          <script>
              document.addEventListener("DOMContentLoaded", function () {
                  Swal.fire({
                      icon: "error",
                      title: "Email Send Failed",
                      text: "There was an issue sending the email to learner.",
                      confirmButtonText: "OK"
                  }).then(function () {
                      window.location.href = "addlearners.php";
                  });
              });
          </script>
      </body>
      </html>';
      exit;

  }
}



?>

<div class="wrapper"></div>

</body>
</html>

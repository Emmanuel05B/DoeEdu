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

// Initialize error handling
$errors = [];

// Handle form submission for parent details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Learner info from POST
  $learner_name = $_POST['name'];
  $learner_surname = $_POST['surname'];
  $learner_email = $_POST['email'];
  $learner_contactnumber = $_POST['contactnumber'];
  $learner_grade = $_POST['grade'];
  $learner_knockout_time = $_POST['knockout_time'];  // Kept as is for other purposes
  
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
      // Check if the parent already exists
      $stmt = $connect->prepare("SELECT ParentId FROM parents WHERE ParentEmail = ?");
      $stmt->bind_param("s", $parent_email);
      $stmt->execute();
      $stmt->bind_result($parent_id);
      $stmt->fetch();
      $stmt->close();

      // If parent does not exist, insert parent data
      if (!$parent_id) {
        $stmt = $connect->prepare("INSERT INTO parents (ParentTitle, ParentName, ParentEmail, ParentContactNumber, ParentSurname) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $parent_title, $parent_name, $parent_email, $parent_contactnumber, $parent_surname);
        $stmt->execute();
        $parent_id = $connect->insert_id; // Get the parent ID after insertion
        $stmt->close();
      }

      // Check if the learner already exists
      $stmt = $connect->prepare("SELECT LearnerId FROM learners WHERE Email = ?");
      $stmt->bind_param("s", $learner_email);
      $stmt->execute();
      $stmt->bind_result($learner_id);
      $stmt->fetch();
      $stmt->close();

      // If learner does not exist, insert learner data
      if (!$learner_id) {
        $total_fees = $maths + $physics; // Calculate total fees

        // Adjust fees based on conditions
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
        
        // Insert learner data without using knockout_time for expiry
        $stmt = $connect->prepare("INSERT INTO learners (Name, Surname, Email, ContactNumber, Grade, RegistrationDate, LearnerKnockoffTime, Math, Physics, TotalFees, TotalPaid, TotalOwe) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiisddddd", $learner_name, $learner_surname, $learner_email, $learner_contactnumber, $learner_grade, $learner_knockout_time, $maths, $physics, $total_fees, $total_paid, $total_owe);

        if ($stmt->execute()) {
          $learner_id = $connect->insert_id; // Get the learner ID
          $stmt->close();

          /////////////////////////////////////////////
          // Insert into finances table
          $total_fees = $maths + $physics; // Calculate total fees
          $total_paid = 0; // Set default value for now

          $stmt = $connect->prepare("INSERT INTO finances (LearnerId, Grade, TotalFees, TotalPaid, Math, Physics) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("iiiddd", $learner_id, $learner_grade, $total_fees, $total_paid, $maths, $physics);
          $stmt->execute();
          $stmt->close();

          /////////////////////////////////////////////

          // Insert subjects (Math and Physics)
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
              $subject_id = 1;
            } else if ($learner_grade == 11) {
              $subject_id = 3;
            }else if ($learner_grade == 10) {
              $subject_id = 5;
            } else {
              echo '<h1>Grade does not exist</h1>';
            }

            $registration_date = new DateTime();  // Current date
            list($contract_expiry_date, $status, $number_of_terms) = calculateContractExpiry($maths, $registration_date);

            // Insert into LearnersSubject table for maths
            $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, NumberOfTerms, ContractExpiryDate, Status) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("iiiiiss", $learner_id, $subject_id, $mathsTarget, $mathsCurrent, $number_of_terms, $contract_expiry_date, $status);
            $stmt2->execute();
            $stmt2->close();
          }

          // For Physics
          if ($physics != 0) {

            // Value of $subject_id for physics depends on $learner_grade
            if($learner_grade == 12){
              $subject_id = 2;
            } else if ($learner_grade == 11) {
              $subject_id = 4;
            }else if ($learner_grade == 10) {
              $subject_id = 6;
            } else {
              echo '<h1>Grade does not exist</h1>';
            }

            $registration_date = new DateTime();  // Current date
            list($contract_expiry_date, $status, $number_of_terms) = calculateContractExpiry($physics, $registration_date);

            // Insert into LearnersSubject table for physics
            $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, NumberOfTerms, ContractExpiryDate, Status) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("iiiiiss", $learner_id, $subject_id, $physicsTarget, $physicsCurrent, $number_of_terms, $contract_expiry_date, $status);
            $stmt2->execute();
            $stmt2->close();
          }

          // Create parent-learner relationship
          $stmt2 = $connect->prepare("INSERT INTO parentlearner (ParentId, LearnerId) VALUES (?, ?)");
          $stmt2->bind_param("ii", $parent_id, $learner_id);

          if ($stmt2->execute()) {
            $connect->commit(); // Commit the transaction

            // Send confirmation email to parent
            sendEmailToParent($parent_email, $parent_name);
          } else {
            $connect->rollback();
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'Parent-learner link could not be created.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'add.php'; 
                    });
                  </script>";
            exit();
          }
        } else {
          $connect->rollback();
          echo "<script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Registration Failed',
                      text: 'Learner data could not be inserted.',
                      confirmButtonText: 'OK'
                  }).then(function() {
                      window.location = 'add.php'; 
                  });
                </script>";
          exit();
        }
      }

    } catch (Exception $e) {
      // Rollback on any error
      $connect->rollback();
      echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Registration Failed',
                  text: 'An error occurred during registration. " . $e->getMessage() . "',
                  confirmButtonText: 'OK'
              }).then(function() {
                  window.location = 'add.php'; 
              });
            </script>";
    }
  }
}

// Function to send email to parent
function sendEmailToParent($parent_email, $parent_name) {
  // Initialize PHPMailer
  $mail = new PHPMailer(true);

  try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'thedistributorsofedu@gmail.com'; 
    $mail->Password = 'bxuxtebkzbibtvej'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Set recipients
    $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
    $mail->addAddress($parent_email, $parent_name);
    $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis'); 

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Learner Registration - DOE Verification';
    $mail->Body = "

      <p>Dear $parent_name,</p>
      <p>We are thrilled to welcome you and your child to the Distributors of Education family!</p>
      <p>We’re pleased to inform you that your child has been successfully registered with us. We are excited to have them on board and look forward to supporting them on their academic journey.</p>
      <p>If you have any questions or need further assistance, feel free to reach out to us.</p>
      <p>Once again, welcome—and thank you for choosing the Distributors of Education.</p>
      <br>
      <p>Warm regards,</p>
      <p><strong>DOE Team</strong></p>";
    // Send email d

    if($mail->send()) {
      // Success message for all emails sent successfully
      echo '<script>
              Swal.fire({
                  icon: "success",
                  title: "Registration Successful",
                  text: "A confirmation email has been sent to $parent_email.",

                  confirmButtonColor: "#3085d6",
                  confirmButtonText: "OK"
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "add2.php";
                  }
              });
            </script>';
      exit;
  } else {
      // If email fails to send, record it in the failed emails array
      $failedEmails[] = $email;
  }


  } catch (Exception $e) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email Send Failed',
                text: 'There was an issue sending the confirmation email. Please try again later.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'add.php'; 
            });
          </script>";
  }
}
?>

<div class="wrapper"></div>

</body>
</html>
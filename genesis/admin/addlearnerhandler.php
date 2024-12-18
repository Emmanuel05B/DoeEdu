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
//if (isset($_POST['reg1'])) {

  // Leraner info from POST
  $learner_name = $_POST['name'];
  $learner_surname = $_POST['surname'];
  $learner_email = $_POST['email'];
  $learner_contactnumber = $_POST['contactnumber'];
  $learner_grade = $_POST['grade'];
  $learner_knockout_time = $_POST['knockout_time'];

  // Parent info from POST
  $parent_name = trim($_POST['parentname']);
  $parent_surname = trim($_POST['parentsurname']);
  $parent_email = $_POST['parentemail'];
  $parentcontact_number = $_POST['parentcontactnumber'];
  $parenttitle = $_POST['title'];

  // Validate required fields
  if (empty($parent_name) || empty($parent_email) || empty($parentcontact_number) || empty($parent_surname)) {
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
        $stmt = $connect->prepare("INSERT INTO parents (ParentTiltle, ParentName, ParentEmail, ParentContactNumber, ParentSurname) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $parenttitle, $parent_name, $parent_email, $parentcontact_number, $parent_surname);
        $stmt->execute();
        $parent_id = $connect->insert_id; // Get the parent ID after insertion
        $stmt->close();
      }


        // Check if the leaner already exists
        $stmt = $connect->prepare("SELECT LearnerId FROM learners WHERE Email = ?");
        $stmt->bind_param("s", $learner_email);
        $stmt->execute();
        $stmt->bind_result($learner_id);
        $stmt->fetch();
        $stmt->close();
  
        // If learner does not exist, insert learner data
       
                    // Insert learner data
            $stmt = $connect->prepare("INSERT INTO learners (Name, Surname, Email, ContactNumber, Grade, RegistrationDate, ContractExpiryDate, LearnerKnockoffTime) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
            $stmt->bind_param("sssiiss", $learner_name, $learner_surname, $learner_email, $learner_contactnumber, $learner_grade, $contract_expiry_date, $learner_knockout_time);

           if ($stmt->execute()) {
              $learnerId = $connect->insert_id;  // Get the learner ID
              $stmt->close();

               // Insert subject levels for the learner
              if (isset($_POST['subjects'])) {
                foreach ($_POST['subjects'] as $subject_name) {
                  // Get the SubjectId based on SubjectName
                  $subject_query = $connect->prepare("SELECT SubjectId FROM subjects WHERE SubjectName = ?");
                  $subject_query->bind_param("s", $subject_name);
                  $subject_query->execute();
                  $subject_query->bind_result($subject_id);
                  $subject_query->fetch();
                  $subject_query->close();

                    // If subject exists, insert data into LearnersSubject table
                     if ($subject_id) {
                        $current_level = $_POST['current'];
                        $target_level = $_POST['target'];
                        $number_of_terms = isset($levels['terms']) ? $levels['terms'] : 1;  // Default to 1 if not set....this should depend on the number of terms selected for each lubjects.. not levels.
                        $status = 'Active';  // Assuming 'Active' status, you may change it based on the business logic

                        if ($current_level != 0 && $target_level != 0) {
                          // Insert into LearnersSubject table
                           $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, NumberOfTerms, ContractExpiryDate, Status) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt2->bind_param("iiiiiis", $learnerId, $subject_id, $target_level, $current_level, $number_of_terms, $contract_expiry_date, $status);
                            $stmt2->execute();
                            $stmt2->close();
                        }
                      }
                }
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

   

      // Create parent-learner relationship
      $stmt2 = $connect->prepare("INSERT INTO parentlearner (ParentId, LearnerId) VALUES (?, ?)");
      $stmt2->bind_param("ii", $parent_id, $learnerId);

      if ($stmt2->execute()) {
         $connect->commit(); // Commit the transaction

        // Send confirmation email to parent
        //sendEmailToParent($parent_email, $parent_name);
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
    } catch (Exception $e) {
      // Rollback on any error
      $connect->rollback();
      echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Registration Failed',
                  text: 'An error occurred during registration.',
                  confirmButtonText: 'OK'
              }).then(function() {
                  window.location = 'add.php'; 
              });
            </script>";
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
    $mail->Username = 'vilakazinurse128@gmail.com'; 
    $mail->Password = 'mvjmvkiowhpohtlk'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Set recipients
    $mail->setFrom('vilakazinurse128@gmail.com', 'DoE_Genesis');
    $mail->addAddress($parent_email, $parent_name);  //dont forget the title
    //$mail->addReplyTo('vilakazinurse128@gmail.com', 'DoE_Genesis'); // Set your Gmail address and your name as the reply-to address


    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Learner Registration - DOE Verification';
    $mail->Body = "
      <p>Dear $parent_name,</p>
      <p>Your learner has been successfully registered with the Distributors of Education (DOE). The registration is complete.</p>
      <p>Best regards,</p>
      <p>DOE</p>";

    // Send email
    $mail->send();

    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: 'Parent and learner have been successfully registered, and an email has been sent.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'add.php';
                }
            });
          </script>";

  } catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  }
}

?>

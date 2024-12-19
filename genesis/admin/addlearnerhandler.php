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
  $learner_knockout_time = $_POST['knockout_time'];

  // Parent info from POST
  $parent_name = trim($_POST['parentname']);
  $parent_surname = trim($_POST['parentsurname']);
  $parent_email = $_POST['parentemail'];
  $parent_contactnumber = $_POST['parentcontact'];
  $parent_title = $_POST['parenttitle'];


  // Subject prices which automatically deertermines the number of terms
  $maths = $_POST['maths'];
  $physics = $_POST['physics'];

   // Subject levels
   $mathsCurrent = $_POST['math-current'];
   $mathsTarget = $_POST['math-target'];
   $physicsCurrent = $_POST['physics-current'];
   $physicsTarget = $_POST['physics-target'];






/*
echo $maths; 
echo "--------";
echo $physics ;
echo "--------";
echo $mathsCurrent;
echo "--------";
echo $mathsTarget ;
echo "--------";
echo $physicsCurrent;
echo "--------";
echo $physicsTarget ;
*/

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
        $contract_expiry_date = date('Y-m-d', strtotime($learner_knockout_time)); // Assuming knockout time is used as expiry date

        // Insert learner data
        $stmt = $connect->prepare("INSERT INTO learners (Name, Surname, Email, ContactNumber, Grade, RegistrationDate, ContractExpiryDate, LearnerKnockoffTime) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("sssiiss", $learner_name, $learner_surname, $learner_email, $learner_contactnumber, $learner_grade, $contract_expiry_date, $learner_knockout_time);

        if ($stmt->execute()) {
          $learner_id = $connect->insert_id; // Get the learner ID
          $stmt->close();


         /*/ if (isset($_POST['subjects']) && !empty($_POST['subjects'])) {
          $current_level = $_POST['current'];
          $target_level = $_POST['target'];
          $number_of_terms = isset($_POST['terms']) ? $_POST['terms'] : 1;  // Default to 1 if not set
          $status = 'Active';  // Assuming 'Active' status

          if ($current_level != 0 && $target_level != 0) {  */



          //do for maths
          if($maths == 0) {  
         //do nothing
     
          }else{

            $subject_id = 1;
            $mtarget_level = $mathsTarget;
            $mcurrent_level = $mathsCurrent;


              if($maths == 450.00){  
      
                $number_of_terms = 1;     //1 term = 3 months  approximately 90 days
                $contract_expiry_date = 60*60*24*90 ;    //90 days in seconds
                //$contract_expiry_date = date('Y-m-d', strtotime(NOW()));                

                $status = 'Active';

              }else if($maths == 750.00){
                
                $number_of_terms = 2;
                $contract_expiry_date = 60*60*24*180 ;  //180 days in seconds
                $status = 'Active';

              }else if($maths == 1119.00){
             
                $number_of_terms = 4;
                $contract_expiry_date = 60*60*24*365;
                $status = 'Active';

              }else {
                $number_of_terms = 0;   //unnecessary
                $status = 'Not Active';
              }
              
                // Insert into LearnersSubject table
                  $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, NumberOfTerms, ContractExpiryDate, Status) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?)");
                  $stmt2->bind_param("iiiiiis", $learner_id, $subject_id, $mtarget_level, $mcurrent_level, $number_of_terms, $contract_expiry_date, $status);
                  $stmt2->execute();
                  $stmt2->close();

          }



                    //do for physics
                    if($physics == 0) {  
                      //do nothing
                  
                       }else{
             
                         $subjectid = 2;
                         $ptarget_level = $physicsTarget;
                         $pcurrent_level =$physicsCurrent;

             
                           if($physics == 450.00){  
                   
                             $number_of_terms = 1;     //1 term = 3 months  approximately 90 days
                             //$contract_expiry_date = 60*60*24*90 - NOW();    //90 days in seconds
                             $contract_expiry_date = 60*60*24*90;    //90 days in seconds
                             $pstatus = 'Active';
                           }else if($physics == 750.00){
                             
                             $number_of_terms = 2;
                             $contract_expiry_date = 60*60*24*180 ;  //180 days in seconds
                             $pstatus = 'Active';

                          
                           }else if($physics == 1119.00){
                          
                             $number_of_terms = 4;
                             $contract_expiry_date = 60*60*24*365;
                             $pstatus = 'Active';

                           }else {
                             $number_of_terms = 0;   //unnecessary
                             $pstatus = ' Not Active';

                           }
                           
                             // Insert into LearnersSubject table
                               $stmt2 = $connect->prepare("INSERT INTO learnersubject (LearnerId, SubjectId, TargetLevel, CurrentLevel, NumberOfTerms, ContractExpiryDate, Status) 
                                                         VALUES (?, ?, ?, ?, ?, ?, ?)");
                               $stmt2->bind_param("iiiiiis", $learner_id, $subjectid, $ptarget_level, $pcurrent_level, $number_of_terms, $contract_expiry_date, $pstatus);
                               $stmt2->execute();
                               $stmt2->close();
             
                       }


          // Create parent-learner relationship
          $stmt2 = $connect->prepare("INSERT INTO parentlearner (ParentId, LearnerId) VALUES (?, ?)");
          $stmt2->bind_param("ii", $parent_id, $learner_id);

          if ($stmt2->execute()) {
            $connect->commit(); // Commit the transaction

            // Send confirmation email to parent
           // sendEmailToParent($parent_email, $parent_name);

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
                  text: 'An error occurred during registration. ' + $e->getMessage(),
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
    $mail->Username = 'vilakazinurse128@gmail.com'; 
    $mail->Password = 'mvjmvkiowhpohtlk'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Set recipients
    $mail->setFrom('vilakazinurse128@gmail.com', 'DoE_Genesis');
    $mail->addAddress($parent_email, $parent_name);

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

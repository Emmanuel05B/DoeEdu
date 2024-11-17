
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../../vendor/autoload.php';  
include('../../partials/connect.php');

$errors = [];
$name = $email = $password = '';

if (isset($_SESSION['Name'])) {     
  $name = $_SESSION['Name'];
}

if (isset($_SESSION['Email'])) {
  $Email = $_SESSION['Email'];
}

if (isset($_POST['reg'])) {
  
  $password = $_POST['password'];
  $name = trim($_POST['name']);
  $surname = trim($_POST['surname']);
 
  $gender = $_POST['gender'];
  $identityNo = $_POST['id'];

  $contactnumber = $_POST['contactnumber'];
  $secondcontactnumber = $_POST['secondcontactnumber'];

  $email = $_POST['email'];
  $address = trim($_POST['address']);

//to be stored in the parent table with the fake Parent ID that we gonna get after registering this parent
  $learnerFakeid = $_POST['learnerFakeid'];  
  

  // Validate and sanitize user input
  if (empty($name) || empty($email) || empty($password) || empty($surname) || empty($identityNo) || empty($contactnumber)) {
    $errors[] = "All fields are required.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  }
 // if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
 //   $errors[] = "Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
 // }
  if (count($errors) === 0) {

    // Check if the email already exists
    $_SESSION['Name'] = $name;      //creation of sessions.....
    $_SESSION['Email'] = $email;
    $stmt = $connect->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR UserId = ?");
    $stmt->bind_param("ss", $email, $identityNo);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
      $_SESSION['userexists'] = true;
    } else {
      // Generate a unique verification token
      $verificationToken = bin2hex(random_bytes(32));
      // Password Hashing
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      // Start transaction
      $connect->begin_transaction();

      try{

        // Insert the new user into the database with the verification token
        $stmt = $connect->prepare("INSERT INTO users (UserId, Surname, Name, UserPassword, Gender, Contact, AlternativeContact, Email, IsVerified, VerificationToken, RegistrationDate) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?,0, ?, Now())");
        $stmt->bind_param("issssiiss", $identityNo, $surname, $name, $hashedPassword, $gender, $contactnumber, $secondcontactnumber, $email,  $verificationToken);
        
        if ($stmt->execute()===TRUE) {
          // Get the last inserted ID
          $userFakeid = $connect->insert_id;

          // Insert data into parent table
          $stmt2 = $connect->prepare("INSERT INTO parent (ParentId, LearnerId)
          VALUES(?,?)");
          $stmt2->bind_param("ii",$userFakeid, $learnerFakeid);

          if ($stmt2->execute()===TRUE){
            // Commit transaction
            $connect->commit();
              /////////////////time to send the email
             
               // Send the verification email to the user using PHPMailer
                $mail = new PHPMailer(true);
                try {
                  // Server settings
                  $mail->isSMTP(); // Set mailer to use SMTP
                  $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server (in this case, Gmail)
                  $mail->SMTPAuth = true; // Enable SMTP authentication
                  $mail->Username = 'vilakazinurse128@gmail.com'; // SMTP username (your Gmail email address)
                  $mail->Password = 'mvjmvkiowhpohtlk'; // SMTP password (your Gmail password)
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_STARTTLS` also accepted
                  $mail->Port = 465; // TCP port to connect to (Gmail's SMTP port for SSL)

                  // Recipients
                  $mail->setFrom('vilakazinurse128@gmail.com', 'Auticare_Connect'); // Set your Gmail address and your name as the sender
                  $mail->addAddress($email, $name); // Add a recipient (the user's email address)
                  $mail->addReplyTo('vilakazinurse128@gmail.com', 'Auticare_Connect'); // Set your Gmail address and your name as the reply-to address

                  // Content
                  $mail->isHTML(true);
                  $mail->Subject = 'EMAIL VERIFICATION';
                  $mail->Body = '
                  <p>Dear ' . $name . ',</p>
                  <p>Welcome to Auticare_Connect!.</p>
                  <p>We are excited to have you as part of our community. To complete your registration and activate your account, please verify your email address by clicking the link below:</p>
                  <a href="http://localhost/code-masters/team02-main/auticare/admin/verification.php?token=' . $verificationToken . '" style="background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Verify Email</a>
                  <p>We have created an account for you as one of the Parent at Tamarisk Primary School.</p>
                  <p>What Happens Next?</p>
                  <p>Verify Your Email: Clicking the link above will verify your email address and activate your account.</p>
                  <p>Once verified, you will have full access to our system, where you can find valuable resources and stay updated with the latest news and events.</p>
                  <p>Ensure you stay connected with us for important updates and information relevant to you and your Child.</p>
                  <p>Warm regards,</p>
                  <p>Auticare_Connect</p>
                  <p>Customer Support Team</p>
                  <p>Email: support@AuticareConnect.com</p>
                  <p>Phone: (123) 456-7890</p>
                  ';
              
                  $mail->send();                 
                  $_SESSION['Success'] = true;

                } catch (Exception $e) {
                  $errors[] = "Error while sending email: " . $mail->ErrorInfo;
                }
            

          }else{
            // Rollback transaction if second insert fails
            $connect->rollback();
           
            $_SESSION['Error'] = true;

          }
        }else{
          // Rollback transaction if first insert fails
          $connect->rollback();
          $_SESSION['Error'] = true;
        }

      }catch(Exception $e){
        // Rollback transaction if an exception occurs
        $connect->rollback();
        $_SESSION['Error'] = true;
      }
      $connect->close();
  
    }
    
  }
}
?>
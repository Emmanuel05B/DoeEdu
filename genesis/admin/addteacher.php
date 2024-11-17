<!DOCTYPE html>
<html>


<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

?>

<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<style>

.registerbtn {
  background-color: #2d98da;
  color: white;
  padding: 15px 15px;
  margin: 2px;
  align: center;
  border: none;
  cursor: pointer;
  width: 100%;
  height: 50px;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}
.content {  /* for the white thingy */
  background-color: white;
  
  margin-top: 20px;
  margin-left: 80px;
  margin-right: 80px;
}
.pos {
  margin-bottom: 30px;
  text-align: center; 
}
</style>


<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader

require '../../vendor/autoload.php';  
include '../partials/Connect.php';

$errors = [];
$name = $email = $password = '';

if (isset($_SESSION['Name'])) {       ///what does this code check for
  $name = $_SESSION['Name'];
}

if (isset($_SESSION['Email'])) {///what does this code check for
  $Email = $_SESSION['Email'];///what does this code check for
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
  
  $specialisation = $_POST['specialization'];
  
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

          // Insert data into employee table
          $stmt2 = $connect->prepare("INSERT INTO employee(Id, StartDate, employeeType ,Specialisation)
          VALUES(?,Now(),1,?)");
          $stmt2->bind_param("is",$userFakeid, $specialisation);

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
                  <p>We have created an account for you as one of the Teachers at Tamarisk Primary School.</p>
                  <p>What Happens Next?</p>
                  <p>Verify Your Email: Clicking the link above will verify your email address and activate your account.</p>
                  <p>Once verified, you will have full access to our system, where you can find valuable resources and stay updated with the latest news and events.</p>
                  <p>Ensure you stay connected with us for important updates and information relevant to you.</p>
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

<!-- HTML CODE DOWN HERE -->


<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
    <section class="content">   <!-- start -->

 <form action="addteacher.php" method="POST">

  <div class="pos">
    <h4>Registering</h4>
    <h4>Teacher</h4>

   <?php
    // Check session variable and echo JavaScript if condition is met
    if (isset($_SESSION['Success']) && $_SESSION['Success']) {
      echo '<script>
      Swal.fire({
          icon: "success",
          title: "Teacher Successfully Registered",
          text: "An email has been sent to the Teacher to verify.",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "OK"
      }).then((result) => {
          if (result.isConfirmed) {
              // Redirect or perform any other action as needed
              window.location.href = "addteacher.php";
          }
      });
    </script>';

        // Reset the session variable after using it
        $_SESSION['Success'] = false;
    }
    ?>
   
  
     <?php
    // Check session variable and echo JavaScript if condition is met
    if (isset($_SESSION['Error']) && $_SESSION['Error']) {
      echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Teacher Unsuccessfully Registered',
                    text: 'No email has been sent to the Teacher to verify.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'addteacher.php'; 
                });
            </script>";

        // Reset the session variable after using it
        $_SESSION['Error'] = false;
    }
    ?>

  <?php
    // Check session variable and echo JavaScript if condition is met
    if (isset($_SESSION['userexists']) && $_SESSION['userexists']) {
      echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Teacher already Exists',
                    text: 'No email has been sent to the Teacher to verify.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'addteacher.php'; 
                });
            </script>";

        // Reset the session variable after using it
        $_SESSION['userexists'] = false;
    }
    ?>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">First Names</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Names" required>
    </div>
    <div class="form-group col-md-6">
      <label for="surname">Last Name</label>
      <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
    </div>
  </div>


  <div class="form-row">
    <div class="form-row">
        
           <div class="form-group col-md-6">
                  <div class="form-group col-md-6">
                    <label for="id">ID Number (13 digits):</label><br>
                    <input type="text" class="form-control" id="id" name="id" pattern="[0-9]{13}" maxlength="13" required>
                   </div>
                   <div class="form-group col-md-6">
                      <label for="name">Title </label>
                      <select id="gender" name="gender" class="form-control" >
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                      </select>
                   </div>
            </div>
    </div>
  </div>

  
  <div class="form-row">
    <div class="form-row">
        
           <div class="form-group col-md-6">
                  <div class="form-group col-md-6">

                  <label for="contactnumber">Contact Number (10 digits):</label><br>
                  <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>

                   </div>
                   <div class="form-group col-md-6">
                    
                  <label for="secondcontactnumber">Contact Number (10 digits):</label><br>
                  <input type="tel" class="form-control" id="secondcontactnumber" name="secondcontactnumber" pattern="[0-9]{10}" maxlength="10" >

                  </select>
                   </div>
            </div>
    </div>
  </div>


  <div class="form-row">
    <div class="form-group col-md-6">
      
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
    </div>
    <div class="form-group col-md-6">
                 <label for="name">Specialization</label>
                  <select type="text" id="specialization" name="specialization" class="form-control" >
                    <option value="ASD Level 1">ASD Level 1</option>
                    <option value="ASD Level 2">ASD Level 2</option>
                    <option value="ASD Level 3">ASD Level 3</option>
                  </select>
    </div>
  </div>
  
  

    <input type="hidden" id="password" name="password" value="12345">

  <button type="submit" class="registerbtn" name="reg">Register The Teacher</button>
</form>

    </section> <!-- end -->
  </div>
</div>




<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>


</body>
</html>

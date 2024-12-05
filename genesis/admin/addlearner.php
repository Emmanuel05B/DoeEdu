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

  .content {
    background-color: white;
    margin-top: 20px;
    margin-left: 80px;
    margin-right: 80px;
  }

  .pos {
    margin-bottom: 30px;
    text-align: center;
  }

  .subject-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
  }

  .subject-table th, .subject-table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
  }

  .subject-name {
    font-weight: bold;
  }

  .subject-options {
    text-align: center;
  }

  .subject-options input {
    margin: 0 5px;
  }
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
include '../partials/Connect.php';

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
  $contactnumber = $_POST['contactnumber'];
  $secondcontactnumber = $_POST['secondcontactnumber'];
  $email = $_POST['email'];
  $specialisation = $_POST['specialization'];

  $subjects = $_POST['subjects']; // Array to hold subject and duration details
  
  // Validation
  if (empty($name) || empty($email) || empty($password) || empty($surname) || empty($contactnumber)) {
    $errors[] = "All fields are required.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  }

  if (count($errors) === 0) {
    // Check if the email already exists
    $_SESSION['Name'] = $name;
    $_SESSION['Email'] = $email;
    $stmt = $connect->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR UserId = ?");
    $stmt->bind_param("ss", $email, $contactnumber);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
      $_SESSION['userexists'] = true;
    } else {
      // Insert the new user into the database and other details...
      // Transaction logic
    }
  }
}
?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php include("adminpartials/header.php") ?>
    <?php include("adminpartials/mainsidebar.php") ?>

    <div class="content-wrapper">
      <section class="content">

        <form action="addteacher.php" method="POST">
          <div class="pos">
            <h4>Registering Learner</h4>
          </div>

          <!-- Learner Info -->
          <h4>Learner Info:</h4>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">First Names</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Names" required>
            </div>
            <div class="form-group col-md-6">
              <label for="surname">Surname</label>
              <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
            </div>
          </div>

          <!-- Personal Information -->
          <div class="form-row">
          <div class="form-group col-md-6">
                   <label for="email">Email</label>
                   <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                   </div>
           
          </div>

          <div class="form-row">

    <div class="form-row">
        
           <div class="form-group col-md-6">
                   

                   <div class="form-group col-md-6">
                    <label for="contactnumber">Contact Number (10 digits):</label>
                     <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                   </div>
                   <div class="form-group col-md-6">
                   <label for="specialization">Grade</label>
                   <select type="text" id="grade" name="grade" class="form-control">
                     <option value="10">10</option>
                     <option value="11">11</option>
                     <option value="12">12</option>
                   </select>
              </div>
            </div>
    </div>
  </div>


          <!-- Subject Selection -->
          <h4>Select Subjects and Duration:</h4>
          <table class="subject-table">
            <thead>
              <tr>
                <th>Subject</th>
                <th>Not Registered</th>
                <th>3 Months</th>
                <th>6 Months</th>
                <th>12 Months</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="subject-name">Maths</td>
                <td class="subject-options"><input type="radio" name="subjects[maths]" value="0" checked></td>
                <td class="subject-options"><input type="radio" name="subjects[maths]" value="3"></td>
                <td class="subject-options"><input type="radio" name="subjects[maths]" value="6"></td>
                <td class="subject-options"><input type="radio" name="subjects[maths]" value="12"></td>
              </tr>
              <tr>
                <td class="subject-name">Physics</td>
                <td class="subject-options"><input type="radio" name="subjects[physics]" value="0" checked></td>
                <td class="subject-options"><input type="radio" name="subjects[physics]" value="3"></td>
                <td class="subject-options"><input type="radio" name="subjects[physics]" value="6"></td>
                <td class="subject-options"><input type="radio" name="subjects[physics]" value="12"></td>
              </tr>
              <!-- Add more subjects as needed -->
            </tbody>
          </table>

          <input type="hidden" id="password" name="password" value="12345">

          <button type="submit" class="registerbtn" name="reg">Register Learner</button>
        </form>

      </section>
    </div>
  </div>

  <?php include("adminpartials/queries.php"); ?>
  <script src="dist/js/demo.js"></script>
</body>
</html>

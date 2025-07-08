<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: addtutor.php");
    exit();
}

// Collect and sanitize POST data
$name = trim($_POST['name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$email = trim($_POST['email'] ?? '');
$title = $_POST['tutortitle'] ?? '';
$subjects = $_POST['subjects'] ?? [];


// Cast contact to int and validate
$contact = $_POST['contactnumber'] ?? '';


echo $name;
echo $surname;
echo $email;
echo $title;
echo $contact;


/*

if (!$name || !$surname || !$email || !$contact || !$title || empty($subjects)) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
          Swal.fire({
              icon: 'error',
              title: 'Missing or Invalid Information',
              text: 'Please fill in all required fields and select at least one subject with a valid contact number.',
              confirmButtonText: 'Go Back'
          }).then(() => {
              window.history.back();
          });
          </script>";
    exit();
}
*/
// Default password (hashed)
$defaultPassword = password_hash("12345", PASSWORD_DEFAULT);
$userType = 1; // Tutor user type as integer

// Begin transaction
$connect->begin_transaction();

try {
    // Insert into users table
         $stmtUser = $connect->prepare("INSERT INTO users (Surname, Name, UserPassword, Gender, Contact, Email, 
        IsVerified, VerificationToken, RegistrationDate, UserType) 
        VALUES (?, ?, ?, ?, ?, ?, 0, ?, Now(), 1)");

        $stmtUser->bind_param("ssssiss",
        $learner_surname,$learner_name,$hashedPassword,$LearnerTitle,$learner_contactnumber,$learner_email,$verificationToken);

    $stmtUser->execute();
    $tutorId = $stmtUser->insert_id;
    $stmtUser->close();


    

    // Insert into tutors table (you can modify these fields as needed)
    $stmtTutor = $connect->prepare("INSERT INTO tutors (TutorId, Bio, Qualifications, ExperienceYears, ProfilePicture, Availability) VALUES (?, '', '','', 0, '')");
    if (!$stmtTutor) {
        throw new Exception("Prepare failed: " . $connect->error);
    }
    $stmtTutor->bind_param("i", $tutorId);
    $stmtTutor->execute();
    $stmtTutor->close();

    // Insert subjects into tutorsubject table
    $stmtSubj = $connect->prepare("INSERT INTO tutorsubject (TutorId, SubjectId, Active) VALUES (?, ?, 1)");
    if (!$stmtSubj) {
        throw new Exception("Prepare failed: " . $connect->error);
    }
    foreach ($subjects as $subjId) {
        $subjId = (int)$subjId; // sanitize to int
        $stmtSubj->bind_param("ii", $tutorId, $subjId);
        $stmtSubj->execute();
    }
    $stmtSubj->close();

    $connect->commit();

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
          Swal.fire({
              icon: 'success',
              title: 'Tutor Registered',
              text: 'Tutor has been successfully added.',
              confirmButtonText: 'OK'
          }).then(() => {
              window.location.href = 'viewtutors.php';
          });
          </script>";

} catch (Exception $e) {
    $connect->rollback();
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
          Swal.fire({
              icon: 'error',
              title: 'Registration Failed',
              text: 'Error: " . addslashes($e->getMessage()) . "',
              confirmButtonText: 'Try Again'
          }).then(() => {
              window.history.back();
          });
          </script>";
}
?>

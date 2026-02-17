<?php
include_once(__DIR__ . "/../../partials/paths.php"); 
include_once(BASE_PATH . "/partials/session_init.php"); 
include_once(BASE_PATH . "/partials/connect.php"); 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize POST data
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $matric = trim($_POST['matric'] ?? '');
    $maths_80 = trim($_POST['maths_80'] ?? '');
    $maths_mark = (int)($_POST['maths_mark'] ?? 0);
    $science_80 = trim($_POST['science_80'] ?? '');
    $science_mark = (int)($_POST['science_mark'] ?? 0);
    $university_student = trim($_POST['university_student'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year_of_study = trim($_POST['year_of_study'] ?? '');
    $device = trim($_POST['device'] ?? '');
    $internet = trim($_POST['internet'] ?? '');
    $online_ok = trim($_POST['online_ok'] ?? '');
    $availability_time = trim($_POST['availability_time'] ?? '');
    $teach_grades = trim($_POST['teach_grades'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $start_immediately = trim($_POST['start_immediately'] ?? '');
    $expected_pay = (int)($_POST['expected_pay'] ?? 0);
    $probation_rate = trim($_POST['probation_rate'] ?? '');

    // Validate required fields
    if (!$name) $errors[] = "Name is required.";
    if (!$surname) $errors[] = "Surname is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";

    // Handle file upload
    $cv_matric_file = $_FILES['cv_matric'] ?? null;
    if (!$cv_matric_file || $cv_matric_file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Please upload your CV with Matric Certificate attached (PDF only).";
    } else {
        $fileExt = strtolower(pathinfo($cv_matric_file['name'], PATHINFO_EXTENSION));
        if ($fileExt !== 'pdf') {
            $errors[] = "Only PDF files are allowed for CV + Matric.";
        }

        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($cv_matric_file['size'] > $maxFileSize) {
            $errors[] = "Uploaded file exceeds the maximum size of 5 MB.";
        }
    }


    if (empty($errors)) {
    // Check if email already exists
    $checkStmt = $connect->prepare("SELECT COUNT(*) FROM tutorapplications WHERE Email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        $errors[] = "You have already submitted an application with this email.";
    } else {
        $uploadDir = BASE_PATH . '/uploads/cvs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $newFileName = uniqid('cv_', true) . '.pdf';
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($cv_matric_file['tmp_name'], $destPath)) {
            $errors[] = "Failed to upload file. Please try again.";
        } else {
            // Insert into database
            $stmt = $connect->prepare("
                INSERT INTO tutorapplications
                (Name, Surname, Email, Contact, Matric, Maths80, MathsMark, Science80, ScienceMark, UniversityStudent, Course, YearOfStudy, Device, Internet, OnlineOk, AvailabilityTime, TeachGrades, Experience, StartImmediately, ExpectedPay, ProbationRate, CV_Matric, SubmissionDate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param(
                "ssssssissssssssssssiss",
                $name, $surname, $email, $contact, $matric, $maths_80, $maths_mark,
                $science_80, $science_mark, $university_student, $course, $year_of_study,
                $device, $internet, $online_ok, $availability_time, $teach_grades,
                $experience, $start_immediately, $expected_pay, $probation_rate, $newFileName
            );

            if ($stmt->execute()) {
                $success = "Your tutor application has been submitted successfully!";

                // --- SEND CONFIRMATION EMAIL USING PHPMailer ---
                try {

                      $mail = new PHPMailer(true);
                      $mail->isSMTP();
                      $mail->Host = $_ENV['EMAIL_HOST'];
                      $mail->SMTPAuth   = true;
                      $mail->Username   = $_ENV['EMAIL_ADDRESS']; 
                      $mail->Password   = $_ENV['EMAIL_APP_PASSWORD'];
                      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                      $mail->Port = $_ENV['EMAIL_PORT']; 
                      $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');
                      $mail->addAddress($email, $surname);
                      $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');
                      $mail->isHTML(true);
     
                    $mail->Subject = 'Tutor Application Received - DoE Tutoring';
                    $mail->Body = "
                        <p>Dear $name $surname,</p>
                        <p>Thank you for submitting your application to become a DoE Mathematics & Physical Sciences Tutor.</p>
                        <p>We have received your application and our team will review it shortly. You will be contacted via email once there are updates regarding your application.</p>
                        <p>If you do not hear from us within one week after the closing date, please consider your application unsuccessful.</p>
                        <br>

                        <p>Best regards,<br>
                        <strong>DoE Tutoring Team</strong><br>
                        Email: info@doetutoring.com</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    // Log error but don't block user submission
                    error_log("Email sending failed: " . $mail->ErrorInfo);
                }

                // Clear form fields
                $name = $surname = $email = $contact = $matric = '';
                $maths_80 = $maths_mark = $science_80 = $science_mark = '';
                $university_student = $course = $year_of_study = '';
                $device = $internet = $online_ok = $availability_time = '';
                $teach_grades = $experience = $start_immediately = '';
                $expected_pay = $probation_rate = '';
            }

        }
    }
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DoE Tutor Application</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
body { background: #f5f5f5; padding: 20px; }
.form-container { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
h3 { margin-top: 0; }
.alert { font-weight: bold; text-align: center; }
</style>
</head>
<body>
<div class="container">
  <div class="form-container">
    <h3>DoE Maths & Science Tutor Application</h3>
    <hr>

    <?php if ($errors): ?>
      <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <!-- Personal info fields -->
      <label>Name *</label>
      <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
      <br>
      <label>Surname *</label>
      <input type="text" name="surname" class="form-control" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
      <br>
      <label>Email *</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
      <br>
      <label>Contact Number</label>
      <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($contact ?? ''); ?>">
      <br>

            <!-- Academic Requirements -->
      <label>Do you have a National Senior Certificate (Matric)? *</label>
      <select name="matric" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
      
      <label>Do you have 80% or more in Mathematics?</label>
      <select name="maths_80" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <label>What percentage did you get for Mathematics?</label>
      <input type="number" name="maths_mark" class="form-control" min="0" max="100" required>
      <br>
    
      <label>Do you have 80% or more in Physical Sciences?</label>
      <select name="science_80" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <label>What percentage did you get for Physical Sciences?</label>
      <input type="number" name="science_mark" class="form-control" min="0" max="100" required>
      <br>
    
      <label>Are you currently a university student?</label>
      <select name="university_student" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <label>If yes, what are you studying?</label>
      <input type="text" name="course" class="form-control" maxlength="100">
      <br>

      
      <label>What year of study are you in?</label>
        <select name="year_of_study" class="form-control">
          <option value="">Select</option>
          <option>1st Year</option>
          <option>2nd Year</option>
          <option>3rd Year</option>
          <option>4th Year</option>
          <option>Postgraduate</option>
          <option>Not a Student</option>
        </select>
        <br>

    
      <!-- Tutoring Readiness -->
      <label>Do you have a device suitable for tutoring?</label>
      <select name="device" class="form-control" required>
        <option value="">Select</option>
        <option>Laptop</option>
        <option>Tablet</option>
        <option>Smartphone</option>
        <option>No device</option>
      </select>
      <br>
    
      <label>Do you have reliable internet access?</label>
      <select name="internet" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <label>Are you comfortable tutoring online? (Zoom / Google Meet)</label>
      <select name="online_ok" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <label>Are you available in the afternoons/evenings?</label>
      <select name="availability_time" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
    
      <label>Are you able to teach Grade 10–12 learners?</label>
      <select name="teach_grades" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
      <!-- Competency -->
      <label>Do you have previous tutoring experience?</label>
      <select name="experience" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
    
      <label>Will you be able to start immediately?</label>
      <select name="start_immediately" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
    
    
      <!-- Payment -->
      <label>Expected pay per hour (R)</label>
      <input type="number" name="expected_pay" class="form-control" min="0" max="99999" required>
      <br>
    
      <label>Are you willing to accept a probation period rate?</label>
      <select name="probation_rate" class="form-control" required>
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
      </select>
      <br>
      
      <!-- Uploads -->
      <label>Upload Your CV with Matric Certificate attached (PDF only) *</label>
      <p style="font-size: 0.9em; color: #555;">
        Please combine your CV and Matric Certificate into a single PDF. <strong>No extra documents are needed.</strong>
      </p>
      <input type="file" name="cv_matric" class="form-control" accept="application/pdf" required>
      <br>

        <label class="checkbox-wrapper" style="display:flex; align-items:center; gap:8px; margin-top:10px;">
          <input type="checkbox" id="confirmBox">

          <span>I confirm that all the information I provided is true and correct. 
            I consent to the use of my data for application processing.  
            I have read and agree to the 
            <a href="/learn/genesis/applications/pages/terms-and-conditions.php" target="_blank">Terms & Conditions</a>
          </span>
        </label>


      <button type="submit" id="submitBtn" class="btn btn-primary btn-block" disabled>Submit Application</button>

      <script>
        const checkbox = document.getElementById('confirmBox');
        const submitBtn = document.getElementById('submitBtn');

        checkbox.addEventListener('change', function () {
          submitBtn.disabled = !this.checked;
        });
      </script>

    </form>
  </div>
</div>
</body>
</html>

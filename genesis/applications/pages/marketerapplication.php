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
    $english_mark = (int)($_POST['english_mark'] ?? 0);
    $digital_marketing_skill = trim($_POST['digital_marketing_skill'] ?? '');
    $marketing_tools = trim($_POST['marketing_tools'] ?? '');
    $time_management = trim($_POST['time_management'] ?? '');
    $communication = trim($_POST['communication'] ?? '');
    $content_creation = trim($_POST['content_creation'] ?? '');
    $social_media_exp = trim($_POST['social_media_exp'] ?? '');
    $creative_apps = trim($_POST['creative_apps'] ?? '');
    $expected_pay = (int)($_POST['expected_pay'] ?? 0);

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
        $checkStmt = $connect->prepare("SELECT COUNT(*) FROM marketerapplications WHERE Email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $errors[] = "You have already submitted an application with this email.";
        } else {
            // Upload file
            $uploadDir = BASE_PATH . '/uploads/cvs/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $newFileName = uniqid('cv_', true) . '.pdf';
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($cv_matric_file['tmp_name'], $destPath)) {
                $errors[] = "Failed to upload file. Please try again.";
            } else {
                // Insert into database
                $stmt = $connect->prepare("
                    INSERT INTO marketerapplications
                    (Name, Surname, Email, Contact, Matric, EnglishMark, DigitalMarketingSkill, MarketingTools, TimeManagement, Communication, ContentCreation, SocialMediaExp, CreativeApps, ExpectedPay, CV_Matric, SubmissionDate)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ");

                $stmt->bind_param(
                    "sssssisssssssis",
                    $name, $surname, $email, $contact, $matric, $english_mark,
                    $digital_marketing_skill, $marketing_tools, $time_management,
                    $communication, $content_creation, $social_media_exp,
                    $creative_apps, $expected_pay, $newFileName
                );

                if ($stmt->execute()) {
                    $success = "Your marketing officer application has been submitted successfully!";

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

                        $mail->Subject = 'Marketing Officer Application Received - DoE';
                        $mail->Body = "
                            <p>Dear $name $surname,</p>
                            <p>Thank you for submitting your application to become a DoE Marketing Officer.</p>
                            <p>We have received your application and our team will review it shortly. You will be contacted via email once there are updates regarding your application.</p>
                            <p>If you do not hear from us within one week after the closing date, please consider your application unsuccessful.</p>
                            <br>
                            <p>Best regards,<br>
                            <strong>DoE Team</strong><br>
                            Email: info@doetutoring.com</p>
                        ";

                        $mail->send();
                    } catch (Exception $e) {
                        error_log("Email sending failed: " . $mail->ErrorInfo);
                    }

                    // Clear form fields
                    $name = $surname = $email = $contact = $matric = '';
                    $english_mark = 0;
                    $digital_marketing_skill = $marketing_tools = '';
                    $time_management = $communication = '';
                    $content_creation = $social_media_exp = '';
                    $creative_apps = '';
                    $expected_pay = 0;
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DoE Marketing Officer Application</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <style>
    body { background: #f5f5f5; padding: 20px; }
    .form-container {
      background: #fff; padding: 25px; border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    h3 { margin-top: 0; }
    input, select, textarea { max-width: 100%; }
  </style>
</head>

<body>
<div class="container">
  <div class="form-container">

    <h3>DoE Marketing Officer Application</h3>
    <p>Please complete the form below. All fields marked with * are required.</p>
    <hr>

    <?php if ($errors): ?>
      <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

      <!-- Personal Information -->
      <label>Name *</label>
      <input type="text" name="name" class="form-control" maxlength="40" 
             value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
      <br>

      <label>Surname *</label>
      <input type="text" name="surname" class="form-control" maxlength="40"
             value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
      <br>

      <label>Email Address *</label>
      <input type="email" name="email" class="form-control" maxlength="60"
             value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
      <br>

      <label>Contact Number *</label>
      <input type="text" name="contact" class="form-control" maxlength="15"
             value="<?php echo htmlspecialchars($contact ?? ''); ?>" required>
      <br>

      <!-- Requirements -->
      <label>Do you have a National Senior Certificate (Matric)? *</label>
      <select name="matric" class="form-control" required>
        <option value="">Select</option>
        <option value="Yes" <?php echo ($matric ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo ($matric ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
      </select>
      <br>

      <label>What percentage did you get for English? *</label>
      <input type="number" name="english_mark" class="form-control" min="0" max="100"
             value="<?php echo htmlspecialchars($english_mark ?? ''); ?>" required>
      <br>

      <!-- Skills -->
      <label>Rate your digital marketing skills *</label>
      <select name="digital_marketing_skill" class="form-control" required>
        <option value="">Select</option>
        <option value="Beginner" <?php echo ($digital_marketing_skill ?? '') === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
        <option value="Intermediate" <?php echo ($digital_marketing_skill ?? '') === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
        <option value="Advanced" <?php echo ($digital_marketing_skill ?? '') === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
      </select>
      <br>

      <label>Which digital marketing tools have you used before? *</label>
      <select name="marketing_tools" class="form-control" required>
        <?php
        $tools = ["Meta Ads","Google Ads","Canva","Google Analytics","TikTok Ads","Mailchimp","Hootsuite","CapCut","Adobe Photoshop","Adobe Illustrator","Not Listed Here","None"];
        foreach($tools as $tool) {
            $sel = ($marketing_tools ?? '') === $tool ? 'selected' : '';
            echo "<option value=\"$tool\" $sel>$tool</option>";
        }
        ?>
      </select>
      <br>

      <label>How strong are your organizational & time management skills? *</label>
      <select name="time_management" class="form-control" required>
        <?php
        $options = ["Excellent","Good","Average"];
        foreach($options as $opt) {
            $sel = ($time_management ?? '') === $opt ? 'selected' : '';
            echo "<option value=\"$opt\" $sel>$opt</option>";
        }
        ?>
      </select>
      <br>

      <label>How strong are your communication skills? *</label>
      <select name="communication" class="form-control" required>
        <?php
        foreach($options as $opt) {
            $sel = ($communication ?? '') === $opt ? 'selected' : '';
            echo "<option value=\"$opt\" $sel>$opt</option>";
        }
        ?>
      </select>
      <br>

      <!-- Additional Smart Questions -->
      <label>Are you able to create posters, videos, and social media content? *</label>
      <select name="content_creation" class="form-control" required>
        <option value="">Select</option>
        <option value="Yes" <?php echo ($content_creation ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo ($content_creation ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
      </select>
      <br>

      <label>Do you have experience running social media pages or campaigns? *</label>
      <select name="social_media_exp" class="form-control" required>
        <option value="">Select</option>
        <option value="Yes" <?php echo ($social_media_exp ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo ($social_media_exp ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
      </select>
      <br>

      <label>Which creative apps do you use for designs/videos? *</label>
      <select name="creative_apps" class="form-control" required>
        <?php
        $apps = ["Canva","Adobe Photoshop","Adobe Illustrator","Adobe Premiere Pro","Adobe After Effects","CapCut","InShot","CorelDRAW","Filmora","DaVinci Resolve","Not Listed Here","None"];
        foreach($apps as $app) {
            $sel = ($creative_apps ?? '') === $app ? 'selected' : '';
            echo "<option value=\"$app\" $sel>$app</option>";
        }
        ?>
      </select>
      <br>

      <label>What is your expected monthly pay? *</label>
      <input type="number" name="expected_pay" class="form-control" min="0" max="99999"
             value="<?php echo htmlspecialchars($expected_pay ?? ''); ?>" required>
      <br>

      <!-- Uploads -->
      <label>Upload Your CV with Matric Certificate attached (PDF only) *</label>
      <p style="font-size: 0.9em; color: #555;">
        Please combine your CV and Matric Certificate into a single PDF. <strong>No extra documents are needed.</strong>
      </p>
      <input type="file" name="cv_matric" class="form-control" accept="application/pdf" required>
      <br>

      <!-- Submit Button -->
      <label class="checkbox-wrapper" style="display:flex; align-items:center; gap:8px; margin-top:10px;">
        <input type="checkbox" id="confirmBox" <?php echo !empty($_POST['confirmBox']) ? 'checked' : ''; ?>>
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
        submitBtn.disabled = !checkbox.checked;
        checkbox.addEventListener('change', function () {
          submitBtn.disabled = !this.checked;
        });
      </script>

    </form>
  </div>
</div>
</body>
</html>

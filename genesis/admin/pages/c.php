<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require('../partials/connect.php');

// Helper function to send email
function sendEmailToParent($parent_email, $parent_name, $learner_name, $activity_title) {
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
    $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoE-Genesis');

    $mail->isHTML(true);
    $mail->Subject = 'Activity Submission Alert - DoE';

    $mail->Body = "
      <p>Dear $parent_name,</p>
      <p>We would like to inform you that your child <strong>$learner_name</strong> did <strong>not</strong> submit the activity titled <strong>\"$activity_title\"</strong> before the due date.</p>
      <p>Please encourage them to participate in future activities to support their academic progress.</p>
      <br><p>Warm regards,</p><p><strong>DoE Team</strong></p>
    ";

    $mail->send();
    
  } catch (Exception $e) {
    // You may log this error
  }
}

// Get activity ID from POST
if (!isset($_POST['activityId'])) {
  echo "<script>alert('No activity selected.'); history.back();</script>";
  exit();
}

$activityId = intval($_POST['activityId']);

// Get activity info
$stmt = $connect->prepare("SELECT Title, Grade, SubjectName FROM onlineactivities WHERE Id = ?");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$activity = $stmt->get_result()->fetch_assoc();
$stmt->close();

$subjectId = $activity['SubjectName'];
$activityTitle = $activity['Title'];

// Get all learners assigned to this subject
$learnerIds = [];
$stmt = $connect->prepare("SELECT LearnerId FROM learnersubject WHERE SubjectId = ?");
$stmt->bind_param("i", $subjectId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $learnerIds[] = $row['LearnerId'];
}
$stmt->close();

if (empty($learnerIds)) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        icon: 'info',
        title: 'No learners found',
        text: 'There are no learners assigned to this subject.',
      }).then(() => { history.back(); });
    </script>";
  exit();
}

// Find learners who have NOT submitted the activity
$notSubmittedIds = [];

foreach ($learnerIds as $userId) {
  $stmt = $connect->prepare("SELECT COUNT(*) FROM learneranswers WHERE UserId = ? AND ActivityId = ?");
  $stmt->bind_param("ii", $userId, $activityId);
  $stmt->execute();
  $stmt->bind_result($answerCount);
  $stmt->fetch();
  $stmt->close();

  if ($answerCount == 0) {
    $notSubmittedIds[] = $userId;
  }
}

// Send emails to the parents of learners who did not submit
if (!empty($notSubmittedIds)) {
  $placeholders = implode(',', array_fill(0, count($notSubmittedIds), '?'));
  $types = str_repeat('i', count($notSubmittedIds));

  $query = "
    SELECT u.Name AS LearnerName, u.Surname AS LearnerSurname, l.ParentName, l.ParentSurname, l.ParentEmail
    FROM users u
    JOIN learners l ON u.Id = l.LearnerId
    WHERE u.Id IN ($placeholders)
  ";

  $stmt = $connect->prepare($query);
  $stmt->bind_param($types, ...$notSubmittedIds);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $learnerName = $row['LearnerName'] . ' ' . $row['LearnerSurname'];
    $parentName = $row['ParentName'];
    $parentEmail = $row['ParentEmail'];

    sendEmailToParent($parentEmail, $parentName, $learnerName, $activityTitle);
  }
  $stmt->close();

  // ✅ Update the LastFeedbackSent column with current timestamp
  $updateStmt = $connect->prepare("UPDATE onlineactivities SET LastFeedbackSent = NOW() WHERE Id = ?");
  $updateStmt->bind_param("i", $activityId);
  $updateStmt->execute();
  $updateStmt->close();
}

// Show SweetAlert and return
echo "
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
    window.onload = function() {
      Swal.fire({
        icon: 'success',
        title: 'Feedback Sent',
        text: 'Emails successfully sent to parents of learners who did not submit.',
      }).then(() => {
        window.history.back();
      });
    };
  </script>
";
?>

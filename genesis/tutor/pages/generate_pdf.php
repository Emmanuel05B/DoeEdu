<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

require_once BASE_PATH . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;


$imagePath = PROFILE_PICS_URL . '/doep.png';
$imageData = base64_encode(file_get_contents($imagePath));
$src = 'data:image/png;base64,' . $imageData;


$learner_id = isset($_POST['learnerId']) ? $_POST['learnerId'] : null;
$SubjectId = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : null;

$tutorEmail = $_SESSION['email'];

// Fetch tutor info
$tutorQuery = $connect->prepare("
    SELECT Name, Surname, Email, Gender
    FROM users
    WHERE Email = ?
");
$tutorQuery->bind_param("s", $tutorEmail);
$tutorQuery->execute();
$tutorData = $tutorQuery->get_result()->fetch_assoc();
$tutorQuery->close();

$name = $tutorData['Name'];
$sur = $tutorData['Surname'];
$email = $tutorData['Email'];
$title = $tutorData['Gender'];

// Fetch subject info
$subjectQuery = $connect->prepare("
    SELECT SubjectName, GradeId 
    FROM subjects 
    WHERE SubjectId = ?
");
$subjectQuery->bind_param("i", $SubjectId);
$subjectQuery->execute();
$subjectData = $subjectQuery->get_result()->fetch_assoc();
$subjectQuery->close();

$SubjectName = $subjectData['SubjectName'];
$grade = $subjectData['GradeId'];

// Fetch learner info
/*
$learner_sql = "SELECT * FROM users WHERE Id = $learner_id";   //prone to SQL injection
$learner_results = $connect->query($learner_sql);
$final = $learner_results->fetch_assoc();
*/

$stmtLearner = $connect->prepare("SELECT * FROM users WHERE Id = ?");
$stmtLearner->bind_param("i", $learner_id);
$stmtLearner->execute();
$final = $stmtLearner->get_result()->fetch_assoc();
$stmtLearner->close();

// Fetch learner activities
$activity_sql = "
    SELECT lam.ActivityId, lam.MarksObtained, a.ActivityName, a.MaxMarks, a.ChapterName, lam.DateAssigned,
           lam.Attendance, lam.AttendanceReason, lam.Submission, lam.SubmissionReason
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ?
    ORDER BY lam.DateAssigned ASC
";
$stmt = $connect->prepare($activity_sql);
$stmt->bind_param('ii', $learner_id, $SubjectId);
$stmt->execute();
$result = $stmt->get_result();

// Calculate attendance & submissions
$total_activities = $result->num_rows;
$missed_classes = 0;
$missed_activities = 0;

$attendance_data = [];
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
    if ($row['Attendance'] == 'absent') $missed_classes++;
    if ($row['Submission'] == 'No') $missed_activities++;
    if ($row['Attendance'] == 'absent' || $row['Submission'] == 'No') {
        $attendance_data[] = $row;
    }
}

$attendance_rate = ($total_activities > 0) ? (($total_activities - $missed_classes)/$total_activities)*100 : 0;
$submission_rate = ($total_activities > 0) ? (($total_activities - $missed_activities)/$total_activities)*100 : 0;

// Capture HTML
ob_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Learner Report</title>

<style>
    body { font-family: Arial, sans-serif; font-size:12px; }
    table { width:100%; border-collapse: collapse; margin-bottom: 10px;}
    th, td { border:1px solid #ddd; padding:5px; text-align:left; vertical-align:top;}
    th { background-color:#f2f2f2; }
    .header-table td { border:none; }
    .top-left-image { width:120px; }
    .center { text-align:center; }
</style>

</head>
<body>

<!-- Header -->
<table class="header-table" style="width:100%; margin-bottom:10px;">
  <tr>
    <td style="border:none; vertical-align:top; width:50%;">
      <img src="<?= $src ?>" class="top-left-image">
    </td>
    <td style="border:none; text-align:right; font-size:12px; line-height:1.4;">
      <p><b>Registration No:</b> 2022/735117/07</p>
      <p><b>Telephone:</b> 081 461 8178</p>
      <p><b>Email:</b> thedistributorsofedu@gmail.com</p>
    </td>
  </tr>
</table>

<hr>
<h3 class="center"><?= $final['Name']; ?>'s Report</h3>
<p class="center"><b>Subject:</b> <?= $SubjectName ?> &nbsp;&nbsp; <b>Generated on:</b> <?= date('Y-m-d') ?></p>
<hr>

<!-- Learner / Tutor Details -->
<table>
  <tr>
    <td style="width:50%;">
      <b>Learner Details</b>

      <table style="width:100%; border:1px solid #ddd; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="border:none; width:50%;"><b>Name:</b></td>
            <td style="border:none;"><?= $final['Name'] ?></td>
        </tr>
        <tr>
            <td style="border:none;"><b>Surname:</b></td>
            <td style="border:none;"><?= $final['Surname'] ?></td>
        </tr>
        <tr>
            <td style="border:none;"><b>Email:</b></td>
            <td style="border:none;"><?= $final['Email'] ?></td>
        </tr>
       </table>
    </td>
    <td style="width:50%;">
      <b>Tutor Details</b>

      <table style="width:100%; border:1px solid #ddd; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="border:none; width:50%;"><b>Name:</b></td>
            <td style="border:none;"><?= $name?></td>
        </tr>
        <tr>
            <td style="border:none;"><b>Surname:</b></td>
            <td style="border:none;"><?= $sur ?></td>
        </tr>
        <tr>
            <td style="border:none;"><b>Email:</b></td>
            <td style="border:none;"><?= $email ?></td>
        </tr>
       </table>
    </td>
  </tr>
</table><br>


<!-- Attendance & Submission -->
<b>Attendance & Submission Rates</b>
<table>
  <tr>
    <td style="width:50%;">
      <b>Attendance</b>
      <table style="width:100%; border:1px solid #ddd; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="border:none; width:50%;"><b>Attendance Rate:</b></td>
            <td style="border:none;"><?= number_format($attendance_rate, 2) ?>%</td>
        </tr>
        <tr>
            <td style="border:none;"><b>Classes Missed:</b></td>
            <td style="border:none;"><?= $missed_classes ?>/<?= $total_activities ?></td>
        </tr>
       </table>
    </td>
    <td style="width:50%;">
      <b>Submission</b>

      <table style="width:100%; border:1px solid #ddd; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="border:none; width:50%;"><b>Submission Rate:</b></td>
            <td style="border:none;"><?= number_format($submission_rate, 2) ?>%</td>
        </tr>
        <tr>
            <td style="border:none;"><b>Activities Missed:</b></td>
            <td style="border:none;"><?= $missed_activities ?>/<?= $total_activities ?></td>
        </tr>
       </table>
    </td>
  </tr>
</table>


<!-- Missed Attendance & Submissions -->
<b>Missed Attendance & Submission Reasons</b>
<table>
  <tr>
    <th>Activity</th>
    <th>Reason</th>
    <th>Type</th>
  </tr>
  <?php
  if(count($attendance_data) > 0){
      foreach($attendance_data as $row){
          if($row['Attendance']=='absent'){
              echo "<tr>
                      <td>{$row['ChapterName']} - {$row['ActivityName']}</td>
                      <td>".htmlspecialchars($row['AttendanceReason'])."</td>
                      <td>Did Not Attend Class</td>
                    </tr>";
          }
          if($row['Submission']=='No'){
              echo "<tr>
                      <td>{$row['ChapterName']} - {$row['ActivityName']}</td>
                      <td>".htmlspecialchars($row['SubmissionReason'])."</td>
                      <td>Did Not Submit Work</td>
                    </tr>";
          }
      }
  } else {
      echo "<tr><td colspan='3'>No missed attendance or submission records.</td></tr>";
  }
  ?>
</table>

<!-- Activity Scores -->
<b>Activity Scores</b>
<table>
  <tr>
    <th>Activity</th>
    <th>Marks</th>
    <th>Percentage</th>
  </tr>
  <?php
  $result->data_seek(0);
  $total_marks = 0;
  $total_max = 0;
  while($row = $result->fetch_assoc()){
      $percent = ($row['MarksObtained'] / $row['MaxMarks']) * 100;
      $total_marks += $row['MarksObtained'];
      $total_max += $row['MaxMarks'];
      echo "<tr>
              <td>{$row['ChapterName']} - {$row['ActivityName']}</td>
              <td>{$row['MarksObtained']}/{$row['MaxMarks']}</td>
              <td>".number_format($percent, 2)."%</td>
            </tr>";
  }
  ?>
</table>

<?php

// ================= ONLINE QUIZZES OVERALL CALCULATION =================

$onlineOverallSql = "
SELECT 
    a.Id AS ActivityId,
    a.TotalMarks,
    COUNT(la.Id) AS Answered,
    SUM(CASE WHEN la.SelectedAnswer = oq.CorrectAnswer THEN 1 ELSE 0 END) AS Correct
FROM onlineactivities a
INNER JOIN onlineactivitiesassignments aa ON aa.OnlineActivityId = a.Id
INNER JOIN classes c ON c.ClassID = aa.ClassID
INNER JOIN learnersubject ls ON ls.LearnerId = ? AND ls.SubjectId = c.SubjectId
LEFT JOIN learneranswers la ON la.ActivityId = a.Id AND la.UserId = ?
LEFT JOIN onlinequestions oq ON oq.Id = la.QuestionId
WHERE c.SubjectId = ? AND aa.AssignedAt > ls.ContractStartDate
GROUP BY a.Id
ORDER BY aa.AssignedAt ASC
";

$stmtOnlineOverall = $connect->prepare($onlineOverallSql);
$stmtOnlineOverall->bind_param("iii", $learner_id, $learner_id, $SubjectId);
$stmtOnlineOverall->execute();
$onlineOverallRes = $stmtOnlineOverall->get_result();

$onlineTotalCorrect = 0;
$onlineTotalMarks   = 0;

while ($row = $onlineOverallRes->fetch_assoc()) {
    // Only consider activities that were attempted
    if ($row['Answered'] > 0) {
        $onlineTotalCorrect += $row['Correct'];
        $onlineTotalMarks   += $row['TotalMarks'];
    }
}

// Calculate overall online quizzes percentage
$onlineOverallPercent = ($onlineTotalMarks > 0)
    ? ($onlineTotalCorrect / $onlineTotalMarks) * 100
    : 0;

$stmtOnlineOverall->close();
?>

<!-- Overall Performance -->
<?php
// ================= FINAL SUBJECT PERFORMANCE =================

$activitiesOverallPercent = ($total_max > 0)
    ? ($total_marks / $total_max) * 100
    : 0;

$activitiesWeight = 0.5;
$onlineWeight     = 0.5;

if ($total_max == 0 && $onlineTotalMarks == 0) {
    $finalOverallPercent = 0;
}
elseif ($total_max == 0) {
    $finalOverallPercent = $onlineOverallPercent;
}
elseif ($onlineTotalMarks == 0) {
    $finalOverallPercent = $activitiesOverallPercent;
}
else {
    $finalOverallPercent =
        ($activitiesOverallPercent * $activitiesWeight) +
        ($onlineOverallPercent * $onlineWeight);
}

// Category
if ($finalOverallPercent >= 90) {
    $category='Excellent';
    $comment="Outstanding overall subject performance!";
}
elseif ($finalOverallPercent >= 70) {
    $category='Good';
    $comment="Good understanding of subject content.";
}
elseif ($finalOverallPercent >= 50) {
    $category='Fair';
    $comment="Average performance. More practice required.";
}
else {
    $category='Poor';
    $comment="Performance below expected level. Immediate intervention needed.";
}


?>

<!-- Online Quizzes Scores -->
<b>System Quizzes Scores</b>
<table>
  <tr>
    <th>Homework Name</th>
    <th>Marks</th>
    <th>Percentage</th>
  </tr>
  <?php

                        $onlineSql = "
                        SELECT 
                            a.Id AS ActivityId,
                            a.Title,
                            a.Topic AS Chapter,
                            a.TotalMarks,
                            COUNT(la.Id) AS Answered,
                            SUM(
                                CASE 
                                    WHEN la.SelectedAnswer = oq.CorrectAnswer 
                                    THEN 1 ELSE 0 
                                END
                            ) AS Correct
                        FROM onlineactivities a

                        INNER JOIN onlineactivitiesassignments aa 
                            ON aa.OnlineActivityId = a.Id

                        INNER JOIN learnerclasses lc
                            ON lc.ClassID = aa.ClassID
                        AND lc.LearnerID = ?

                        INNER JOIN classes c
                            ON c.ClassID = lc.ClassID

                        INNER JOIN learnersubject ls
                            ON ls.LearnerId = lc.LearnerID
                        AND ls.SubjectId = c.SubjectID

                        LEFT JOIN learneranswers la 
                            ON la.ActivityId = a.Id
                        AND la.UserId = ?

                        LEFT JOIN onlinequestions oq 
                            ON oq.Id = la.QuestionId

                        WHERE aa.AssignedAt >= ls.ContractStartDate 
                        AND c.SubjectID = ?

                        GROUP BY a.Id
                        ORDER BY aa.AssignedAt ASC
                    ";

  $stmtOnline = $connect->prepare($onlineSql);
  $stmtOnline->bind_param("iii", $learner_id, $learner_id, $SubjectId);
  $stmtOnline->execute();
  $onlineResults = $stmtOnline->get_result();

  if ($onlineResults->num_rows > 0) {
      while ($row = $onlineResults->fetch_assoc()) {

          if ($row['Answered'] > 0) {
              $marks = $row['Correct'];
              $percentage = ($marks / $row['TotalMarks']) * 100;
              $marksDisplay = "{$marks} / {$row['TotalMarks']}";
              $percentDisplay = number_format($percentage, 2) . "%";
          } else {
              $marksDisplay = "-";
              $percentDisplay = "Not Attempted";
          }

          $homeworkName = "{$row['Chapter']} - {$row['Title']}";

          echo "<tr>
                  <td><b>{$homeworkName}</b></td>
                  <td>{$marksDisplay}</td>
                  <td>{$percentDisplay}</td>
                </tr>";
      }
  } else {
      echo "<tr><td colspan='3'>No online homework found.</td></tr>";
  }

  $stmtOnline->close();
  ?>
</table>

<table style="width:100%; border-collapse: collapse; margin-bottom:10px;">
  <tr>
    <th colspan="2" text-align:left; border:1px solid #ddd; padding:5px;">
      Overall Performance
    </th>
  </tr>
  <tr>
    <td colspan="2" style="border:1px solid #ddd; padding:10px; text-align:center; line-height:1.6;">
      
      <b>Status:</b> <?= htmlspecialchars($category) ?><br>
      <b>Activities Average:</b> <?= number_format($activitiesOverallPercent, 2) ?>%<br>
      <b>Online Quiz Average:</b> <?= number_format($onlineOverallPercent, 2) ?>%<br>
      <b>FINAL SUBJECT PERFORMANCE:</b> <?= number_format($finalOverallPercent, 2) ?>%<br>
      <b>Attendance Rate:</b> <?= number_format($attendance_rate, 2) ?>%<br>
      <b>Submission Rate:</b> <?= number_format($submission_rate, 2) ?>%<br>
      <b>Comment:</b> <?= htmlspecialchars($comment) ?>
    </td>
  </tr>
</table>




<!-- Financial Info -->
<?php
$sql = "
SELECT TotalFees, TotalPaid, Balance
                FROM finances
                WHERE LearnerId = ?";
$stmt_fin = $connect->prepare($sql);

$stmt_fin->bind_param('i',$learner_id);
$stmt_fin->execute();
$fin_result = $stmt_fin->get_result()->fetch_assoc();
?>
<b>Financial Info:</b>
<table>
  <tr>
    <th>Total Fees</th>
    <th>Total Paid</th>
    <th>Total Owe</th>
  </tr>
  <tr>
    <td>R <?= number_format($fin_result['TotalFees'], 2) ?></td>
    <td>R <?= number_format($fin_result['TotalPaid'], 2) ?></td>
    <td>R <?= number_format($fin_result['Balance'], 2) ?></td>
  </tr>
</table>

</body>
</html>

<?php
$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("learner_report.pdf", ["Attachment"=>true]);



?>

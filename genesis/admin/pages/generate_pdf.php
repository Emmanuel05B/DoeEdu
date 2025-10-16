<?php
require '../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$imagePath = '../images/westtt.png'; 
$imageData = base64_encode(file_get_contents($imagePath));
$src = 'data:image/png;base64,' . $imageData;

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

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
$learner_sql = "SELECT * FROM users WHERE Id = $learner_id";
$learner_results = $connect->query($learner_sql);
$final = $learner_results->fetch_assoc();

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
      <p>
        <b>Name:</b> <?= $final['Name'] ?><br>
        <b>Surname:</b> <?= $final['Surname'] ?><br>
        <b>Email:</b> <?= $final['Email'] ?>
      </p>
    </td>
    <td style="width:50%;">
      <b>Tutor Details</b>
      <p>
        <b>Name:</b> <?= $name ?><br>
        <b>Surname:</b> <?= $sur ?><br>
        <b>Email:</b> <?= $email ?>
      </p>
    </td>
  </tr>
</table>


<!-- Attendance & Submission -->
<table>
  <tr>
    <td style="width:50%;">
      <b>Attendance</b>
      <p>
        <b>Attendance Rate:</b> <?= number_format($attendance_rate, 2) ?>%<br>
        <b>Classes Missed:</b> <?= $missed_classes ?>/<?= $total_activities ?>
      </p>
    </td>
    <td style="width:50%;">
      <b>Submission</b>
      <p>
        <b>Submission Rate:</b> <?= number_format($submission_rate, 2) ?>%<br>
        <b>Activities Missed:</b> <?= $missed_activities ?>/<?= $total_activities ?>
      </p>
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


<!-- Overall Performance -->
<?php
$overall_score = ($total_max>0)?($total_marks/$total_max)*100:0;
if($overall_score>=90){ $category='Excellent'; $comment="Outstanding performance! Keep up the great work!"; }
elseif($overall_score>=70){ $category='Good'; $comment="Good performance. Keep pushing to reach even higher levels!"; }
elseif($overall_score>=50){ $category='Fair'; $comment="You’ve done well, but there’s room for improvement. Stay focused!"; }
else{ $category='Poor'; $comment="There’s significant room for improvement"; }

// Combine attendance and submission rates into the comment
    if ($attendance_rate < 75) {
        $comment .= " Your attendance rate is below 75%. Try to attend all classes for better learning.";
    } else {
        $comment .= " Your attendance rate is great!";
    }

?>
<table style="width:100%; border-collapse: collapse; margin-bottom:10px;">
  <tr>
    <th colspan="2" text-align:left; border:1px solid #ddd; padding:5px;">
      Overall Performance
    </th>
  </tr>
  <tr>
    <td colspan="2" style="border:1px solid #ddd; padding:10px; text-align:center; line-height:1.6;">
      <b>Status:</b> <?= htmlspecialchars($category) ?><br>
      <b>Overall Score:</b> <?= number_format($overall_score, 2) ?>%<br>
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
$dompdf->stream("learner_report.pdf", ["Attachment"=>false]);



?>

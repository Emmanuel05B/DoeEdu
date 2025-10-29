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

// Get input from POST
$subjectId = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : 0;
$grade     = isset($_POST['grade']) ? $_POST['grade'] : '';
$group     = isset($_POST['group']) ? $_POST['group'] : '';
$learnerIdsJson = $_POST['learnerIds'] ?? '[]';
$learnerIds = json_decode($learnerIdsJson, true);

// Remove duplicates
$learnerIds = array_unique($learnerIds);

// Prepare DOE logo
$imagePath = PROFILE_PICS_URL . '/doe.jpg';
$imageData = base64_encode(file_get_contents($imagePath));
$src = 'data:image/png;base64,' . $imageData;

// Fetch subject name
$subjectName = '';
if ($subjectId > 0) {
    $stmtSub = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
    $stmtSub->bind_param("i", $subjectId);
    $stmtSub->execute();
    $resSub = $stmtSub->get_result();
    if ($resSub && $resSub->num_rows > 0) {
        $rowSub = $resSub->fetch_assoc();
        $subjectName = $rowSub['SubjectName'];
    }
    $stmtSub->close();
}

// Fetch learner info
$learners = [];
if(count($learnerIds) > 0){
    $in  = str_repeat('?,', count($learnerIds) - 1) . '?';
    $types = str_repeat('i', count($learnerIds));
    $sql = "SELECT Id, Name, Surname FROM users WHERE Id IN ($in) ORDER BY Name ASC";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param($types, ...$learnerIds);
    $stmt->execute();
    $res = $stmt->get_result();
    while($row = $res->fetch_assoc()){
        $learners[] = $row;
    }
    $stmt->close();
}

// Capture HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Class Form</title>
<style>
    body { font-family: Arial, sans-serif; font-size:12px; }
    table { width:100%; border-collapse: collapse; margin-bottom: 10px;}
    th, td { border:1px solid #ddd; padding:5px; text-align:left; vertical-align:top;}
    th { background-color:#f2f2f2; }
    .header-table td { border:none; vertical-align:top; }
    .top-left-image { width:120px; }
    .center { text-align:center; }
</style>
</head>
<body>

<!-- Header -->
<table class="header-table" style="width:100%; margin-bottom:10px;">
  <tr>
    <td style="width:50%;"><img src="<?= $src ?>" class="top-left-image"></td>
    <td style="width:50%; text-align:right; font-size:12px; line-height:1.4;">
      <p><b>Registration No:</b> 2022/735117/07</p>
      <p><b>Telephone:</b> 081 461 8178</p>
      <p><b>Email:</b> thedistributorsofedu@gmail.com</p>
    </td>
  </tr>
</table>

<hr>
<h2 class="center">The DOE Weekly Participation Form</h2>
<p class="center"><b>Grade:</b> <?= htmlspecialchars($grade) ?> &nbsp; | &nbsp; <b>Subject:</b> <?= htmlspecialchars($subjectName) ?> <?php if($group) echo " &nbsp; | &nbsp; <b>Group:</b> ".htmlspecialchars($group); ?></p>
<p class="center"><b>Total Learners:</b> <?= count($learners) ?> &nbsp; | &nbsp; <b>Generated on:</b> <?= date('Y-m-d') ?></p>
<br>

<!-- Learner Table -->
<table>
    <thead>
        <tr>
            <th>StNo.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Attendance</th>
            <th>Reason</th>
            <th>Submission</th>
            <th>Reason</th>
            <th>Mark</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($learners as $index => $learner): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($learner['Name']) ?></td>
            <td><?= htmlspecialchars($learner['Surname']) ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php endforeach; ?>
        <?php if(count($learners) === 0): ?>
        <tr>
            <td colspan="8" class="center">No learners found for this class.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Key / Legend -->
<hr>
<p><b>Attendance:</b> <b>A</b>=Absent, <b>P</b>=Present, <b>L</b>=Late</p>
<p><b>Reason for absence:</b> <b>O</b>=Other, <b>DI</b>=Data Issues, <b>NP</b>=None Provided</p>
<p><b>Submission:</b> <b>N</b>=Did Not Submit, <b>Y</b>=Submitted</p>
<p><b>Reason for non-submission:</b> <b>O</b>=Other, <b>DI</b>=Data Issues, <b>NW</b>=Did Not Write, <b>NP</b>=None Provided</p>

</body>
</html>

<?php
$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("class_form.pdf", ["Attachment" => true]);
?>

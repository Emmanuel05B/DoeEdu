<?php
require '../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

// Get input parameters
$subjectId = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$grade     = isset($_GET['grade']) ? $_GET['grade'] : '';
$group     = isset($_GET['group']) ? $_GET['group'] : '';

// Fetch subject info
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

// Build query for learners in this subject/grade/group, contract active
$sql = "
    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
    FROM learners lt
    JOIN users u ON lt.LearnerId = u.Id
    LEFT JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
    LEFT JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
    LEFT JOIN classes c ON lc.ClassID = c.ClassID
    WHERE ls.SubjectId = ? AND ls.ContractExpiryDate > CURDATE()
";

$params = [$subjectId];
$types  = "i";

if ($grade !== '') {
    $sql .= " AND lt.Grade = ?";
    $types .= "s";
    $params[] = $grade;
}

if ($group !== '') {
    $sql .= " AND c.GroupName = ?";
    $types .= "s";
    $params[] = $group;
}

$stmt = $connect->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$results = $stmt->get_result();

// Capture HTML output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .top-right-image { position: absolute; top: 0; right: 0; max-height: 130px; margin-right: 30px; }
    </style>
</head>
<body>
    <div>
        <h2 style="text-align: center;"><b>The DOE Weekly Participation Form</b></h2>
        <hr><br>
        <table class="table">
            <tr>
                <td>
                    <p><strong>Registration No:</strong> 2022/735117/07</p>
                    <p><strong>Telephone:</strong> 081 461 8178</p>
                    <p><strong>Email:</strong> <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>
                </td>
                <td>
                    <img src="../images/westtt.png" alt="DOE Logo" class="top-right-image">
                </td>
            </tr>
        </table>
    </div>
    <br>

    <p style="text-align: center;">
        <b>Grade: </b><?php echo htmlspecialchars($grade); ?> 
        <b>| Subject: </b><?php echo htmlspecialchars($subjectName); ?> 
        <?php if($group) echo "<b>| Group: </b>".htmlspecialchars($group); ?>
    </p>
    <p style="text-align: center;"><b>Total Learners: </b><?php echo $results->num_rows; ?></p>
    <small style="display: block; text-align: center;"><b>Generated on: </b><?php echo date('Y-m-d'); ?></small><br>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <small>Marks: any number between 0 and the total marks.</small><br>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Attendance</th>
                                    <th>Reason</th>
                                    <th>Submission</th>
                                    <th>Reason</th>
                                    <th>Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($learner = $results->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($learner['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($learner['Surname']); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                           
                        </table>
                    </div>
                    <br>
                    <small>Key:</small>
                    <hr>
                    <p><b>Attendance: </b><span><b>A</b>=Absent</span> <span><b>P</b>=Present</span> <span><b>L</b>=Late</span></p>
                    <p><b>Reason Provided (for not attending): </b><span><b>O</b>=Other</span> <span><b>DI</b>=Data Issues</span> <span><b>NP</b>=None Provided</span></p>
                    <hr>
                    <p><b>Submission: </b><span><b>N(no)</b>=Did Not Submit</span> <span><b>Y(yes)</b>=Submitted</span></p>
                    <p><b>Reason Provided (for not submitting): </b><span><b>O</b>=Other</span> <span><b>DI</b>=Data Issues</span> <span><b>NW</b>=Did Not Write</span> <span><b>NP</b>=None Provided</span></p>
                    <hr>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("learner_report.pdf", ["Attachment" => false]);
?>

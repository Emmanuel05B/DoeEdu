<?php
require '../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');

// Retrieve POST variables for parent and learner ID
$parentId = isset($_POST['parentId']) ? $_POST['parentId'] : null;
$learner_id = isset($_POST['learnerId']) ? $_POST['learnerId'] : null;
$SubjectId = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : null;

// Fetch subject name based on SubjectId
$SubjectName = '';
switch ($SubjectId) {
    case 1:
        $SubjectName = 'Mathematics';
        break;
    case 2:
        $SubjectName = 'Physical Sciences';
        break;
    case 3:
        $SubjectName = 'Mathematics';
        break;
    case 4:
        $SubjectName = 'Physical Sciences';
        break;
    case 5:
        $SubjectName = 'Mathematics';
        break;
    case 6:
        $SubjectName = 'Physical Sciences';
        break;
    default:
        echo '<h1>Learners - Unknown Status</h1>';
        exit();
}

// Fetch parent details from the database
$psql = "SELECT * FROM parents WHERE ParentId = $parentId";
$presults = $connect->query($psql);

// Check if the query was successful
if (!$presults) {
    die('Error executing parent query: ' . $connect->error);
}

$pfinal = $presults->fetch_assoc();


// Fetch learner details
$learner_sql = "SELECT * FROM learners WHERE LearnerId = $learner_id";
$learner_results = $connect->query($learner_sql);

// Check if the query was successful
if (!$learner_results) {
    die('Error executing learner query: ' . $connect->error);
}

$final = $learner_results->fetch_assoc();

// Fetch teacher details
$userId = $_SESSION['user_id']; // for teacher
$tsql = "SELECT * FROM users WHERE Id = $userId";
$tresults = $connect->query($tsql);
$tfinal = $tresults->fetch_assoc();


// Fetch learner activity marks
$activity_sql = "
    SELECT 
        lam.ActivityId, 
        lam.MarksObtained,
        a.ActivityName,  
        a.MaxMarks,
        a.ChapterName,
        lam.DateAssigned
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ?
    ORDER BY lam.DateAssigned ASC
";

$stmt = $connect->prepare($activity_sql);
$stmt->bind_param('ii', $learner_id, $SubjectId); // Bind the learner_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if (!$result) {
    die('Error executing activity query: ' . $connect->error);
}

// Fetch the attendance and submission data for missed classes and activities
$attendance_submission_sql = "
    SELECT 
        lam.ActivityId, 
        lam.Attendance, 
        lam.AttendanceReason, 
        lam.Submission, 
        lam.SubmissionReason,
        a.ChapterName,
        a.ActivityName
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND (lam.Attendance = 'absent' OR lam.Submission = 'No') 
    AND a.SubjectId = ?  
    ORDER BY lam.DateAssigned ASC
";

$stmt2 = $connect->prepare($attendance_submission_sql);
$stmt2->bind_param('ii', $learner_id, $SubjectId); // Bind learner_id and SubjectId to the query
$stmt2->execute();
$attendance_submission_result = $stmt2->get_result();

// Check if the query was successful
if (!$attendance_submission_result) {
    die('Error executing attendance submission query: ' . $connect->error);
}

// Fetch total activities count for calculating percentage
$total_activities_sql = "
    SELECT COUNT(*) as total 
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ? 
";
$total_activities_stmt = $connect->prepare($total_activities_sql);
$total_activities_stmt->bind_param('ii', $learner_id, $SubjectId);
$total_activities_stmt->execute();
$total_activities_result = $total_activities_stmt->get_result();

// Check if the query was successful
if (!$total_activities_result) {
    die('Error executing total activities query: ' . $connect->error);
}

$total_activities = $total_activities_result->fetch_assoc()['total'];

// Calculate missed attendance and submissions
$missed_classes = 0;
$missed_activities = 0;
$stmt2->data_seek(0); // Reset result pointer
while ($row = $attendance_submission_result->fetch_assoc()) {
    if ($row['Attendance'] == 'absent') {
        $missed_classes++;
    }
    if ($row['Submission'] == 'No') {
        $missed_activities++;
    }
}

// Calculate attendance and submission rates
if ($total_activities > 0) {
    $attendance_rate = (($total_activities - $missed_classes) / $total_activities) * 100;
    $submission_rate = (($total_activities - $missed_activities) / $total_activities) * 100;
} else {
    $attendance_rate = 0;
    $submission_rate = 0;
}

// Prepare display variables
$numabsent = $missed_classes;
$submission_no_count = $missed_activities;

// Start capturing the HTML content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Report for: <?php echo $final['Name']; ?></h2>
    <small><?php echo date('Y-m-d'); ?></small>
    <p>Subject: <?php echo $SubjectName; ?></p>

    <div>
        <b>Learner Details:</b><br>
        Name: <?php echo $final['Name']; ?><br>
        Surname: <?php echo $final['Surname']; ?><br>
        Grade: <?php echo $final['Grade']; ?><br>
        Contact Number: <?php echo $final['ContactNumber']; ?><br>
        Email: <?php echo $final['Email']; ?>
    </div>

    <div>
        <b>Parent Details:</b><br>
        Title: <?php echo $pfinal['ParentTitle']; ?><br>
        Name: <?php echo $pfinal['ParentName']; ?><br>
        Surname: <?php echo $pfinal['ParentSurname']; ?><br>
        Email: <?php echo $pfinal['ParentEmail']; ?>
    </div>

    <hr>

    <div>
        <p class="lead">Attendance:</p>
        <table>
            <tr>
                <th>Attendance Rate:</th>
                <td><?php echo number_format($attendance_rate, 2); ?>%</td>
            </tr>
            <tr>
                <th>Classes Missed:</th>
                <td><?php echo $numabsent; ?> / <?php echo $total_activities; ?> classes</td>
            </tr>
        </table>
    </div>

    <div>
        <p class="lead">Activities Scores:</p>
        <table>
            <thead>
                <tr>
                    <th>Activity Name</th>
                    <th>Marks</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($activity = $result->fetch_assoc()) {
                        $percentage = ($activity['MarksObtained'] / $activity['MaxMarks']) * 100;
                ?>
                <tr>
                    <td><?php echo $activity['ActivityName']; ?></td>
                    <td><?php echo $activity['MarksObtained']; ?> / <?php echo $activity['MaxMarks']; ?></td>
                    <td><?php echo number_format($percentage, 2); ?>%</td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='3'>No activities found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div>
        <p class="lead">Financial Information:</p>
        <table>
            <thead>
                <tr>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo number_format($TotalFees, 2); ?></td>
                    <td><?php echo number_format($TotalPaid, 2); ?></td>
                    <td><?php echo number_format($TotalOwe, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$html = ob_get_clean(); // Capture the HTML output

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Set Paper size and Orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Stream the generated PDF to the browser
$dompdf->stream("learner_report.pdf", ["Attachment" => false]); // Change to true to force download
?>

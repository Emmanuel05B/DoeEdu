<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Report</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content-wrapper {
            padding: 20px;
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
        .lead {
            font-weight: bold;
        }
        .top-right-image {
            position: absolute;
            top: 0;
            right: 0;
            max-height: 130px;
            margin-top: 0px;
            margin-right: 30px;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../partials/header.php");
include(__DIR__ . "/../partials/mainsidebar.php");
include(__DIR__ . "/../../partials/connect.php");

$learner_id = isset($_GET['lid']) ? intval($_GET['lid']) : null;
$SubjectId = isset($_GET['val']) ? intval($_GET['val']) : 0;

// Fetch subject name dynamically
$SubjectName = '';
$stmtSub = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmtSub->bind_param("i", $SubjectId);
$stmtSub->execute();
$resSub = $stmtSub->get_result();
if ($resSub && $resSub->num_rows > 0) {
    $rowSub = $resSub->fetch_assoc();
    $SubjectName = $rowSub['SubjectName'];
} else {
    echo '<h1>Learners - Unknown Subject</h1>';
    exit();
}
$stmtSub->close();

// Fetch learner info
if ($learner_id) {
    $stmtLearner = $connect->prepare("SELECT * FROM learners WHERE LearnerId = ?");
    $stmtLearner->bind_param("i", $learner_id);
    $stmtLearner->execute();
    $presults = $stmtLearner->get_result();
    $pfinal = $presults->fetch_assoc();
    $stmtLearner->close();

    $stmtUser = $connect->prepare("SELECT * FROM users WHERE Id = ?");
    $stmtUser->bind_param("i", $learner_id);
    $stmtUser->execute();
    $results = $stmtUser->get_result();
    $final = $results->fetch_assoc();
    $stmtUser->close();
}

// Fetch teacher info
$userId = $_SESSION['user_id'];
$stmtTeacher = $connect->prepare("SELECT * FROM users WHERE Id = ?");
$stmtTeacher->bind_param("i", $userId);
$stmtTeacher->execute();
$tresults = $stmtTeacher->get_result();
$tfinal = $tresults->fetch_assoc();
$stmtTeacher->close();

// Fetch learner activities
$activity_sql = "
    SELECT lam.ActivityId, lam.MarksObtained,
           a.ActivityName, a.MaxMarks, a.ChapterName, lam.DateAssigned
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ?
    ORDER BY lam.DateAssigned ASC
";
$stmt = $connect->prepare($activity_sql);
$stmt->bind_param('ii', $learner_id, $SubjectId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch missed attendance and submissions
$attendance_sql = "
    SELECT lam.ActivityId, lam.Attendance, lam.AttendanceReason,
           lam.Submission, lam.SubmissionReason,
           a.ChapterName, a.ActivityName
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ? AND (lam.Attendance='absent' OR lam.Submission='No')
    ORDER BY lam.DateAssigned ASC
";
$stmt2 = $connect->prepare($attendance_sql);
$stmt2->bind_param('ii', $learner_id, $SubjectId);
$stmt2->execute();
$attendance_submission_result = $stmt2->get_result();

// Calculate totals
$total_activities_sql = "
    SELECT COUNT(*) as total 
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ? AND a.SubjectId = ?
";
$stmtTotal = $connect->prepare($total_activities_sql);
$stmtTotal->bind_param('ii', $learner_id, $SubjectId);
$stmtTotal->execute();
$total_activities_result = $stmtTotal->get_result();
$total_activities = $total_activities_result->fetch_assoc()['total'];

// Calculate missed classes and submissions
$missed_classes = 0;
$missed_activities = 0;
$attendance_submission_result->data_seek(0);
while ($row = $attendance_submission_result->fetch_assoc()) {
    if ($row['Attendance'] === 'absent') $missed_classes++;
    if ($row['Submission'] === 'No') $missed_activities++;
}

$attendance_rate = ($total_activities > 0) ? (($total_activities - $missed_classes)/$total_activities)*100 : 0;
$submission_rate = ($total_activities > 0) ? (($total_activities - $missed_activities)/$total_activities)*100 : 0;

$numabsent = $missed_classes;
$submission_no_count = $missed_activities;

// Fetch financial info for the learner
$fin_sql = "
    SELECT 
        TotalFees, 
        TotalPaid, 
        Balance AS TotalOwe, 
        PaymentStatus, 
        DueDate, 
        LastPaymentDate
    FROM finances
    WHERE LearnerId = ?
";
$stmtFin = $connect->prepare($fin_sql);
$stmtFin->bind_param("i", $learner_id);
$stmtFin->execute();
$fin_result = $stmtFin->get_result();
$financial_info = $fin_result->fetch_assoc();
$stmtFin->close();

// Extract financial values safely
$TotalFees  = $financial_info['TotalFees'] ?? 0;
$TotalPaid  = $financial_info['TotalPaid'] ?? 0;
$TotalOwe   = $financial_info['TotalOwe'] ?? 0;
$PaymentStatus = $financial_info['PaymentStatus'] ?? 'Unpaid';
$DueDate = $financial_info['DueDate'] ?? '-';
$LastPaymentDate = $financial_info['LastPaymentDate'] ?? '-';

?>

<div class="content-wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <div>
                    <p><strong>Registration No:</strong> 2022/735117/07</p>
                    <p><strong>Residential Address:</strong> 50188 Makoshala, Block E, Phokoane, Nebo, Limpopo, 1059</p>
                    <p><strong>Telephone:</strong> 081 461 8178</p>
                    <p><strong>Email:</strong> <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>
                </div><br>
                <h2 class="page-header" style="text-align:center;">
                    Report for: <?php echo $final['Name']; ?><br>
                    Subject: <?php echo $SubjectName; ?>
                    <img src="../images/westtt.png" alt="Image" class="top-right-image">
                </h2>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <b>Learner Details:</b><br>
                Name: <?php echo $final['Name']; ?><br>
                Surname: <?php echo $final['Surname']; ?><br>
                Grade: <?php echo $pfinal['Grade']; ?><br>
                Contact: <?php echo $final['Contact']; ?><br>
                Email: <?php echo $final['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Tutor Details:</b><br>
                Name: <?php echo $tfinal['Name']; ?><br>
                Surname: <?php echo $tfinal['Surname']; ?><br>
                Email: <?php echo $tfinal['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Parent Details:</b><br>
                Title: <?php echo $pfinal['ParentTitle']; ?><br>
                Name: <?php echo $pfinal['ParentName']; ?><br>
                Surname: <?php echo $pfinal['ParentSurname']; ?><br>
                Email: <?php echo $pfinal['ParentEmail']; ?>
            </div>
        </div>
        <hr><br>

        <!-- Attendance and Submission Tables -->
        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Attendance:</p>
                <table class="table">
                    <tr>
                        <th>Attendance Rate:</th>
                        <td><?php echo number_format($attendance_rate, 2); ?>%</td>
                    </tr>
                    <tr>
                        <th>Classes Missed:</th>
                        <td><?php echo $numabsent; ?>/<?php echo $total_activities; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6">
                <p class="lead">Submission:</p>
                <table class="table">
                    <tr>
                        <th>Submission Rate:</th>
                        <td><?php echo number_format($submission_rate, 2); ?>%</td>
                    </tr>
                    <tr>
                        <th>Activities Missed:</th>
                        <td><?php echo $submission_no_count; ?>/<?php echo $total_activities; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Activity Scores Table -->
        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Activities Scores:</p>
                <table class="table">
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
                            $result->data_seek(0);
                            while ($activity = $result->fetch_assoc()) {
                                $percentage = ($activity['MarksObtained'] / $activity['MaxMarks']) * 100;
                                echo "<tr>";
                                echo "<td><b>{$activity['ChapterName']} <span style='color: blue;'>{$activity['ActivityName']}</span></b></td>";
                                echo "<td>{$activity['MarksObtained']} / {$activity['MaxMarks']}</td>";
                                echo "<td>".number_format($percentage, 2)."%</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No activities found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-6">
                <p class="lead">Missed Attendance and Submissions:</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activity Name</th>
                            <th>Reason</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($attendance_submission_result->num_rows > 0) {
                            $attendance_submission_result->data_seek(0);
                            while ($row = $attendance_submission_result->fetch_assoc()) {
                                if ($row['Attendance'] === 'absent') {
                                    echo "<tr>";
                                    echo "<td><b>{$row['ChapterName']} <span style='color: blue;'>{$row['ActivityName']}</span></b></td>";
                                    echo "<td>".htmlspecialchars($row['AttendanceReason'])."</td>";
                                    echo "<td>Did Not Attend Class</td>";
                                    echo "</tr>";
                                }
                                if ($row['Submission'] === 'No') {
                                    echo "<tr>";
                                    echo "<td><b>{$row['ChapterName']} <span style='color: blue;'>{$row['ActivityName']}</span></b></td>";
                                    echo "<td>".htmlspecialchars($row['SubmissionReason'])."</td>";
                                    echo "<td>Did Not Submit Work</td>";
                                    echo "</tr>";
                                }
                            }
                        } else {
                            echo "<tr><td colspan='3'>No missed records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Overall Performance -->
        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Overall Performance Status:</p>
                <table class="table">
                    <tbody>
                        <?php
                        $total_marks = 0; $total_max = 0;
                        $result->data_seek(0);
                        while ($act = $result->fetch_assoc()) {
                            $total_marks += $act['MarksObtained'];
                            $total_max += $act['MaxMarks'];
                        }
                        $overall_score = ($total_max > 0) ? ($total_marks / $total_max) * 100 : 0;

                        if ($overall_score >= 90) { $category="Excellent"; $comment="Outstanding performance!"; }
                        elseif ($overall_score >= 70) { $category="Good"; $comment="Good performance, keep pushing!"; }
                        elseif ($overall_score >= 50) { $category="Fair"; $comment="Fair performance, room for improvement."; }
                        else { $category="Poor"; $comment="Poor performance, focus more!"; }

                        if ($attendance_rate < 75) $comment .= " Attendance below 75%.";
                        if ($submission_rate < 75) $comment .= " Submission rate needs improvement.";
                        ?>
                        <tr>
                            <td style="text-align:center;">
                                <b><?php echo $category; ?></b><br>
                                Overall Score: <?php echo number_format($overall_score,2); ?>%<br>
                                Attendance Rate: <?php echo number_format($attendance_rate,2); ?>%<br>
                                Submission Rate: <?php echo number_format($submission_rate,2); ?>%<br>
                                <i><?php echo $comment; ?></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Financial Information -->
            <!-- Financial Information -->
<div class="col-xs-6">
    <p class="lead">Financial Information:</p>
    <table class="table">
        <thead>
            <tr>
                <th>Total Fees</th>
                <th>Total Paid</th>
                <th>Balance</th>
                <th>Payment Status</th>
                <th>Due Date</th>
                <th>Last Payment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmtFin = $connect->prepare("
                SELECT TotalFees, TotalPaid, Balance, PaymentStatus, DueDate, LastPaymentDate
                FROM finances
                WHERE LearnerId = ?
            ");
            $stmtFin->bind_param("i", $learner_id);
            $stmtFin->execute();
            $fin_result = $stmtFin->get_result();
            
            if ($fin_result->num_rows > 0) {
                while ($fin = $fin_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>R " . number_format($fin['TotalFees'], 2) . "</td>";
                    echo "<td>R " . number_format($fin['TotalPaid'], 2) . "</td>";
                    echo "<td>R " . number_format($fin['Balance'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($fin['PaymentStatus']) . "</td>";
                    echo "<td>" . ($fin['DueDate'] ? $fin['DueDate'] : '-') . "</td>";
                    echo "<td>" . ($fin['LastPaymentDate'] ? $fin['LastPaymentDate'] : '-') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No financial records found.</td></tr>";
            }
            $stmtFin->close();
            ?>
        </tbody>
    </table>
</div>

        </div>

        <!-- PDF Button -->
        <div class="row no-print">
            <div class="col-xs-12">
                <form action="generate_pdf.php" method="post" style="display:inline;">
                    <input type="hidden" name="learnerId" value="<?php echo $learner_id; ?>">
                    <input type="hidden" name="subjectId" value="<?php echo $SubjectId; ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-download"></i> Generate PDF
                    </button>
                </form>
            </div>
        </div>

    </section>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>

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
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include("adminpartials/head.php");
include("adminpartials/header.php");
include("adminpartials/mainsidebar.php");

include('../partials/connect.php');

$parentId = isset($_GET['pid']) ? $_GET['pid'] : null;
$learner_id = isset($_GET['lid']) ? $_GET['lid'] : null;

// Fetch learner details
if ($parentId) {
    $psql = "SELECT * FROM parents WHERE ParentId = $parentId";
    $presults = $connect->query($psql);
    $pfinal = $presults->fetch_assoc();
}

include('../admin/newshared.php');

// SQL to fetch the activities and marks for the learner
$activity_sql = "
    SELECT 
        lam.ActivityId, 
        lam.MarksObtained, 
        a.MaxMarks,
        lam.DateAssigned
    FROM learneractivitymarks lam
    JOIN activities a ON lam.ActivityId = a.ActivityId
    WHERE lam.LearnerId = ?
    ORDER BY lam.DateAssigned ASC
";

$stmt = $connect->prepare($activity_sql);
$stmt->bind_param('i', $learner_id); // Bind the learner_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the total number of activities for calculating percentage
$total_activities_sql = "SELECT COUNT(*) as total FROM learneractivitymarks WHERE LearnerId = ?";
$total_activities_stmt = $connect->prepare($total_activities_sql);
$total_activities_stmt->bind_param('i', $learner_id);
$total_activities_stmt->execute();
$total_activities_result = $total_activities_stmt->get_result();
$total_activities = $total_activities_result->fetch_assoc()['total'];
?>

<div class="content-wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Report for: <?php echo $final['Name']; ?>
                    <small class="pull-right"><?php echo 'today sdate'; ?></small>
                </h2>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <b>Learner Details:</b><br>
                <b>Name:</b> <?php echo $final['Name']; ?><br>
                <b>Surname:</b> <?php echo $final['Surname']; ?><br>
                <b>Grade:</b> <?php echo $final['Grade']; ?><br>
                <b>Contact Number:</b> <?php echo $final['ContactNumber']; ?><br>
                <b>Email:</b> <?php echo $final['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Teacher Details:</b><br>
                <b>Name:</b> <?php echo $tfinal['Name']; ?><br>
                <b>Surname:</b> <?php echo $tfinal['Surname']; ?><br>
                <b>Email:</b> <?php echo $tfinal['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Parent Details:</b><br>
                <b>Title:</b> <?php echo $pfinal['ParentTitle']; ?><br>
                <b>Name:</b> <?php echo $pfinal['ParentName']; ?><br>
                <b>Surname:</b> <?php echo $pfinal['ParentSurname']; ?><br>
                <b>Email:</b> <?php echo $pfinal['ParentEmail']; ?>
            </div>
        </div>
        
        <hr><br>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Attendance:</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Attendance Rate:</th>
                            <td><?php echo $attendancerate; ?>%</td>
                        </tr>
                        <tr>
                            <th>Classes missed:</th>
                            <td><?php echo $numabsent; ?>/<?php echo $total; ?> classes</td>
                        </tr>
                    </table>
                </div>
            </div>
             
            <div class="col-xs-6">
                <p class="lead">Submission:</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Submission rate:</th>
                            <td><?php echo $submissionrate; ?>%</td>
                        </tr>
                        <tr>
                            <th>Activities Missed:</th>
                            <td><?php echo $submission_no_count; ?>/<?php echo $total; ?> Activities</td>

                        </tr>
                    </table>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Activities Scores:</p>
                <div class="table-responsive">
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
                                while ($activity = $result->fetch_assoc()) {
                                    // Calculate percentage for each activity
                                    $percentage = ($activity['MarksObtained'] / $activity['MaxMarks']) * 100;
                            ?>
                            <tr>
                                <td><b>Activity <?php echo $activity['ActivityId']; ?></b></td>
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
            </div>

            <div class="col-xs-6">
                <p class="lead">Classroom Transition</p>
                <div class="table-responsive">
                    <table class="table">

                        <tr>
                            <th style="width:50%">Day 4:</th>
                            <td><?php echo 85; ?></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Day 3: </th>
                            <td><?php echo 95; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nr</th>
                            <th>Question</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>yyyyyyyyyyyyyyy</td>
                            <td><?php echo 52; ?></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <form action="generate_pdf.php" method="post" style="display:inline;">
                    <input type="hidden" name="parentId" value="<?php echo $parentId; ?>">
                    <input type="hidden" name="learnerId" value="<?php echo $learner_id; ?>">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-download"></i> Generate PDF
                    </button>
                    
                </form>
            </div>
        </div>
    </section>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
</body>
</html>

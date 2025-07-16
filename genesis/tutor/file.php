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
            max-height: 130px; /* Adjust the size of the image */
            margin-top: 0px; /* Adjust the space from the top */
            margin-right: 30px; /* Adjust the space from the right */
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

    include("tutorpartials/head.php");
    include("tutorpartials/header.php");
    include("tutorpartials/mainsidebar.php");

    include('../partials/connect.php');

    $learner_id = isset($_GET['lid']) ? $_GET['lid'] : null;

    $SubjectId = intval($_GET['val']); // Get the subject value, ensure it's an integer

   // Fetch subject name from DB
    $SubjectName = '';
    $subjectStmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
    $subjectStmt->bind_param("i", $SubjectId);
    $subjectStmt->execute();
    $subjectStmt->bind_result($SubjectName);
    $subjectStmt->fetch();
    $subjectStmt->close();

    if (empty($SubjectName)) {
        echo '<h1>Learners - Unknown Subject</h1>';
        exit();
    }

    // Fetch learner details for parent
    if ($learner_id) {
        $psql = "SELECT * FROM learners WHERE LearnerId = $learner_id";
        $presults = $connect->query($psql);
        $pfinal = $presults->fetch_assoc();
    }

    //get basic learner details from users
    if ($learner_id) {
        $sql = "SELECT * FROM users WHERE Id = $learner_id"; 
        $results = $connect->query($sql);
        $final = $results->fetch_assoc();
    }

    $userId = $_SESSION['user_id']; // for teacher
    $tsql = "SELECT * FROM users WHERE Id = $userId";
    $tresults = $connect->query($tsql);
    $tfinal = $tresults->fetch_assoc();

    //Sql for the online quizzes
    $sql3 = "
    SELECT 
        oa.Id AS ActivityId,
        oa.Topic,
        oa.Title,
        oa.TotalMarks,
        oa.DueDate,
        COUNT(CASE WHEN la.SelectedAnswer = oq.CorrectAnswer THEN 1 END) AS MarksObtained
    FROM onlineactivities oa
    LEFT JOIN onlinequestions oq ON oq.ActivityId = oa.Id
    LEFT JOIN learneranswers la ON la.QuestionId = oq.Id AND la.UserId = ?
    WHERE oa.SubjectName = ? AND oa.DueDate < NOW()
    GROUP BY oa.Id
    ORDER BY oa.DueDate DESC
    ";

    $stmt3 = $connect->prepare($sql3);
    $stmt3->bind_param("is", $learner_id, $SubjectId);
    $stmt3->execute();
    $result_quizzes = $stmt3->get_result();


    // SQL to fetch the activities and marks for the learner
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

    // SQL to fetch the attendance and submission reasons where learner did not attend or submit
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

    // Fetch the total number of activities for calculating percentage
    $total_activities_sql = "
        SELECT COUNT(*) as total 
        FROM learneractivitymarks lam
        JOIN activities a ON lam.ActivityId = a.ActivityId
        WHERE lam.LearnerId = ? AND a.SubjectId = ? 
    ";
    $total_activities_stmt = $connect->prepare($total_activities_sql);
    $total_activities_stmt->bind_param('ii', $learner_id, $SubjectId); // Bind learner_id and SubjectId to the query
    $total_activities_stmt->execute();
    $total_activities_result = $total_activities_stmt->get_result();
    $total_activities = $total_activities_result->fetch_assoc()['total'];

    // Calculate missed attendance and submissions
    $missed_classes = 0;
    $missed_activities = 0;
    $stmt2->data_seek(0);  // Reset result pointer
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
?>

<div class="content-wrapper">
    <section class="invoice">
    <div class="row">
    <div class="col-xs-12">
         <!-- Contact Info Section -->
            <div>
                <p><strong>Registration No:</strong> 2022/735117/07</p>
                <p><strong>Residential Address:</strong> 50188 Makoshala, Block E, Phokoane, Nebo, Limpopo, 1059</p>
                <p><strong>Telephone:</strong> 081 461 8178</p>
                <p><strong>Email:</strong> <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>
            </div><br>
        <h2 class="page-header">
            <span style="display: block; text-align: center;">Report for: <?php echo $final['Name']; ?></span>
            <span style="display: block; text-align: center;">Subject: <?php echo $SubjectName; ?></span>

            <!-- Adding the image -->
            <img src="images/westtt.png" alt="Image" class="top-right-image">
        </h2>
    </div>
</div>

        <!-- Users detatails Table -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <b>Learner Details:</b><br>
                <b>Name:</b> <?php echo $final['Name']; ?><br>
                <b>Surname:</b> <?php echo $final['Surname']; ?><br>
                <b>Grade:</b> <?php echo $pfinal['Grade']; ?><br>
                <b>Contact Number:</b> <?php echo $final['Contact']; ?><br>
                <b>Email:</b> <?php echo $final['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Tutor Details:</b><br>
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

        <!-- Attendance and submission tables Table -->
        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Attendance:</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Attendance Rate:</th>
                            <td><?php echo number_format($attendance_rate, 2); ?>%</td>
                        </tr>
                        <tr>
                            <th>Classes missed:</th>
                            <td><?php echo $numabsent; ?>/<?php echo $total_activities; ?> classes</td>
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
                            <td><?php echo number_format($submission_rate, 2); ?>%</td>
                        </tr>
                        <tr>
                            <th>Activities Missed:</th>
                            <td><?php echo $submission_no_count; ?>/<?php echo $total_activities; ?> Activities</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><br>

        <div class="row">

            <!-- Combined physical Activities Table -->
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
                            <td><b><?php echo $activity['ChapterName']; ?> <span style="color: blue;"><?php echo $activity['ActivityName']; ?></span></b></td>
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

            <!-- Combined Online Quizzes Table -->
            <div class="col-xs-6">
                <p class="lead">Quizzes Scores:</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Quiz Name</th>
                                <th>Marks</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_quizzes->num_rows > 0) {
                                while ($activity = $result_quizzes->fetch_assoc()) {
                                    $obtained = intval($activity['MarksObtained']);
                                    $max = intval($activity['TotalMarks']);
                                    $percentage = ($max > 0) ? ($obtained / $max) * 100 : 0;

                                    echo "<tr>";
                                    echo "<td><b>" . htmlspecialchars($activity['Topic']) . " <span style='color: blue;'>" . htmlspecialchars($activity['Title']) . "</span></b></td>";
                                    echo "<td>" . $obtained . " / " . $max . "</td>";
                                    echo "<td>" . number_format($percentage, 2) . "%</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No quizzes found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Combined Attendance and Submission Reasons Table -->
            <div class="col-xs-6">
                <p class="lead">Missed Attendance and Submissions Reasons:</p>
                <div class="table-responsive">
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
                                // Reset the pointer before re-looping through the result set
                                $attendance_submission_result->data_seek(0); // <-- Reset pointer to the beginning

                                // Loop through the result set and add rows to the table
                                while ($row = $attendance_submission_result->fetch_assoc()) {
                                    // Safely access values and provide fallback if null or undefined
                                    $activityId = isset($row['ActivityId']) ? $row['ActivityId'] : 'N/A';
                                    $attendance = isset($row['Attendance']) ? $row['Attendance'] : 'No Attendance';
                                    $submission = isset($row['Submission']) ? $row['Submission'] : 'No Submission';
                                    $attendanceReason = isset($row['AttendanceReason']) ? $row['AttendanceReason'] : 'No Reason';
                                    $submissionReason = isset($row['SubmissionReason']) ? $row['SubmissionReason'] : 'No Reason';

                                    // Add a row for missed attendance
                                    if ($attendance == 'absent') {
                                        echo "<tr>";
                                        echo "<td><b>{$row['ChapterName']} <span style='color: blue;'>{$row['ActivityName']}</span></b></td>";
                                        echo "<td>" . htmlspecialchars($attendanceReason) . "</td>";
                                        echo "<td>Did Not Attend Class</td>";
                                        echo "</tr>";
                                    }

                                    // Add a row for missed submission
                                    if ($submission == 'No') {
                                        echo "<tr>";
                                        echo "<td><b>{$row['ChapterName']} <span style='color: blue;'>{$row['ActivityName']}</span></b></td>";
                                        echo "<td>" . htmlspecialchars($submissionReason) . "</td>";
                                        echo "<td>Did Not Submit Work</td>";
                                        echo "</tr>";
                                    }
                                }
                            } else {
                                // If no results, show a message
                                echo "<tr><td colspan='3'>No missed attendance or submission records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div><br>


            <!-- Overall Performance Status: Table -->
            <div class="col-xs-6">
                <p class="lead">Overall Performance Status:</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="display: inline-block; text-align: center; width: 100%;">Performance Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Initialize overall score
                            $total_marks_obtained = 0;
                            $total_max_marks = 0;
                            $activity_count = 0;

                            // Calculate total marks obtained and max marks for activity performance
                            $result->data_seek(0); // Reset result pointer for calculation
                            while ($activity = $result->fetch_assoc()) {
                                $total_marks_obtained += $activity['MarksObtained'];
                                $total_max_marks += $activity['MaxMarks'];
                                $activity_count++;
                            }

                            // Calculate Overall Activity Score Percentage
                            $overall_activity_score = ($total_max_marks > 0) ? ($total_marks_obtained / $total_max_marks) * 100 : 0;

                            // Determine the performance category based on overall activity score
                            if ($overall_activity_score >= 90) {
                                $performance_category = 'Excellent';
                                $comment = "Outstanding performance! Keep up the great work!";
                            } elseif ($overall_activity_score >= 70) {
                                $performance_category = 'Good';
                                $comment = "Good performance. Keep pushing to reach even higher levels!";
                            } elseif ($overall_activity_score >= 50) {
                                $performance_category = 'Fair';
                                $comment = "You’ve done well, but there’s room for improvement. Stay focused!";
                            } else {
                                $performance_category = 'Poor';
                                $comment = "There’s significant room for improvement. Focus on your studies and submit work on time!";
                            }

                            // Combine attendance and submission rates into the comment
                            if ($attendance_rate < 75) {
                                $comment .= " Your attendance rate is below 75%. Try to attend all classes for better learning.";
                            } else {
                                $comment .= " Your attendance rate is great!";
                            }

                            if ($submission_rate < 75) {
                                $comment .= " Your submission rate needs improvement.";
                            } else {
                                $comment .= " Keep up the good work with your submissions!";
                            }
                            ?>
                            <tr>
                                <td style="display: inline-block; text-align: center; width: 100%;">
                                    <b><?php echo $performance_category; ?></b><br>
                                    Overall Activity Score: <?php echo number_format($overall_activity_score, 2); ?>%<br>
                                    Attendance Rate: <?php echo number_format($attendance_rate, 2); ?>%<br>
                                    Submission Rate: <?php echo number_format($submission_rate, 2); ?>%<br>
                                    <br>
                                    <i><?php echo $comment; ?></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br>


            <!-- Financial Information: Table -->
            <div class="col-xs-6">
                <p class="lead">Financial Information:</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Total Fees</th>
                                <th>Total Paid</th>
                                <th>Total Owe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // SQL to fetch financial information
                            $sql = "SELECT 
                                        SUM(TotalFees) AS TotalFees,
                                        SUM(TotalPaid) AS TotalPaid,
                                        SUM(CASE WHEN TotalOwe > 0 THEN TotalOwe ELSE 0 END) AS TotalOwe
                                    FROM learners
                                    WHERE LearnerId = ?";
                            
                            // Prepare and execute the query
                            $stmt = $connect->prepare($sql);
                            $stmt->bind_param('i', $learner_id); // Bind the learner_id to the query
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $financial_info = $result->fetch_assoc();
                            
                            // Extract financial values
                            $TotalFees = isset($financial_info['TotalFees']) ? $financial_info['TotalFees'] : 0;
                            $TotalPaid = isset($financial_info['TotalPaid']) ? $financial_info['TotalPaid'] : 0;
                            $TotalOwe = isset($financial_info['TotalOwe']) ? $financial_info['TotalOwe'] : 0;

                            // Display financial information in the table
                            echo "<tr>";
                            echo "<td><b>R " . number_format($TotalFees, 2) . "</b></td>";
                            echo "<td>R " . number_format($TotalPaid, 2) . "</td>";
                            echo "<td>R " . number_format($TotalOwe, 2) . "</td>";
                            echo "</tr>";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div><br>


        

        

        <div class="row no-print">
            <div class="col-xs-12">
                <form action="generate_pdf.php" method="post" style="display:inline;">
                    <input type="hidden" name="parentId" value="<?php echo $parentId; ?>">
                    <input type="hidden" name="learnerId" value="<?php echo $learner_id; ?>">
                    <input type="hidden" name="subjectId" value="<?php echo $_GET['val'] ?>">
                    

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

<?php
    require '../../vendor/autoload.php';
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $imagePath = 'images/westtt.png'; // adjust if needed
    $imageData = base64_encode(file_get_contents($imagePath));
    $src = 'data:image/png;base64,' . $imageData;

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        header("Location: ../common/login.php");
        exit();
    }

    include('../partials/connect.php');


    $learner_id = isset($_POST['learnerId']) ? $_POST['learnerId'] : null;
    $SubjectId = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : null;

    // Fetch subject name based on SubjectId
    $SubjectName = '';
    switch ($SubjectId) {
        case 1:
            $SubjectName = 'Mathematics';
            $grade = '10';
            $tutor = 'Ms Malesela';  
            $name = 'Shirley';
            $sur = 'Rakau';
            $email = 'shirleytidimalo03@gmail.com';

            break;
        case 2:
            $SubjectName = 'Mathematics';
            $grade = '11';
            $tutor = 'Ms Khumalo';  //fake
            $name = 'Naledi';
            $sur = 'Khumalo';
            $email = 'nkhumalo@doe.co.za';

            break;
        case 3:
         
            $SubjectName = 'Mathematics';
            $grade = '12';
            $tutor = 'Ms Matlaisane';
            $name = 'Siphumelele';
            $sur = 'Matlaisane';
            $email = 'siphumelelematlaisane@gmail.com';

            break;
        case 4:
            $SubjectName = 'Physical Sciences';
            $grade = '11';
            $tutor = 'Mr Boshielo';
            $name = 'Emmanuel';
            $sur = 'Boshielo';
            $email = 'emahlwele05@gmail.com';
            break;
        case 5:
            
            $SubjectName = 'Physical Sciences';
            $grade = '12';
            $tutor = 'Mr Mamogobo';
            $name = 'Sydney';
            $sur = 'Mamogobo';
            $email = 'mamogobodsydney@gmail.com';

            break;
        case 6:
            $SubjectName = 'Physical Sciences';
            $grade = '10';
            $tutor = 'Mr Boshielo';
            $name = 'Emmanuel';
            $sur = 'Boshielo';
            $email = 'emahlwele05@gmail.com';
            break;
        default:
            echo '<h1>Learners - Unknown Status</h1>';
            exit();
    }

    // Fetch parent details from the database
    // Fetch learner details for parent

        $psql = "SELECT * FROM learners WHERE LearnerId = $learner_id";
        $presults = $connect->query($psql);

        // Check if the query was successful
        if (!$presults) {
            die('Error executing parent query: ' . $connect->error);
        }

        $pfinal = $presults->fetch_assoc();


    // Fetch learner details from users
    $learner_sql = "SELECT * FROM users WHERE Id = $learner_id";
    $learner_results = $connect->query($learner_sql);

    // Check if the query was successful
    if (!$learner_results) {
        die('Error executing learner query: ' . $connect->error);
    }
    $final = $learner_results->fetch_assoc();

    /*/ Fetch teacher details.............unneeded
    $userId = $_SESSION['user_id']; // for teacher
    $tsql = "SELECT * FROM users WHERE Id = $userId";
    $tresults = $connect->query($tsql);
    $tfinal = $tresults->fetch_assoc();   */


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
        .top-left-image {
           
            position: absolute;
            top: 0;
            left: 0;
            max-height: 160px; /* Keep image size */
            margin-top: -70px; /* Space from top */
            margin-left: 50px; /* Space from left */
        }
    </style>
</head>

<body>

        <div>
            <table class="table">
                        <tbody>
                           
                            <tr>
                                <td>
                                     <div>
                                        <!-- Adding the image -->
                                      <img src="<?= $src ?>" alt="Image" class="top-left-image" />
                                    </div>                            
                                </td>

                                <td>
                                    <div>
                                    <p><strong>Registration No:</strong> 2022/735117/07</p>
                                    <p><strong>Telephone:</strong> 081 461 8178</p>
                                    <p><strong>Email:</strong> <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>
                                    </div>                             
                                </td>

                            </tr>

                        </tbody>
            </table>

        </div><hr>


        <h4 style="display: block; text-align: center;"><?php echo $final['Name']; ?>'s Report </h4>
<div style="text-align: center;">
  <span><b>Subject: </b><?php echo $SubjectName; ?></span>
  &nbsp;&nbsp;&nbsp;&nbsp; <!-- adds some space -->
  <span><b>Generated on: </b><?php echo date('Y-m-d'); ?></span>
</div>
<hr><br>



        <div>
            <table class="table2">
                        <tbody>
                       
                            <tr>
                                <td>

                                    <div class="col-xs-6">
                                        <p class="lead">Learner Details:</p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <p><b>Name: </b><span><?php echo $final['Name']; ?></span>
                                                        <p><b>Surname: </b><span"><?php echo $final['Surname']; ?></span></p>
                                                        <p><b>Email: </b><span><?php echo $final['Email']; ?></span></p>
                                                    </td>
                                                </tr>
                                              
                                            </table>
                                        </div>
                                    </div>
                          
                                </td>

                                <td>

                                    <div class="col-xs-6">
                                        <p class="lead">Tutor Details:</p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <p><b>Name: </b><span><?php echo $name; ?></span>
                                                        <p><b>Surname: </b><span><?php echo $sur; ?></span></p>
                                                        <p><b>Email: </b><span><?php echo $email; ?></span></p>
                                                    </td>
                                                </tr>
                                              
                                            </table>
                                        </div>
                                    </div>
                                 
                                </td>

                            </tr>

                        </tbody>
            </table>

        </div><br>




    <!-- Attandance and submission Tables below-->
    <div>
        
        <table class="table">
                        <tbody>
                            <tr>
                                <!-- AttandanceTable -->
                                <td>
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
                                </td>

                                <!--  Submission Table below-->
                                <td>
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
                                </td>

                            </tr>

                        </tbody>
        </table>

    </div><br>
    
    <!-- Scores Table below-->
    <div>
        <table class="table">
               <!-- Combined Attendance and Submission Reasons Table  below -->
               <tbody>          
                    <tr>
                        <td>
                                   
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
                                                                           echo "<td><b>{$row['ChapterName']}</b> <span>{$row['ActivityName']}</span></td>";
                                                                           echo "<td>" . htmlspecialchars($attendanceReason) . "</td>";
                                                                           echo "<td>Did Not Attend Class</td>";
                                                                           echo "</tr>";
                                                                       }
           
                                                                       // Add a row for missed submission
                                                                       if ($submission == 'No') {
                                                                           echo "<tr>";
                                                                           echo "<td><b>{$row['ChapterName']}</b> <span>{$row['ActivityName']}</span></td>";
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
                        </td>
                    </tr>
           
                </tbody>

            <tbody>
                           
                <tr>
                    <td>
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
                                                    <td><b><?php echo $activity['ChapterName']; ?></b> <span><?php echo $activity['ActivityName']; ?></span></td>
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
                    </td>
                </tr>

            </tbody>

  
        </table>
    </div><br>



                        <div class="col-xs-6">
                            <p class="lead">Overall Performance Status:</p>
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

                        </div><br>




    <div>
        <table class="table">
            <tr>
                <td>
                    
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
                </td>
            </tr>
        </table>
        
    </div><br>



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

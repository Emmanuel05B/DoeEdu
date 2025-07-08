<?php

include('../partials/connect.php');


if ($learner_id) {
    $sql = "SELECT * FROM users WHERE Id = $learner_id";  //with the updated database
    $results = $connect->query($sql);
    $final = $results->fetch_assoc();
}

$userId = $_SESSION['user_id']; // for teacher
$tsql = "SELECT * FROM users WHERE Id = $userId";
$tresults = $connect->query($tsql);
$tfinal = $tresults->fetch_assoc();

/*   for old scores from auticare
// Query to get total appearances
$sql_total = "SELECT COUNT(*) AS total_appearances FROM learneractivitymarks WHERE LearnerId = $learner_id;";
$result_total = $connect->query($sql_total);
$total = $result_total->fetch_assoc();

// Query to get present count (including late)
$sql_present = "SELECT COUNT(*) AS present_count FROM learneractivitymarks WHERE LearnerId = $learner_id AND Attendance IN ('present', 'late');";
$result_present = $connect->query($sql_present);
$present = $result_present->fetch_assoc();

// Query to get absent count
$sql_absent = "SELECT COUNT(*) AS absent_count FROM learneractivitymarks WHERE LearnerId = $learner_id AND Attendance = 'absent';";
$result_absent = $connect->query($sql_absent);
$absent = $result_absent->fetch_assoc();

// Query to get submission count (where submission is 'yes')
$sql_submission = "SELECT COUNT(*) AS submission_count FROM learneractivitymarks WHERE LearnerId = $learner_id AND Submission = 'yes';";
$result_submission = $connect->query($sql_submission);
$submission = $result_submission->fetch_assoc();

// Calculating attendance rate
$numpresent = $present['present_count'];
$total = $total['total_appearances'];
$numabsent = $absent['absent_count'];

$attendancerate = round(($numpresent / $total) * 100, 2);

// Calculating submission rate
$submission_count = $submission['submission_count'];
$submissionrate = round(($submission_count / $total) * 100, 2);

// Calculate the 'no' submission count
$submission_no_count = $total - $submission_count;

*/


?>
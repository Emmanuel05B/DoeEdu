<?php

include('../partials/connect.php');


if ($learner_id) {
    $sql = "SELECT * FROM learners WHERE LearnerId = $learner_id";
    $results = $connect->query($sql);
    $final = $results->fetch_assoc();
}

$userId = $_SESSION['user_id']; // for teacher
$tsql = "SELECT * FROM users WHERE Id = $userId";
$tresults = $connect->query($tsql);
$tfinal = $tresults->fetch_assoc();


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


///////////////////////////////////////////////////////averages

$resultsDetail = '';
$averageScoreSummary = '';

// Initializing  variables for each activity's averages
$OutdoorPlayEngagementAverage = 0;
$OutdoorPlayIndependenceAverage = 0;

$StoryTimeEngagementAverage = 0;
$StoryTimeIndependenceAverage = 0;

$SensoryIntegrationEngagementAverage = 0;
$SensoryIntegrationIndependenceAverage = 0;

$LifeSkillsEngagementAverage = 0;
$LifeSkillsIndependenceAverage = 0;

// Fetch distinct activities
$activitySql = "SELECT DISTINCT ActivityName FROM scores WHERE LearnerId = $learner_id";
$activityResults = $connect->query($activitySql);

if ($activityResults === false) {
    echo "Error: " . $connect->error;
    exit;
}

// Arrays to store totals and counts
$engagementTotals = [];
$independenceTotals = [];
$activityCounts = [];

// Initialize totals and counts for each activity
while ($activityRow = $activityResults->fetch_assoc()) {
    $activity = $activityRow['ActivityName'];
    
    $engagementTotals[$activity] = 0;
    $independenceTotals[$activity] = 0;
    $activityCounts[$activity] = 0;

    // Query to get scores for this activity
    $sql = "SELECT EngagementLevel, IndependanceLevel FROM scores WHERE LearnerId = $learner_id AND ActivityName = '$activity'";
    $fileResults = $connect->query($sql);

    if ($fileResults === false) {
        echo "Error: line 50" ;
        continue; // jump to the next activity if there's an error
    }

    // Fetch results and accumulate totals
    while ($results = $fileResults->fetch_assoc()) {
        $engagementTotals[$activity] += $results['EngagementLevel'];
        $independenceTotals[$activity] += $results['IndependanceLevel'];
        $activityCounts[$activity]++;
    }
}

// Calculate averages for each activity and assign to separate variables
foreach ($engagementTotals as $activity => $totalEngagement) {
    $count = $activityCounts[$activity];

    if ($count > 0) {
        $averageEngagement = round(($totalEngagement / (10 * $count)) * 100, 2);
        $averageIndependence = round(($independenceTotals[$activity] / (10 * $count)) * 100, 2);


        // Assign to the corresponding activity variables
        switch ($activity) {
            case 'OutdoorPlay':
                $OutdoorPlayEngagementAverage = $averageEngagement;
                $OutdoorPlayIndependenceAverage = $averageIndependence;
                break;
            case 'StoryTime':
                $StoryTimeEngagementAverage = $averageEngagement;
                $StoryTimeIndependenceAverage = $averageIndependence;
                break;
            case 'SensoryIntegration':
                $SensoryIntegrationEngagementAverage = $averageEngagement;
                $SensoryIntegrationIndependenceAverage = $averageIndependence;
                break;
            case 'LifeSkills':
                $LifeSkillsEngagementAverage = $averageEngagement;
                $LifeSkillsIndependenceAverage = $averageIndependence;
                break;
        }


        //calculate the average for each activity
        $OutdoorAverage = round(($OutdoorPlayEngagementAverage + $OutdoorPlayIndependenceAverage) / 2, 2);
        $StoryTimeAverage = round(($StoryTimeEngagementAverage + $StoryTimeIndependenceAverage) / 2, 2);
        $SensoryIntegrationAverage = round(($SensoryIntegrationEngagementAverage + $SensoryIntegrationIndependenceAverage) / 2, 2);
        $LifeSkillsAverage = round(($LifeSkillsEngagementAverage + $LifeSkillsIndependenceAverage) / 2, 2);
        
    } else {
        // Handling cases where no results were found for the activity
        if ($activity === 'OutdoorPlay') {
            $OutdoorPlayEngagementAverage = 0;
            $OutdoorPlayIndependenceAverage = 0;
        } elseif ($activity === 'StoryTime') {
            $StoryTimeEngagementAverage = 0;
            $StoryTimeIndependenceAverage = 0;
        } elseif ($activity === 'SensoryIntegration') {
            $SensoryIntegrationEngagementAverage = 0;
            $SensoryIntegrationIndependenceAverage = 0;
        } elseif ($activity === 'LifeSkills') {
            $LifeSkillsEngagementAverage = 0;
            $LifeSkillsIndependenceAverage = 0;
        }
    }
}

//////////////////////////

//////////////5  5

// Step 1: Get the IDs of the latest 5 records
$sqlLatestIds = "SELECT Id FROM scores WHERE LearnerId = $learner_id ORDER BY Id DESC LIMIT 5";
$fileResultsLatestIds = $connect->query($sqlLatestIds);

if ($fileResultsLatestIds === false) {
    echo "Error executing the query for latest record IDs.";
} else {
    // Check if any rows were returned
    if ($fileResultsLatestIds->num_rows > 0) {
        // Collect the IDs of the latest 5 records
        $latestIds = [];
        while ($row = $fileResultsLatestIds->fetch_assoc()) {
            $latestIds[] = $row['Id'];
        }

        // Create a string of IDs for the next query
        $latestIdsString = implode(',', $latestIds);

        // Step 2: Get the latest 5 records
        $sqlLatest = "SELECT * FROM scores WHERE LearnerId = $learner_id AND Id IN ($latestIdsString)";
        $fileResultsLatest = $connect->query($sqlLatest);

        if ($fileResultsLatest === false) {
            echo "Error executing the query for latest records.";
        } else {
            // Initialize totals for the latest records
            $engagementlevelTotalLatest = 0;
            $independancelevelTotalLatest = 0;
            $numResultsLatest = 0;

            // Fetch latest 5 records
            while ($results = $fileResultsLatest->fetch_assoc()) {
                $engagementlevelTotalLatest += $results['EngagementLevel'];
                $independancelevelTotalLatest += $results['IndependanceLevel'];
                $numResultsLatest++;
            }

            // Calculate averages for latest records
            $latestEngagementLevelAVG = round((($engagementlevelTotalLatest) / (10 * $numResultsLatest)) * 100, 2);
            $latestIndependanceLevelAVG = round((($independancelevelTotalLatest) / (10 * $numResultsLatest)) * 100, 2);


            // Step 3: Get records that are NOT in the latest 5
            $sqlBefore = "SELECT * FROM scores WHERE LearnerId = $learner_id AND Id NOT IN ($latestIdsString) ORDER BY Id DESC LIMIT 5";
            $fileResultsBefore = $connect->query($sqlBefore);

            if ($fileResultsBefore === false) {
                echo "Error executing the query for previous records.";
            } else {
                // Check if any rows were returned
                if ($fileResultsBefore->num_rows > 0) {
                    // Initialize totals for the previous records
                    $engagementlevelTotalBefore = 0;
                    $independancelevelTotalBefore = 0;
                    $numResultsBefore = 0;

                    // Fetch previous records
                    while ($results = $fileResultsBefore->fetch_assoc()) {
                        $engagementlevelTotalBefore += $results['EngagementLevel'];
                        $independancelevelTotalBefore += $results['IndependanceLevel'];
                        $numResultsBefore++;
                    }

                    // Calculate averages for previous records
                    $beforeEngagementLevelAVG = round((($engagementlevelTotalBefore) / (10 * $numResultsBefore)) * 100, 2);
                    $beforeIndependanceLevelAVG = round((($independancelevelTotalBefore) / (10 * $numResultsBefore)) * 100, 2);

                    // Prepare variables for displaying results
                        $engagementStatus = "";
                        $independenceStatus = "";

                        // Engagement Level Status
                        if ($latestEngagementLevelAVG > $beforeEngagementLevelAVG) {
                            $improvementEngagement = round($latestEngagementLevelAVG - $beforeEngagementLevelAVG, 2);
                            $engagementStatus = "$latestEngagementLevelAVG%, Improved by $improvementEngagement points";
                        } elseif ($latestEngagementLevelAVG < $beforeEngagementLevelAVG) {
                            $dropEngagement = round($beforeEngagementLevelAVG - $latestEngagementLevelAVG, 2);
                            $engagementStatus = "$latestEngagementLevelAVG%, Dropped by $dropEngagement points";
                        } else {
                            $engagementStatus = "$latestEngagementLevelAVG%, No change";
                        }

                        // Independence Level Status
                        if ($latestIndependanceLevelAVG > $beforeIndependanceLevelAVG) {
                            $improvementIndependence = round($latestIndependanceLevelAVG - $beforeIndependanceLevelAVG, 2);
                            $independenceStatus = "$latestIndependanceLevelAVG%, Improved by $improvementIndependence points";
                        } elseif ($latestIndependanceLevelAVG < $beforeIndependanceLevelAVG) {
                            $dropIndependence = round($beforeIndependanceLevelAVG - $latestIndependanceLevelAVG, 2);
                            $independenceStatus = "$latestIndependanceLevelAVG%, Dropped by $dropIndependence points";
                        } else {
                            $independenceStatus = "$latestIndependanceLevelAVG%, No change";
}

                } else {
                    echo "No reports have been made for this learner before the latest five records.";
                }
            }
        }
    } else {
        echo "No reports have been made for this learner.";
    }
}


//////////// //5  5



?>
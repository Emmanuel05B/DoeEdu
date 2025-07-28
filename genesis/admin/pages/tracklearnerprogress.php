<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/login.php");
    exit();
}
?>

<?php include("../adminpartials/head.php"); //affects the alert styling ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<style>
    /* Add your existing styles here */
    .content {
        background-color: white;
        margin-top: 20px;

    }

    .pos {
        margin-top: 50px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .container {
        width: 100%;
        background-color: white;
        position: relative;
        margin-bottom: 20px;
    }

    .scale {
        width: 100%;
        height: 20px;
        position: relative;
        background: linear-gradient(to right, #f00 0%, #ffa500 50%, #0f0 100%);
    }

    .scale-mark {
        position: absolute;
        height: 20px;
        width: 1px;
        background-color: #000;
    }

    .scale-mark.start {
        left: 0;
    }

    .scale-mark.middle {
        left: 50%;
    }

    .scale-mark.end {
        left: 100%;
    }

    .skills-container {
        position: relative;
        height: 43px;
        border: 1px solid #000;
        border-radius: 5px;
    }

    .skills {
        text-align: right;
        padding-top: 10px;
        padding-bottom: 10px;
        color: white;
        position: absolute;
        box-sizing: border-box;
    }

    .css {
        background-color: white; /* Default color */
    }

    .scale-label {
        position: absolute;
        font-size: 12px;
        top: -20px; /* Position above the progress bar */
        width: 40px;
        text-align: center;
    }

    #buttonContainer {
        display: flex;
        flex-direction: column; /* Stack items vertically */
        align-items: center; /* Center items horizontally */
    }

    #updateButton {
        background-color: blue;
        width: 180px;
        padding: 10px;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    #status {
        border: 1px solid #ccc;
        padding: 10px; /* Padding inside the box */
        background-color: #f9f9f9;
        border-radius: 5px;
        width: 320px;
    }

    .scale-label.start {
        left: 0;
    }

    .scale-label.middle {
        left: 50%;
        transform: translateX(-50%);
    }

    .scale-label.end {
        right: 0;
    }

    .button {
        background-color: blue;
        width: 120px; /* Adjust width as needed */
        padding: 10px; /* Adjust padding for thickness */
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px; /* Optional: Add border radius for rounded corners */
        margin-bottom: 20px;
    }
</style>

<?php
include('../../partials/connect.php');

$learner_id = intval($_GET['id']); // For learner, ensure it's an integer
$statusValue = intval($_GET['val']); // Get the subject value, ensure it's an integer

if (!$learner_id || !$statusValue) {
    echo "Invalid parameters provided.";
    exit;
}

// Query to fetch activities based on the subject
$sqlActivities = "SELECT ActivityId, MaxMarks FROM activities WHERE SubjectId = $statusValue";
$activityResults = $connect->query($sqlActivities);

if ($activityResults === false) {
    echo "Error executing the query.";
    exit;
}

// Check if any activities exist
if ($activityResults->num_rows > 0) {
    $MarksObtained = 0;
    $Totals = 0;

    while ($activity = $activityResults->fetch_assoc()) {
        $activityId = $activity['ActivityId'];
        $maxMarks = $activity['MaxMarks'];

        // Query to get marks for the learner for this activity
        $sqlLearnerMarks = "SELECT MarksObtained FROM learneractivitymarks WHERE LearnerId = $learner_id AND ActivityId = $activityId";
        $learnerResults = $connect->query($sqlLearnerMarks);

        if ($learnerResults && $learnerResults->num_rows > 0) {
            while ($learner = $learnerResults->fetch_assoc()) {
                $MarksObtained += intval($learner['MarksObtained']);
                $Totals += intval($maxMarks);
            }
        }
    }

    // Calculate percentage
    $AVGscore = $Totals > 0 ? round(($MarksObtained / $Totals) * 100, 2) : 0;
} else {
    echo "No activities found for the selected subject.     put sweet alert here...  line   187";
    exit;
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include("../adminpartials/header.php"); ?>
    <?php include("../adminpartials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="oder">
                    <div style="text-align: center;">
                        <h2 style="display: inline-block;">Learner Progress.=== name somewhere</h2><br><br><br>
                    </div>

                    <div class="container">
                        <div class="scale">
                            <div class="scale-mark start"></div>
                            <div class="scale-mark middle"></div>
                            <div class="scale-mark end"></div>
                            <div class="scale-label start">0%</div>
                            <div class="scale-label middle">50%</div>
                            <div class="scale-label end">100%</div>
                        </div><br><br>

                        <div class="skills-container">
                            <div class="skills css" id="cssSkill">50%</div>
                        </div><br>
                    </div>

                    <div id="buttonContainer">
                        <button id="updateButton" onclick="updateSkill()">Update To Clear Old Scores</button>
                        <p id="status"></p><br>
                        <p><a class="button" href="subanalytics.php?id=<?php echo $learner_id ?>">View Scores</a></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    let lastScore = localStorage.getItem('cssSkillPercentage');
    if (lastScore) {
        updateSkillElement(parseInt(lastScore));
    }
});

function updateSkill() {
    var score = <?php echo $AVGscore ?>;
    localStorage.setItem('cssSkillPercentage', score);
    updateSkillElement(score);
    document.getElementById('updateButton').innerHTML = 'Updated';
}

function updateSkillElement(score) {
    var skillElement = document.getElementById('cssSkill');
    skillElement.style.width = score + '%';
    skillElement.innerHTML = score + '%';

    var status;
    if (score < 50) {
        skillElement.style.backgroundColor = 'red';
        status = 'Bad, if the learner continues like this, they will probably fail.';
    } else if (score < 80) {
        skillElement.style.backgroundColor = 'orange';
        status = 'Getting There, the learner is doing good. The aim is to get them to 100%.';
    } else {
        skillElement.style.backgroundColor = '#53f527';
        status = 'Good, the learner is really improving. Motivate the learner to keep working hard.';
    }

    document.getElementById('status').innerHTML = '<p style="font-weight: bold;">Status: </p>' + status + '<br><br> <p style="text-align: center; font-weight: bold;">Results Based on Aggregate Scores from All Reports to Date</p>';
}
</script>

<?php include("../adminpartials/queries.php"); ?>
<script src="../dist/js/demo.js"></script>
</body>
</html>

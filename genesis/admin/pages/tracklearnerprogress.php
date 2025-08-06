<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<style>
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
        background: linear-gradient(to right, #f00 0%, #ffa500 50%, #0f0 100%);
        position: relative;
    }
    .scale-mark {
        position: absolute;
        height: 20px;
        width: 1px;
        background-color: #000;
    }
    .scale-label {
        position: absolute;
        font-size: 12px;
        top: -20px;
        width: 40px;
        text-align: center;
    }
    .scale-mark.start, .scale-label.start { left: 0; }
    .scale-mark.middle, .scale-label.middle { left: 50%; transform: translateX(-50%); }
    .scale-mark.end, .scale-label.end { right: 0; }

    .skills-container {
        height: 43px;
        border: 1px solid #000;
        border-radius: 5px;
        position: relative;
    }
    .skills {
        color: white;
        position: absolute;
        padding: 10px;
        box-sizing: border-box;
        text-align: right;
    }
    .css { background-color: white; }

    #buttonContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    #updateButton, .button {
        background-color: blue;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .button { width: 120px; }
    #updateButton { width: 180px; }

    #status {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        width: 320px;
    }
</style>

<?php
include(__DIR__ . "/../../partials/connect.php");

$learner_id = intval($_GET['id']);
$statusValue = intval($_GET['val']);

if (!$learner_id || !$statusValue) {
    echo "<script>Swal.fire('Invalid Parameters', 'Please ensure a learner and subject are selected.', 'error');</script>";
    exit;
}

$sqlActivities = "SELECT ActivityId, MaxMarks FROM activities WHERE SubjectId = $statusValue";
$activityResults = $connect->query($sqlActivities);

if ($activityResults === false) {
    echo "<script>Swal.fire('Error', 'Query could not be executed.', 'error');</script>";
    exit;
}

if ($activityResults->num_rows > 0) {
    $MarksObtained = 0;
    $Totals = 0;

    while ($activity = $activityResults->fetch_assoc()) {
        $activityId = $activity['ActivityId'];
        $maxMarks = $activity['MaxMarks'];

        $sqlLearnerMarks = "SELECT MarksObtained FROM learneractivitymarks WHERE LearnerId = $learner_id AND ActivityId = $activityId";
        $learnerResults = $connect->query($sqlLearnerMarks);

        if ($learnerResults && $learnerResults->num_rows > 0) {
            while ($learner = $learnerResults->fetch_assoc()) {
                $MarksObtained += intval($learner['MarksObtained']);
                $Totals += intval($maxMarks);
            }
        }
    }

    $AVGscore = $Totals > 0 ? round(($MarksObtained / $Totals) * 100, 2) : 0;
} else {
    echo "<script>Swal.fire('No Data', 'No activities found for the selected subject.', 'warning');</script>";
    exit;
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="oder">
                    <div style="text-align: center;">
                        <h2>Learner Progress</h2>
                        <p style="font-weight: bold;">Subject Score Analysis</p><br>
                    </div>

                    <div class="container">
                        <div class="scale">
                            <div class="scale-mark start"></div>
                            <div class="scale-mark middle"></div>
                            <div class="scale-mark end"></div>
                            <div class="scale-label start">0%</div>
                            <div class="scale-label middle">50%</div>
                            <div class="scale-label end">100%</div>
                        </div><br>

                        <div class="skills-container">
                            <div class="skills css" id="cssSkill">50%</div>
                        </div>
                    </div>

                    <div id="buttonContainer">
                        <button id="updateButton" onclick="updateSkill()">Update To Clear Old Scores</button>
                        <div id="status"></div>
                        <p><a class="button" href="subanalytics.php?id=<?php echo $learner_id ?>">View Scores</a></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let lastScore = localStorage.getItem('cssSkillPercentage');
    if (lastScore) {
        updateSkillElement(parseInt(lastScore));
    }
});

function updateSkill() {
    const score = <?php echo $AVGscore ?>;
    localStorage.setItem('cssSkillPercentage', score);
    updateSkillElement(score);
    document.getElementById('updateButton').innerHTML = 'Updated';
}

function updateSkillElement(score) {
    const skillElement = document.getElementById('cssSkill');
    skillElement.style.width = score + '%';
    skillElement.innerHTML = score + '%';

    let status;
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

    document.getElementById('status').innerHTML = '<p style="font-weight: bold;">Status:</p>' + status + '<br><br>' +
        '<p style="text-align: center; font-weight: bold;">Results Based on Aggregate Scores from All Reports to Date</p>';
}
</script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>

<!DOCTYPE html>
<html>
    
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');

if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}

$activityId = intval($_GET['activityId']);
$userId = $_SESSION['user_id']; // logged-in learner

// Fetch activity details
$stmt = $connect->prepare("SELECT TutorId, SubjectName, Grade, Topic, Title, Instructions, TotalMarks, DueDate, CreatedAt, ImagePath FROM onlineactivities WHERE id = ?");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Activity not found.");
}

$activity = $result->fetch_assoc();
$stmt->close();

if ($activity['TutorId'] == $userId) {   // Learner should NOT be tutor who created it
    die("You do not have permission to view this activity.");
}

// Fetch questions with their IDs for form inputs
$qstmt = $connect->prepare("SELECT id, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) {
    $questions[] = $row;
}
$qstmt->close();
?>

<?php include("learnerpartials/head.php"); ?>

<style>
  /* Custom colors matching your palette */
  .tile-card {
    background: #e6f0ff; /* light blue */
    border: 2px solid #a3c1f7; /* sky blue */
    border-radius: 8px;
    padding: 15px;
    height: 100%;
    box-shadow: 0 2px 6px rgba(163,193,247,0.3);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }

  .tile-card p, 
  .tile-card h4 {
    color: #3a3a72; /* purple-ish for text */
    font-weight: 600;
    margin-bottom: 10px;
  }

  .tile-card ul li label {
    cursor: pointer;
    color: #2a2a6f; /* darker purple */
  }

  .tile-card ul li input[type="radio"] {
    margin-right: 8px;
  }

  .activity-image {
    background: #f0f7ff; /* very light blue */
    border: 2px solid #8ab4f8; /* lighter sky blue */
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 2px 8px rgba(138,180,248,0.25);
    margin-bottom: 10px;
    max-height: 180px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .activity-image img {
    max-width: 100%;
    max-height: 160px;
    border-radius: 6px;
    object-fit: contain;
  }

  .submit-btn {
    background-color: #6a52a3; /* purple */
    color: white;
    font-weight: 600;
    border: none;
    padding: 12px 30px;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    margin-top: 20px;
  }
  .submit-btn:hover {
    background-color: #57407d; /* darker purple */
    color: white;
  }

  /* Container for all tiles (image+instructions + questions) */
  .tiles-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  /* Each tile: roughly 48% width on md screens, 100% on small screens */
  .tile-wrapper {
    flex: 1 1 48%;
    box-sizing: border-box;
  }

  @media (max-width: 767px) {
    .tile-wrapper {
      flex: 1 1 100%;
    }
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php") ?>
  <?php include("learnerpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">

        <div class="col-xs-12">
          <h3 style="color:#3a3a72; font-weight:700;"><?php echo htmlspecialchars($activity['Title']); ?></h3>
          <p style="color:#2a2a6f; font-weight:600;">
            Subject: <?php echo htmlspecialchars($activity['SubjectName']); ?> | 
            Grade: <?php echo htmlspecialchars($activity['Grade']); ?> | 
            Topic: <?php echo htmlspecialchars($activity['Topic']); ?> | 
            Due Date: <?php echo htmlspecialchars($activity['DueDate']); ?>
          </p>
          <hr style="border-color:#a3c1f7;">
        </div>

        <div class="col-xs-12">
          <form action="submithomework.php" method="POST">
            <input type="hidden" name="activityId" value="<?php echo $activityId; ?>">

            <div class="tiles-row">
              <!-- Image + Instructions tile -->
              <div class="tile-wrapper">
                <div class="tile-card">
                  <?php if (!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                    <div class="activity-image">
                      <img src="<?php echo htmlspecialchars($activity['ImagePath']); ?>" alt="Activity Image">
                    </div>
                  <?php endif; ?>

                  <div class="instructions-text">
                    <h4>Instructions</h4>
                    <p><?php echo nl2br(htmlspecialchars($activity['Instructions'])); ?></p>
                  </div>
                </div>
              </div>

              <!-- Questions -->
              <?php foreach ($questions as $index => $question): ?>
                <div class="tile-wrapper">
                  <div class="tile-card">
                    <p><strong>Q<?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($question['QuestionText']); ?></p>
                    <ul class="list-unstyled">
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="A" required> A. <?php echo htmlspecialchars($question['OptionA']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="B"> B. <?php echo htmlspecialchars($question['OptionB']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="C"> C. <?php echo htmlspecialchars($question['OptionC']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="D"> D. <?php echo htmlspecialchars($question['OptionD']); ?></label></li>
                    </ul>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <button type="submit" class="submit-btn">Submit Homework</button>
          </form>
        </div>

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>

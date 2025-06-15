<!DOCTYPE html>
<html>
    
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');

// Validate activityId from GET
if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}

$activityId = intval($_GET['activityId']);
$tutorId = $_SESSION['user_id']; // logged-in tutor

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

if ($activity['TutorId'] != $tutorId) {
    die("You do not have permission to view this activity.");
}

// Fetch questions
$qstmt = $connect->prepare("SELECT QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) {
    $questions[] = $row;
}
$qstmt->close();
?>

<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/header.php") ?>;
  <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo htmlspecialchars($activity['Title']); ?></h3>
              <p><small>
                Subject: <?php echo htmlspecialchars($activity['SubjectName']); ?> | 
                Grade: <?php echo htmlspecialchars($activity['Grade']); ?> | 
                Topic: <?php echo htmlspecialchars($activity['Topic']); ?> | 
                Due Date: <?php echo htmlspecialchars($activity['DueDate']); ?>
              </small></p>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <p><strong>Instructions:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($activity['Instructions'])); ?></p>

                <?php if (!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                <div style="margin-bottom:20px;">
                  <img src="<?php echo htmlspecialchars($activity['ImagePath']); ?>" alt="Activity Image" style="max-width: 30%; height: auto;">
                </div>
                <?php endif; ?>

                <h4>Questions</h4>
                <?php foreach ($questions as $index => $question): ?>
                <div class="box box-primary" style="padding:15px; margin-bottom:15px;">
                    <p><strong>Question <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($question['QuestionText']); ?></p>
                    <ul>
                        <li>A. <?php echo htmlspecialchars($question['OptionA']); ?></li>
                        <li>B. <?php echo htmlspecialchars($question['OptionB']); ?></li>
                        <li>C. <?php echo htmlspecialchars($question['OptionC']); ?></li>
                        <li>D. <?php echo htmlspecialchars($question['OptionD']); ?></li>
                    </ul>
                </div>
                <?php endforeach; ?>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>

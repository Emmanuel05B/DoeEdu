<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("learnerpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php") ?>
  <?php include("learnerpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Homework</h1>
    </section>

    <?php
    include('../partials/connect.php');

    // For now, hardcoded learner ID (replace with $_SESSION['user_id'] in production)
    $LearnerId = '72';

    // Utility function to map SubjectId to subject names
    function getSubjectName($id) {
      $map = [
        1 => "Mathematics",
        2 => "Physical Sciences",
        3 => "Mathematics",
        4 => "Physical Sciences",
        5 => "Mathematics",
        6 => "Physical Sciences",
      ];
      return $map[$id] ?? "Unknown Subject";
    }

    // Get all subjects assigned to the learner (limit to 2)
    $stmt = $connect->prepare("SELECT SubjectId FROM learnersubject WHERE LearnerId = ? ORDER BY SubjectId ASC LIMIT 2");
    $stmt->bind_param("i", $LearnerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjectRows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (count($subjectRows) === 0) {
      echo "<h3 class='text-center'>No subjects found for this learner.</h3>";
    } else {
      foreach ($subjectRows as $subject) {
        $SubjectId = $subject['SubjectId'];
        $SubjectName = getSubjectName($SubjectId);
    ?>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $SubjectName; ?> - Upcoming and Completed Homework</h3>
            </div>

            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped" style="width:100%;">
                <thead style="background-color: #3c8dbc; color: white;">
                  <tr>
                    <th>Title</th>
                    <th>Chapter</th>
                    <th>Received On</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Total Marks</th>
                    <th>Score</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                // Fetch homework activities for the current subject
                $stmt2 = $connect->prepare("SELECT Id, Title, Topic, CreatedAt, DueDate, TotalMarks 
                                            FROM onlineactivities WHERE SubjectName = ?");
                $stmt2->bind_param("i", $SubjectId);
                $stmt2->execute();
                $activities = $stmt2->get_result();

                if ($activities->num_rows === 0) {
                  echo "<tr><td colspan='8'>No homework available for this subject.</td></tr>";
                } else {
                  while ($activity = $activities->fetch_assoc()) {
                    echo "<tr>
                            <td>{$activity['Title']}</td>
                            <td>{$activity['Topic']}</td>
                            <td>{$activity['CreatedAt']}</td>
                            <td>{$activity['DueDate']}</td>
                            <td><span class='label label-warning'>Not Started</span></td>
                            <td>{$activity['TotalMarks']}</td>
                            <td>-</td>
                            <td><a href='viewhomework.php?activityId={$activity['Id']}' class='btn btn-primary btn-sm'>Open</a></td>
                          </tr>";
                  }
                }

                $stmt2->close();
                ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </section>

    <?php
      } // end foreach
    } // end else 
    ?>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $('table').DataTable({
      responsive: true,
      autoWidth: false
    });
  });
</script>

</body>
</html>

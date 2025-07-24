<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');

$tutorId = $_SESSION['user_id']; // Logged-in tutor id

?>

<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Activities</h1>
      <small>List of all your created activities</small>
    </section>

    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Activities List</h3>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-bordered table-hover" id="activitiesTable" style="width:100%;">
            <thead style="background-color: #3c8dbc; color: white;">
              <tr>
                
                <th>Title</th>
                <th>Topic</th>
                <th>Grade</th>
                <th>Subject</th>
                <th>Group</th>
                <th>Due Date</th>
                <th>Click</th>
                <th>Click</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $connect->prepare("
                SELECT oa.Id, oa.Title, oa.Topic, oa.Grade, s.SubjectName, oa.DueDate
                FROM onlineactivities oa
                INNER JOIN subjects s ON oa.SubjectName = s.SubjectId
                WHERE oa.TutorId = ?
                ORDER BY oa.Id DESC
              ");
              $stmt->bind_param("i", $tutorId);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows === 0) {
                echo "<tr><td colspan='6' class='text-center'>No activities found.</td></tr>";
              } else {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>" . htmlspecialchars($row['Title']) . "</td>
                          <td>" . htmlspecialchars($row['Topic']) . "</td>
                          <td>" . htmlspecialchars($row['Grade']) . "</td>
                          <td>" . htmlspecialchars($row['SubjectName']) . "</td>
                          <td>" . htmlspecialchars($row['SubjectName']) . "</td>
                          <td>" . htmlspecialchars($row['DueDate']) . "</td>
                          <td><a href='viewactivity.php?activityId=" . intval($row['Id']) . "' class='btn btn-block btn-primary'>View/Edit</a></td>
                          <td><a href='activityoverview.php?activityId=" . intval($row['Id']) . "' class='btn btn-block btn-primary'>Overview</a></td></tr>";
                }
              }
              $stmt->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script><!-- white mainsidebar -->

<script>
  $(function () {
    $('#activitiesTable').DataTable({
      responsive: true,
      autoWidth: false,
      order: [[4, "asc"]] // Sort by due date ascending by default
    });
  });
</script>


</body>
</html>

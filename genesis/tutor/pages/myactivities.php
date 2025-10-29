<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  


$tutorId = $_SESSION['user_id']; 
?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'The activity has been successfully deleted.',
      confirmButtonText: 'OK'
  });
  </script>
  <?php endif; ?>

  
  <div class="content-wrapper">
    <section class="content-header">
       <h1>Master Activities <small>List of all your created activities</small></h1>
        <ol class="breadcrumb">
          <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Activities</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Activities List</h3>
        </div>
        <div class="box-body table-responsive">
          <table class="table table-bordered table-hover" id="activitiesTable" style="width:100%;">
            <thead style="background-color: #d1d9ff;">
              <tr>
                <th>Title</th>
                <th>Topic</th>
                <th>Grade</th>
                <th>Subject</th>
                <th>Created On</th>
                <th>Edit</th>
                <th>Open</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $connect->prepare("
                SELECT oa.Id, oa.Title, oa.Topic, oa.Grade, s.SubjectName, oa.CreatedAt
                FROM onlineactivities oa
                INNER JOIN subjects s ON oa.SubjectId = s.SubjectId
                WHERE oa.TutorId = ?
                ORDER BY oa.Id DESC
              ");
              $stmt->bind_param("i", $tutorId);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows === 0) {
                echo "<tr><td colspan='8' class='text-center'>No activities found.</td></tr>";
              } else {
                while ($row = $result->fetch_assoc()) {
                  $activityId = intval($row['Id']);
                  echo "<tr>
                    <td>" . htmlspecialchars($row['Title']) . "</td>
                    <td>" . htmlspecialchars($row['Topic']) . "</td>
                    <td>" . htmlspecialchars($row['Grade']) . "</td>
                    <td>" . htmlspecialchars($row['SubjectName']) . "</td>
                    <td>" . htmlspecialchars($row['CreatedAt']) . "</td>
                    <td>
                    <a href='viewactivity.php?activityId=" . $activityId . " ' class='btn btn-xs btn-primary' title='Edit'>
                        <i class='fa fa-pencil'></i> Edit
                    </a>
                    </td>
                    <td>
                    <a href='masteractivityoverview.php?activityId=" . $activityId . "' class='btn btn-xs btn-info' title='Overview'>
                        <i class='fa fa-info-circle'></i> Overview
                    </a>
                  
                    </td>
                    
                </tr>";

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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>



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

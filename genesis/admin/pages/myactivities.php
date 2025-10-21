<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../partials/connect.php");

$tutorId = $_SESSION['user_id']; // Logged-in tutor id
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

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
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Activities</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-info">
        <div class="box-body table-responsive">
          <table class="table table-bordered table-hover" id="activitiesTable" style="width:100%;">
            <thead style="background-color:#d9edf7; color:#333;">
              <tr>
                <th>Title</th>
                <th>Topic</th>
                <th>Grade</th>
                <th>Subject</th>
                <th>Created On</th>
                <th>Action</th>
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
                        <i class='fa fa-pencil'></i>
                    </a>
                    <a href='#' class='btn btn-xs btn-danger delete-activity-btn' data-id='" . intval($row['Id']) . "' title='Delete'>
                        <i class='fa fa-trash'></i>
                    </a>
                    <a href='masteractivityoverview.php?activityId=" . $activityId . "' class='btn btn-xs btn-info' title='Overview'>
                        <i class='fa fa-info-circle'></i>
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

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
$(function () {
    $('#activitiesTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[4, "asc"]] // Sort by due date ascending by default
    });

    // SweetAlert for Delete buttons
    $(document).on('click', '.delete-activity-btn', function(e) {
    e.preventDefault();
    var activityId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the activity!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'deleteactivity.php?activityId=' + activityId;
        }
    });
});

});
</script>

</body>
</html>

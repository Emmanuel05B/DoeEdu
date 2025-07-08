<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');
include("tutorpartials/head.php");

$tutorId = $_SESSION['user_id'];

// Fetch pending sessions    //should be grade instead of Contact
$pendingSQL = "
    SELECT ts.*, u.Name, u.Contact 
    FROM tutorsessions ts
    JOIN users u ON ts.LearnerId = u.Id
    WHERE ts.TutorId = ? AND ts.Status = 'Pending'
    ORDER BY ts.SlotDateTime ASC
";

$pendingQuery = $connect->prepare($pendingSQL);

if (!$pendingQuery) {
    die("Prepare failed for pendingQuery: " . $connect->error);
}

$pendingQuery->bind_param("i", $tutorId);
$pendingQuery->execute();
$pendingResult = $pendingQuery->get_result();

// Fetch accepted sessions
$acceptedQuery = $connect->prepare("
    SELECT ts.*, u.Name, u.Contact
    FROM tutorsessions ts
    JOIN users u ON ts.LearnerId = u.Id
    WHERE ts.TutorId = ? AND ts.Status = 'Accepted' AND ts.SlotDateTime >= NOW()
    ORDER BY ts.SlotDateTime ASC
");
$acceptedQuery->bind_param("i", $tutorId);
$acceptedQuery->execute();
$acceptedResult = $acceptedQuery->get_result();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("tutorpartials/header.php"); ?>
  <?php include("tutorpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">Session Requests
        <small>Manage session invites from learners</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Session Requests</li>
      </ol>
    </section>

    <section class="content">

      <!-- Pending Requests -->
      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Pending Requests</h3>
        </div>
        <div class="box-body table-responsive" style="background-color:#e8eeff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#d1d9ff;">
              <tr>
                <th>Learner</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Notes</th>
                <th>Status</th>
                <th style="width:130px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($pendingResult->num_rows > 0): ?>
                <?php while ($row = $pendingResult->fetch_assoc()): 
                  $dt = new DateTime($row['SlotDateTime']);
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= htmlspecialchars($row['Subject']) ?></td>
                    <td><?= htmlspecialchars($row['Contact']) ?></td>
                    <td><?= $dt->format('Y-m-d') ?></td>
                    <td><?= $dt->format('H:i') ?></td>
                    <td><?= htmlspecialchars($row['Type'] ?? 'One-on-One') ?></td>
                    <td><?= htmlspecialchars($row['Notes']) ?></td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>
                      <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Accept</button>
                      <button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Decline</button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="9" class="text-center">No pending requests found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Accepted Sessions -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Upcoming Accepted Sessions</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <table class="table table-striped">
            <thead style="background-color:#dafbe4;">
              <tr>
                <th>Learner</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($acceptedResult->num_rows > 0): ?>
                <?php while ($row = $acceptedResult->fetch_assoc()): 
                  $dt = new DateTime($row['SlotDateTime']);
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($row['FullName']) ?></td>
                    <td><?= htmlspecialchars($row['Subject']) ?></td>
                    <td><?= htmlspecialchars($row['Grade']) ?></td>
                    <td><?= $dt->format('Y-m-d') ?></td>
                    <td><?= $dt->format('H:i') ?></td>
                    <td><?= htmlspecialchars($row['Type'] ?? 'One-on-One') ?></td>
                    <td><?= htmlspecialchars($row['Notes']) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7" class="text-center">No upcoming sessions.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>

</body>
</html>

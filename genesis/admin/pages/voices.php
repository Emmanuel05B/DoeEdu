<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

// Fetch unread student voices from DB
$studentVoices = [];
$stmt = $connect->prepare("
    SELECT Id, UserId, Subject, Message, IsAnonymous, CreatedAt
    FROM studentvoices
    WHERE IsRead = 0
    ORDER BY CreatedAt DESC
    LIMIT 50
");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Determine display name
    if ($row['IsAnonymous'] || empty($row['UserId'])) {
        $name = "Anonymous";
    } else {
        $userStmt = $connect->prepare("SELECT CONCAT(Name,' ',Surname) AS FullName FROM users WHERE Id = ?");
        $userStmt->bind_param("i", $row['UserId']);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userRow = $userResult->fetch_assoc();
        $name = $userRow['FullName'] ?? "Unknown";
        $userStmt->close();
    }

    $studentVoices[] = [
        "id" => $row['Id'],
        "name" => $name,
        "subject" => $row['Subject'],
        "date" => date("Y-m-d", strtotime($row['CreatedAt'])),
        "message" => $row['Message']
    ];
}
$stmt->close();
?>
<?php
// Fetch read student voices
$readVoices = [];
$stmtRead = $connect->prepare("
    SELECT Id, UserId, Subject, Message, IsAnonymous, CreatedAt
    FROM studentvoices
    WHERE IsRead = 1
    ORDER BY CreatedAt DESC
    LIMIT 50
");
$stmtRead->execute();
$resultRead = $stmtRead->get_result();
while ($row = $resultRead->fetch_assoc()) {
    if ($row['IsAnonymous'] || empty($row['UserId'])) {
        $name = "Anonymous";
    } else {
        $userStmt = $connect->prepare("SELECT CONCAT(Name,' ',Surname) AS FullName FROM users WHERE Id = ?");
        $userStmt->bind_param("i", $row['UserId']);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userRow = $userResult->fetch_assoc();
        $name = $userRow['FullName'] ?? "Unknown";
        $userStmt->close();
    }

    $readVoices[] = [
        "id" => $row['Id'],
        "name" => $name,
        "subject" => $row['Subject'],
        "date" => date("Y-m-d", strtotime($row['CreatedAt'])),
        "message" => $row['Message']
    ];
}
$stmtRead->close();
 ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Student Voices <small>Read learners' concerns and feedback</small></h1>
            <ol class="breadcrumb">
                <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Student Voices</li>
            </ol>
        </section>

        <section class="content">
          <div class="row">
            <!-- Left side: Student voices -->
            <div class="col-md-8">
              <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
                <div class="box-header with-border" style="background-color:#f0f8ff; display: flex; justify-content: space-between; align-items: center;">
                  <h3 class="box-title" style="color:#3c8dbc;">
                    <i class="fa fa-comments"></i> Learner Concerns
                  </h3>
                  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#readVoicesModal">
                    View Read Voices
                  </button>
                </div>

                <div class="box-body" style="background-color:#ffffff; max-height: 600px; overflow-y: auto;">
                  <ul class="list-group">
                    <?php foreach($studentVoices as $voice): ?>
                      <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
                        <div>
                          <strong><?= htmlspecialchars($voice['name']) ?></strong> 
                          <em style="color: gray; font-size: 12px; margin-left: 5px;"><?= htmlspecialchars($voice['date']) ?></em><br>
                          <?php if(!empty($voice['subject'])): ?>
                              <em style="color:#3c8dbc; font-weight:600;">Subject: <?= htmlspecialchars($voice['subject']) ?></em><br>
                          <?php endif; ?>
                          <p><?= htmlspecialchars($voice['message']) ?></p>
                        </div>
                        
                        <div>
                          <form method="POST" action="studentvoices_markread.php" style="margin:0;">
                              <input type="hidden" name="id" value="<?= $voice['id'] ?>">
                              <button type="submit" class="btn btn-xs btn-success">
                                  <i class="fa fa-check"></i> Mark as Read
                              </button>
                          </form>
                        </div>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            </div>


            <!-- Right side: Additional info -->
            <div class="col-md-4">
              <div class="box box-info" style="border-top: 3px solid #00c0ef;">
                <div class="box-header with-border" style="background-color:#d9f0fb;">
                  <h3 class="box-title" style="color:#0073b7;">
                    <i class="fa fa-info-circle"></i> Additional Info
                  </h3>
                </div>
                <div class="box-body" style="background-color:#ffffff; min-height: 200px;">
                  <h4>How to Support Learners</h4>
                  <ul>
                    <li>Schedule one-on-one sessions.</li>
                    <li>Encourage group discussions.</li>
                    <li>Provide extra practice resources.</li>
                    <li>Refer to counselors if needed.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<!-- SweetAlert2 -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
<script>
<?php if(isset($_SESSION['success'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= $_SESSION['success']; ?>'
});
<?php unset($_SESSION['success']); endif; ?>

<?php if(isset($_SESSION['error'])): ?>
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: '<?= $_SESSION['error']; ?>'
});
<?php unset($_SESSION['error']); endif; ?>
</script>


<!-- Read Voices Modal -->
<div class="modal fade" id="readVoicesModal" tabindex="-1" role="dialog" aria-labelledby="readVoicesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3c8dbc; color:white;">
        <h4 class="modal-title" id="readVoicesModalLabel"> Read Student Voices</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color:white;">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height:500px; overflow-y:auto;">
        <ul class="list-group">
            <?php foreach($readVoices as $voice): ?>
              <li class="list-group-item">
                  <strong><?= htmlspecialchars($voice['name']) ?></strong> 
                  <em style="color: gray; font-size: 12px; margin-left: 5px;"><?= htmlspecialchars($voice['date']) ?></em><br>
                  <?php if(!empty($voice['subject'])): ?>
                      <em style="color:#3c8dbc; font-weight:600;">Subject: <?= htmlspecialchars($voice['subject']) ?></em><br>
                  <?php endif; ?>
                  <p><?= htmlspecialchars($voice['message']) ?></p>
              </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>

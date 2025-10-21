<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$learnerId = $_SESSION['user_id'] ?? 0;

// Fetch learner's class IDs
$classIds = [];
$stmtClasses = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerId = ?");
$stmtClasses->bind_param("i", $learnerId);
$stmtClasses->execute();
$classResult = $stmtClasses->get_result();
while($row = $classResult->fetch_assoc()){
    $classIds[] = $row['ClassID'];
}
$stmtClasses->close();

// --- GENERAL ANNOUNCEMENTS ---
$generalAnnouncements = [];
$sqlGeneral = "
    SELECT n.NotificationId, n.Title, n.Content, n.SubjectName, n.CreatedAt, u.Name, u.Surname
    FROM notifications n
    LEFT JOIN users u ON n.CreatedBy = u.Id
    WHERE n.CreatedFor IN (1, 12)
      AND (n.ExpiryDate IS NULL OR n.ExpiryDate >= NOW())
    ORDER BY n.CreatedAt DESC
    LIMIT 20
";
$resultGeneral = $connect->query($sqlGeneral);
while($row = $resultGeneral->fetch_assoc()){
    $generalAnnouncements[] = $row;
}

// --- CLASS NOTIFICATIONS (for modal) ---
$classNotifications = [];
if (!empty($classIds)) {
    $inClause = implode(',', array_map('intval', $classIds));
    $notifSql = "
        SELECT cn.Title, cn.Content, cn.Subject, cn.CreatedAt
        FROM classnotifications cn
        WHERE cn.Grade IN (SELECT Grade FROM classes WHERE ClassID IN ($inClause))
          AND cn.Group_Class IN (SELECT Group_Class FROM classes WHERE ClassID IN ($inClause))
          AND cn.CreatedAt >= NOW() - INTERVAL 14 DAY
        ORDER BY cn.CreatedAt DESC
        LIMIT 20
    ";
    $notifResult = $connect->query($notifSql);
    while ($notif = $notifResult->fetch_assoc()) {
        $classNotifications[] = $notif;
    }
}


?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1>Announcements & News</h1>
      <p>Stay up to date with important updates, tips, and events from your Tutors and Directors.</p>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Announcements</li>
      </ol>
      <a href="#" class="btn btn-info" style="margin-top: 10px;" data-toggle="modal" data-target="#learnerNotificationsModal">
        View Class Notifications
      </a>
    </section>

    <section class="content">

      <!-- Detailed Announcements -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Latest Announcements</h3>
        </div>
        <div class="box-body">
          <?php
          if (!empty($generalAnnouncements)) {
              foreach ($generalAnnouncements as $notice) {
                  $posterName = htmlspecialchars($notice['Surname']);
                  $title = htmlspecialchars($notice['Title']);
                  $content = nl2br(htmlspecialchars($notice['Content']));
                  $subjectName = htmlspecialchars($notice['SubjectName']);
                  $createdAt = date('Y-m-d', strtotime($notice['CreatedAt']));
                  
                  echo <<<HTML
                  <div class="post">
                    <h4><strong>🔔 $title</strong> <small style="font-weight: normal; color: #555;">[$subjectName]</small></h4>
                    <p>Posted by $posterName · $createdAt</p>
                    <p>$content</p>
                  </div>
                  <hr>
                  HTML;
              }
          } else {
              echo "<p>No announcements available at the moment.</p>";
          }
          ?>
        </div>
      </div>
    </section>
  </div>

  <!-- Class Notifications Modal -->
  <div class="modal fade" id="learnerNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="learnerNotifTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="learnerNotifTitle">Class Notification Centre</h4>
        </div>  

        <div class="modal-body">
          <?php if (!empty($classNotifications)): ?>
              <?php foreach ($classNotifications as $notif): ?>
                  <div class="panel panel-default">
                      <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                        <span><strong>Date:</strong> <?= htmlspecialchars($notif['CreatedAt']) ?></span>
                        <span>
                          <?= htmlspecialchars($notif['Subject']) ?>
                          <?php if(!empty($notif['Priority'])): ?>
                              <?php if($notif['Priority'] == 1): ?>
                                  <span class="label label-danger">HIGH</span>
                              <?php elseif($notif['Priority'] == 2): ?>
                                  <span class="label label-warning">MEDIUM</span>
                              <?php endif; ?>
                          <?php endif; ?>
                        </span>
                     </div>

                      <div class="panel-body">
                          <strong><?= htmlspecialchars($notif['Title']) ?>:</strong><br>
                          <?= nl2br(htmlspecialchars($notif['Content'])) ?>
                          <?php if(!empty($notif['Link'])): ?>
                            <br><a href="<?= htmlspecialchars($notif['Link']) ?>" target="_blank">🔗 View Link</a>
                          <?php endif; ?>
                      </div>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <div class="text-center text-muted">
                  No class notifications at the moment.
              </div>
          <?php endif; ?>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>  

      </div>
    </div>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>

<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$userId = $_SESSION['user_id']; 
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $createdFor = $_POST['createdFor'] ?? '';
    $expiryDate = $_POST['expiryDate'] ?? '';
    $priority = $_POST['priority'] ?? 'Medium';

    if ($title === '') $errors[] = "Title is required.";
    if ($content === '') $errors[] = "Content is required.";
    if (!in_array($createdFor, ['1','2','12'])) $errors[] = "Invalid recipient selection.";
    if ($expiryDate === '') $errors[] = "Expiry date is required.";

    if (empty($errors)) {
        $stmt = $connect->prepare("INSERT INTO notifications (Title, Content, CreatedBy, CreatedFor, Priority, ExpiryDate, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssiiss", $title, $content, $userId, $createdFor, $priority, $expiryDate);
        if ($stmt->execute()) {
            $success = "Notification successfully created.";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

include(__DIR__ . "/../../common/partials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Create Notification</h1>
            <ol class="breadcrumb">
                <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Create Notification</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="background:#f0f0f0;">
                            <h3 class="box-title">Notification Details</h3>
                        </div>

                        <form method="POST" action="">
                            <div class="box-body">
                                <div class="row">
                                    <!-- Left Column: Title -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" class="form-control" placeholder="Enter title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <!-- Right Column: Recipient -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="createdFor">Send To</label>
                                            <select name="createdFor" class="form-control" required>
                                                <option value="">-- Select --</option>
                                                <option value="1" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '1') ? 'selected' : '' ?>>Learners</option>
                                                <option value="2" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '2') ? 'selected' : '' ?>>Tutors</option>
                                                <option value="12" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '12') ? 'selected' : '' ?>>Both</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Right Column: Priority -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <select name="priority" class="form-control">
                                                <option value="Low" <?= (isset($_POST['priority']) && $_POST['priority']=='Low')?'selected':'' ?>>Low</option>
                                                <option value="Medium" <?= (isset($_POST['priority']) && $_POST['priority']=='Medium')?'selected':'' ?>>Medium</option>
                                                <option value="High" <?= (isset($_POST['priority']) && $_POST['priority']=='High')?'selected':'' ?>>High</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" rows="4" class="form-control" placeholder="Write notification content..." required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="expiryDate">Expiry Date</label>
                                    <input type="date" name="expiryDate" class="form-control" required value="<?= htmlspecialchars($_POST['expiryDate'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="box-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#sentNotificationsModal">
                                        View Sent Announcements
                                    </button>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary">Create Notification</button>
                                    <a href="adminindex.php" class="btn btn-default">Cancel</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Tips -->
                <div class="col-md-4">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-info-circle"></i> Tips</h3>
                        </div>
                        <div class="box-body">
                            <ul>
                                <li>Keep titles short and clear.</li>
                                <li>Write concise content.</li>
                                <li>Select the correct recipient group.</li>
                                <li>Set expiry date to auto-remove old notifications.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>



<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= $success ?>',
    confirmButtonColor: '#3085d6'
});
</script>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    html: `<?= implode("<br>", array_map('htmlspecialchars', $errors)) ?>`,
    confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>



<?php
// Fetch sent notifications for admin
$sentNotifications = $connect->query("
    SELECT n.NotificationId, n.Title, n.Content, n.Priority, n.CreatedAt, n.ExpiryDate, 
           CASE n.CreatedFor 
                WHEN 1 THEN 'Learners'
                WHEN 2 THEN 'Tutors'
                WHEN 12 THEN 'Both'
           END AS Recipient
    FROM notifications n
    WHERE n.CreatedBy = $userId
    ORDER BY n.CreatedAt DESC
    LIMIT 20
");
?>

<!-- Sent Announcements Modal -->
<div class="modal fade" id="sentNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="sentNotifTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="sentNotifTitle">Sent Announcements</h4>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <?php if ($sentNotifications && $sentNotifications->num_rows > 0): ?>
            <?php while ($notice = $sentNotifications->fetch_assoc()): ?>
                <div class="panel panel-default" style="margin-bottom:10px;">
                    <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center; background:#f5f5f5;">
                        <span><strong>Created At: </strong><?= htmlspecialchars($notice['CreatedAt']) ?> <strong>| Expiry:</strong> <?= $notice['ExpiryDate'] ?></span>

                        <form method="POST" action="delete_notification.php" style="margin:0;">
                            <input type="hidden" name="NotificationId" value="<?= $notice['NotificationId'] ?>">

                            <button type="button" class="btn btn-xs btn-danger deleteNotificationBtn" data-id="<?= $notice['NotificationId'] ?>">
                            <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                    <div class="panel-body">
                        <p><strong>Header: </strong><?= htmlspecialchars($notice['Title']) ?> | <?= $notice['Recipient'] ?></p>

                        <p><strong>Content:</strong> <small><?= nl2br(htmlspecialchars($notice['Content'])) ?></small></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center text-muted">
                No sent announcements.
            </div>
        <?php endif; ?>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
document.querySelectorAll('.deleteNotificationBtn').forEach(button => {
  button.addEventListener('click', function() {
    const notificationId = this.dataset.id;
    const panel = this.closest('.panel'); // grab the parent panel for removal

    Swal.fire({
      title: 'Are you sure?',
      text: "This announcement will be permanently deleted.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        fetch('delete_notification.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `NotificationId=${notificationId}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            // Remove the panel visually
            panel.style.transition = "opacity 0.5s";
            panel.style.opacity = "0";
            setTimeout(() => panel.remove(), 500);

            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: data.message,
              confirmButtonColor: '#3085d6'
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: data.message,
              confirmButtonColor: '#d33'
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Failed to delete notification.',
            confirmButtonColor: '#d33'
          });
        });
      }
    });
  });
});
</script>


</body>
</html>


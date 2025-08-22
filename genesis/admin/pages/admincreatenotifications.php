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

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Create Notification</button>
                                <a href="adminindex.php" class="btn btn-default">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

</body>
</html>


<section class="content">
      <div class="row">
        <!-- Metric Cards -->
        <div class="col-md-3">
          <div class="box" style="background:#e6f0ff; border-radius:15px; box-shadow:0 0 10px rgba(0,0,0,0.05);">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Pending Homework</h4>
              <h2 style="font-weight:bold;"><?= $pendingHomeworkCount ?></h2>
              <i class="fa fa-tasks fa-2x pull-right" style="color:#6a52a3;"></i>
              <a href="homework.php" class="btn btn-link">View All</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f9f1fe; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Average Score</h4>
              <h2><?= $averageScore ?>%</h2>
              <i class="fa fa-bar-chart fa-2x pull-right" style="color:#a06cd5;"></i>
              <a href="myresults.php" class="btn btn-link">View Results</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f0f7ff; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Attendance</h4>
              <h2>%</h2>
              <i class="fa fa-calendar-check-o fa-2x pull-right" style="color:#0073e6;"></i>
              <a href="attendance.php" class="btn btn-link">Track Attendance</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#d1ffe0; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Completed Tasks</h4>
              <h2><?= $completedTasksCount ?></h2>
              <i class="fa fa-check-circle fa-2x pull-right" style="color:#28a745;"></i>
              <a href="completed.php" class="btn btn-link">View Completed</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box" style="border-top:3px solid #3a3a72;">
            <div class="box-header with-border">
              <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body text-center">
              <a href="startquiz.php" class="btn btn-primary" style="margin:5px;">📘 Start New Quiz</a>
              <a href="studymaterials.php" class="btn btn-info" style="margin:5px;">📂 View Study Materials</a>
              <a href="myresults.php" class="btn btn-success" style="margin:5px;">📊 Check Results</a>
              <a href="homework.php" class="btn btn-warning" style="margin:5px;">📝 Pending Homework</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box" style="border-top:3px solid #ff9800;">
            <div class="box-header with-border">
              <h3 class="box-title">Class Leaderboard</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Learner</th>
                    <th>Score (%)</th>
                    <th>Badges</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Jane Doe</td>
                    <td>95%</td>
                    <td>🏆🎯</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>John Smith</td>
                    <td>92%</td>
                    <td>🎯</td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Mary Johnson</td>
                    <td>90%</td>
                    <td>🎯</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>





      <div class="row">

        <!-- Upcoming Homework -->
        <div class="col-md-6">
          <div class="box" style="border-top:3px solid #0073e6;">
            <div class="box-header with-border">
              <h3 class="box-title">Upcoming Homework</h3>
            </div>
            <div class="box-body">
              <ul>
                <li>Math – Algebra Quiz <span style="color:red;">Due Tomorrow</span></li>
                <li>Science – Lab Report <span style="color:orange;">Due in 3 days</span></li>
                <li>History – Essay <span style="color:green;">Due in 1 week</span></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Recent Results -->
        <div class="col-md-6">
          <div class="box" style="border-top:3px solid #28a745;">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Results</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tr><th>Subject</th><th>Score</th><th>Date</th></tr>
                <tr><td>Math</td><td>85%</td><td>Aug 10</td></tr>
                <tr><td>Science</td><td>92%</td><td>Aug 7</td></tr>
              </table>
            </div>
          </div>
        </div>

      </div>

      <div class="row">

          <!-- Announcements -->
          <div class="col-md-6">
            <div class="box" style="border-top:3px solid #ff9800;">
              <div class="box-header with-border">
                <h3 class="box-title">Announcements</h3>
              </div>
              <div class="box-body">
                <p>📢 School closed on Friday for maintenance.</p>
                <p>📢 New worksheets uploaded for Grade 10 Science.</p>
              </div>
            </div>
          </div>

          <!-- Achievements -->
          <div class="col-md-6">
            <div class="box" style="border-top:3px solid #9c27b0;">
              <div class="box-header with-border">
                <h3 class="box-title">Your Achievements</h3>
              </div>
              <div class="box-body">
                <span class="badge" style="background:#28a745;">Perfect Score</span>
                <span class="badge" style="background:#0073e6;">On-Time Submissions</span>
                <span class="badge" style="background:#ff9800;">Consistency Star</span>
              </div>
            </div>
          </div>

      </div>

      </div>


    </section>
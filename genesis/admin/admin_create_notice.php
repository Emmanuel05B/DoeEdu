<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include('../partials/connect.php');

$userId = $_SESSION['user_id'];
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $createdFor = $_POST['createdFor'] ?? '';

  if ($title === '') $errors[] = "Title is required.";
  if ($content === '') $errors[] = "Content is required.";
  if (!in_array($createdFor, ['1', '2', '12'])) $errors[] = "Invalid recipient selection.";

  if (empty($errors)) {
    $stmt = $connect->prepare("INSERT INTO notices (Title, Content, CreatedBy, CreatedFor) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $userId, $createdFor);
    if ($stmt->execute()) {
      $success = "Notice successfully created.";
    } else {
      $errors[] = "Error: " . $stmt->error;
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>
<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Create Notice</h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create Notice</li>
      </ol>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="box box-primary" style="max-width: 700px; margin: auto;">
          <div class="box-header with-border">
            <h3 class="box-title">Notice Details</h3>
          </div>

          <form method="POST" action="">
            <div class="box-body">

              <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
              <?php endif; ?>

              <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                  <ul>
                    <?php foreach ($errors as $error): ?>
                      <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
              </div>

              <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" rows="5" class="form-control" placeholder="Write the notice here..." required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
              </div>

              <div class="form-group">
                <label for="createdFor">Send To</label>
                <select name="createdFor" class="form-control" required>
                  <option value="">-- Select --</option>
                  <option value="1" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '1') ? 'selected' : '' ?>>Learners</option>
                  <option value="2" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '2') ? 'selected' : '' ?>>Tutors</option>
                  <option value="12" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '12') ? 'selected' : '' ?>>Both Learners & Tutors</option>
                </select>
              </div>

            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Create Notice</button>
              <a href="adminindex.php" class="btn btn-default">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Admin scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>

</body>
</html>

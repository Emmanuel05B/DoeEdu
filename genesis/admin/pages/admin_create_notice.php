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

  if ($title === '') $errors[] = "Title is required.";
  if ($content === '') $errors[] = "Content is required.";
  if (!in_array($createdFor, ['1', '2', '12'])) $errors[] = "Invalid recipient selection.";
  if ($expiryDate === '') $errors[] = "Expiry date is required.";

  if (empty($errors)) {
    $stmt = $connect->prepare("INSERT INTO notices (Title, Content, CreatedBy, CreatedFor, ExpiryDate) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiis", $title, $content, $userId, $createdFor, $expiryDate);
    if ($stmt->execute()) {
      $success = "Notice successfully created.";
    } else {
      $errors[] = "Error: " . $stmt->error;
    }
    $stmt->close();
  }
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php");  ?>
<!-- SweetAlert2 CDN --> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

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
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Notice Details</h3>
          </div>

          <form method="POST" action="">
            <div class="box-body">

              <div class="row">
                <!-- Left side: Title and Content -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                  </div>

                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" rows="5" class="form-control" placeholder="Write the notice here..." required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                  </div>
                </div>

                <!-- Right side: Send To and Expiry Date -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="createdFor">Send To</label>
                    <select name="createdFor" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="1" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '1') ? 'selected' : '' ?>>Learners</option>
                      <option value="2" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '2') ? 'selected' : '' ?>>Tutors</option>
                      <option value="12" <?= (isset($_POST['createdFor']) && $_POST['createdFor'] == '12') ? 'selected' : '' ?>>Both Learners & Tutors</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="date" name="expiryDate" class="form-control" required value="<?= htmlspecialchars($_POST['expiryDate'] ?? '') ?>">
                  </div>
                </div>
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
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
<script src="../../common/dist/js/demo.js"></script>

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

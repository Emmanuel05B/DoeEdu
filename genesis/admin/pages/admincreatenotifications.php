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


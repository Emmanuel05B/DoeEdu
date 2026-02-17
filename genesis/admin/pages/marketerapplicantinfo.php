<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");
include_once(BASE_PATH . "/partials/connect.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

// Validate GET parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid applicant ID.";
    header("Location: marketerapplications.php");
    exit();
}

$appId = intval($_GET['id']);

// Fetch applicant info
$sqlApplicant = "SELECT * FROM marketerapplications WHERE Id = ?";
$stmt = $connect->prepare($sqlApplicant);
$stmt->bind_param('i', $appId);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc();

if (!$applicant) {
    $_SESSION['error'] = "Applicant not found.";
    header("Location: marketerapplications.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<?php include_once(COMMON_PATH . "/../partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Applicant Details</h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="marketerapplications.php">Applications</a></li>
            <li class="active">Applicant Info</li>
        </ol>
    </section>

    <section class="content">

        <!-- Applicant Name Header -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="box-title"><?= htmlspecialchars($applicant['Name'] . ' ' . $applicant['Surname']) ?></h4>
                        <p><?= htmlspecialchars($applicant['Email']) ?></p>
                    </div>

                    <div class="col-md-6">
                        <?php if (!empty($applicant['CV_Matric'])): ?>  
                            <a href="<?= CVS_URL ?>/<?= htmlspecialchars($applicant['CV_Matric']) ?>" 
                               class="btn btn-info btn-xs" target="_blank"><i class="fa fa-file"></i> View CV</a>
                        <?php else: ?>
                            <span class="text-muted">No CV uploaded</span>
                        <?php endif; ?><br>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">

                <!-- Academic / Skills Information -->
                <div class="box box-info">
                    <div class="box-header">
                        <h4 class="box-title">Academic & Skills Info</h4>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr><th>Matric Completed?</th><td><?= htmlspecialchars($applicant['Matric']) ?></td></tr>
                            <tr><th>English Mark (%)</th><td><?= htmlspecialchars($applicant['EnglishMark']) ?>%</td></tr>
                            <tr><th>Digital Marketing Skill Level</th><td><?= htmlspecialchars($applicant['DigitalMarketingSkill']) ?></td></tr>
                            <tr><th>Marketing Tools Familiarity</th><td><?= htmlspecialchars($applicant['MarketingTools']) ?></td></tr>
                            <tr><th>Content Creation Experience</th><td><?= htmlspecialchars($applicant['ContentCreation']) ?></td></tr>
                            <tr><th>Communication Skills</th><td><?= htmlspecialchars($applicant['Communication']) ?></td></tr>
                            <tr><th>Social Media Experience</th><td><?= htmlspecialchars($applicant['SocialMediaExp']) ?></td></tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <!-- Experience & Availability -->
                <div class="box box-success">
                    <div class="box-header">
                        <h4 class="box-title">Experience & Availability</h4>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr><th>Marketing Experience</th><td><?= htmlspecialchars($applicant['Experience'] ?? '-') ?></td></tr>
                            <tr><th>Expected Pay (per hour)</th><td>R <?= htmlspecialchars($applicant['ExpectedPay']) ?></td></tr>
                            <tr><th>Preferred Working Days</th><td><?= htmlspecialchars($applicant['PreferredDays'] ?? '-') ?></td></tr>
                            <tr><th>Preferred Working Hours</th><td><?= htmlspecialchars($applicant['PreferredTime'] ?? '-') ?></td></tr>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <!-- Back Button -->
        <div class="box-footer">
            <a href="marketerapplications.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Applications</a>
        </div>

    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>

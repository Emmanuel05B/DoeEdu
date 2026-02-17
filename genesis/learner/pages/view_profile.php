<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

$learnerId = $_SESSION['user_id'] ?? null;
if (!$learnerId) {
    die("User not logged in.");
}

// Fetch learner profile
$stmt = $connect->prepare("SELECT * FROM learnerprofiles WHERE LearnerId = ?");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>My Profile <small>View your personal information and preferences</small></h1>
        
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">My Profile</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Learning Profile</h3>
                        <div class="box-tools pull-right">
                            <a href="profilesettings.php#learning_profile" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <p><strong>About Me:</strong> <?= htmlspecialchars($profile['AboutLearner'] ?? 'N/A') ?></p>
                        <p><strong>Learning Style:</strong> <?= htmlspecialchars($profile['LearningStyle'] ?? 'N/A') ?></p>
                        <p><strong>Study Challenges:</strong> 
                            <?php 
                                $challenges = json_decode($profile['StudyChallenges'] ?? '[]', true);
                                echo $challenges ? implode(', ', $challenges) : 'N/A';
                            ?>
                        </p>
                        <p><strong>Concentration Span:</strong> <?= htmlspecialchars($profile['ConcentrationSpan'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>

            <!-- Availability -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Availability</h3>
                        <div class="box-tools pull-right">
                            <a href="profilesettings.php#availability" class="btn btn-xs btn-success">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <p><strong>Preferred Day:</strong> <?= htmlspecialchars($profile['PreferredDay'] ?? 'N/A') ?></p>
                        <p><strong>Preferred Time:</strong> <?= htmlspecialchars($profile['PreferredTime'] ?? 'N/A') ?></p>
                        <p><strong>Session Length:</strong> <?= htmlspecialchars($profile['SessionLength'] ?? 'N/A') ?></p>
                        <p><strong>Other Classes:</strong> <?= htmlspecialchars($profile['OtherClasses'] ?? 'N/A') ?></p>
                        <p><strong>Chores at Home:</strong> <?= htmlspecialchars($profile['ChoresHome'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Session Style -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Session Style</h3>
                        <div class="box-tools pull-right">
                            <a href="profilesettings.php#session_style" class="btn btn-xs btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <p><strong>Session Format:</strong> <?= htmlspecialchars($profile['SessionFormat'] ?? 'N/A') ?></p>
                        <p><strong>Break Preferences:</strong> <?= htmlspecialchars($profile['BreakPreferences'] ?? 'N/A') ?></p>
                        <p><strong>Motivations & Goals:</strong> <?= htmlspecialchars($profile['MotivationsGoals'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>

            <!-- Technical Setup -->
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Technical Setup</h3>
                        <div class="box-tools pull-right">
                            <a href="profilesettings.php#technical_setup" class="btn btn-xs btn-danger">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <p><strong>Devices:</strong> <?= htmlspecialchars($profile['Devices'] ?? 'N/A') ?></p>
                        <p><strong>Internet Reliability:</strong> <?= htmlspecialchars($profile['InternetReliability'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>

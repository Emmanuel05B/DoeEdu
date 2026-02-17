<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

$learnerId = $_SESSION['user_id'] ?? null;
if (!$learnerId) {
    die("User not logged in.");
}

$formType = $_POST['form_type'] ?? '';
if (!$formType) die("No form type specified.");

// Check if profile exists
$stmtCheck = $connect->prepare("SELECT Id FROM learnerprofiles WHERE LearnerId = ?");
$stmtCheck->bind_param("i", $learnerId);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$profileExists = $resultCheck->num_rows > 0;

// Prepare fields
$fields = [];
$values = [];

switch ($formType) {
    case 'learning_profile':
        $fields = ['AboutLearner', 'LearningStyle', 'StudyChallenges', 'ConcentrationSpan'];
        $values = [
            $_POST['about_learner'] ?? null,
            $_POST['learning_style'] ?? null,
            json_encode($_POST['study_challenges'] ?? []),
            $_POST['concentration_span'] ?? null
        ];
        break;

    case 'availability':
        $fields = ['PreferredDay', 'PreferredTime', 'SessionLength', 'OtherClasses', 'ChoresHome'];
        $values = [
            $_POST['preferred_day'] ?? null,
            $_POST['preferred_time'] ?? null,
            $_POST['session_length'] ?? null,
            $_POST['other_classes'] ?? null,
            $_POST['chores_home'] ?? null
        ];
        break;

    case 'session_style':
        $fields = ['SessionFormat', 'BreakPreferences', 'MotivationsGoals'];
        $values = [
            $_POST['session_format'] ?? null,
            $_POST['break_preferences'] ?? null,
            $_POST['motivations_goals'] ?? null
        ];
        break;

    case 'technical_setup':
        $fields = ['Devices', 'InternetReliability', 'StrengthsSkills'];
        $values = [
            $_POST['devices'] ?? null,
            $_POST['internet_reliability'] ?? null,
            $_POST['strengths_skills'] ?? null
        ];
        break;

    default:
        die("Invalid form type.");
}

if ($profileExists) {
    $setParts = [];
    foreach ($fields as $f) $setParts[] = "$f = ?";
    $sql = "UPDATE learnerprofiles SET " . implode(", ", $setParts) . ", UpdatedAt = NOW() WHERE LearnerId = ?";
    $stmt = $connect->prepare($sql);

    // Bind dynamically
    $types = str_repeat("s", count($values)) . "i";
    $stmt->bind_param($types, ...array_merge($values, [$learnerId]));

} else {
    $allFields = array_merge(['LearnerId'], $fields);
    $placeholders = implode(',', array_fill(0, count($allFields), '?'));
    $sql = "INSERT INTO learnerprofiles (" . implode(',', $allFields) . ") VALUES ($placeholders)";
    $stmt = $connect->prepare($sql);

    $values = array_merge([$learnerId], $values);
    $types = "i" . str_repeat("s", count($values) - 1); // first is integer, rest are strings
    $stmt->bind_param($types, ...$values);
}

if ($stmt->execute()) {
    header("Location: profilesettings.php?status=success");
    exit();
} else {
    header("Location: profilesettings.php?status=error");
    exit();
}

<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}
include_once(BASE_PATH . "/partials/connect.php");

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No learner ID specified.";
    header("Location: pendingverifications.php");
    exit();
}

$learnerId = intval($_GET['id']);

$connect->begin_transaction();

try {
    // Delete learner’s subjects
    $stmtLS = $connect->prepare("DELETE FROM learnersubject WHERE LearnerId = ?");
    $stmtLS->bind_param("i", $learnerId);
    $stmtLS->execute();
    $stmtLS->close();

    // Delete learner record
    $stmtL = $connect->prepare("DELETE FROM learners WHERE LearnerId = ?");
    $stmtL->bind_param("i", $learnerId);
    $stmtL->execute();
    $stmtL->close();

    // Delete from users
    $stmtU = $connect->prepare("DELETE FROM users WHERE Id = ?");
    $stmtU->bind_param("i", $learnerId);
    $stmtU->execute();
    $stmtU->close();

    $connect->commit();

    $_SESSION['success'] = "Learner and all related subjects have been successfully deregistered.";
    header("Location: pendingverifications.php");
    exit();

} catch (Exception $e) {
    $connect->rollback();
    $_SESSION['error'] = "Failed to deregister learner: " . $e->getMessage();
    header("Location: pendingverifications.php");
    exit();
}
?>

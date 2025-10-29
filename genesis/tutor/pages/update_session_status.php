<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sessionId = $_POST['session_id'];
    $action = $_POST['action'];

    if ($action == 'decline') {
    
        $stmt = $connect->prepare("UPDATE tutorsessions SET Status = 'Declined' WHERE SessionId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Session Declined',
            'message' => 'The session has been successfully declined.'
        ];
        header("Location: schedule.php");
        exit;
    }

    if ($action == 'accept') {
        $stmt = $connect->prepare("UPDATE tutorsessions SET Status = 'Confirmed' WHERE SessionId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Session Accepted',
            'message' => 'The session has been accepted.'
        ];
        header("Location: schedule.php");
        exit;
    }
}

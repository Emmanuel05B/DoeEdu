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
    $sessionId = $_POST['sessionid'];
    $mode = $_POST['mode'] ?? '';

    if ($mode === 'remove_missed') {
        $stmt = $connect->prepare("UPDATE tutorsessions SET Hidden = 1 WHERE SessionId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Removed',
            'message' => 'The session has been removed from your list.'
        ];
    } else {
        $stmt = $connect->prepare("DELETE FROM tutorsessions WHERE SessionId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => 'The session has been deleted successfully.'
        ];
    }

    header("Location: mytutors.php");
    exit;
}

?>



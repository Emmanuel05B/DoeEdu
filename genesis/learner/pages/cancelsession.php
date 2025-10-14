<?php
session_start();
include('../../partials/connect.php');

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








<?php
/*
session_start();
include('../../partials/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sessionId = $_POST['sessionid'];
    
    
        $stmt = $connect->prepare("DELETE FROM tutorsessions WHERE SessionId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Request Deleted',
            'message' => 'The request has been successfully deleted.'
        ];
        header("Location: mytutors.php");
        exit;

*/
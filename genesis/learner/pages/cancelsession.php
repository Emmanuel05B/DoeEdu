<?php
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

}

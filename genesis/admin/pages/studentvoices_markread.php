<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");
$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $connect->prepare("UPDATE studentvoices SET IsRead = 1 WHERE Id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Marked as read.";
    } else {
        $_SESSION['error'] = "Failed to mark as read.";
    }
    $stmt->close();
}

header("Location: voices.php");
exit();
?>

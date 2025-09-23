<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $anonymous = isset($_POST['anonymous']) && $_POST['anonymous'] == '1' ? 1 : 0;
    $userId = $anonymous ? null : ($_SESSION['user_id'] ?? null);

    if (empty($message)) {
        $_SESSION['error'] = "Please enter a message.";
    } else {
        $stmt = $connect->prepare("
            INSERT INTO studentvoices (UserId, Subject, Message, IsAnonymous, CreatedAt)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("issi", $userId, $subject, $message, $anonymous);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Your feedback has been submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit feedback. Please try again.";
        }
        $stmt->close();
    }

    // Redirect back to voices page
    header("Location: studentvoices.php");
    exit();
}
?>

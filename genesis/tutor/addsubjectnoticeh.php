<?php
session_start();
include('../partials/connect.php');

// Enable error reporting for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($_SESSION['email'])) {
        echo "<script>alert('Session expired. Please log in again.'); window.location.href = '../common/login.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $subjectName = trim($_POST['subjectName'] ?? ''); // must match form
        $grade = trim($_POST['grade'] ?? '');
        $createdBy = $_SESSION['user_id'];

        if ($title === '' || $content === '' || $subjectName === '' || $grade === '') {
            echo "<script>alert('All fields are required.'); window.history.back();</script>";
            exit();
        }

        $sql = "INSERT INTO subjectnotices (Title, Content, SubjectName, Grade, CreatedBy, CreatedAt, IsOpened) 
                VALUES (?, ?, ?, ?, ?, NOW(), 0)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssssi", $title, $content, $subjectName, $grade, $createdBy);
        $stmt->execute();

        echo "<script>alert('Notice added successfully.'); window.location.href = 'classes1.php';</script>";
    } else {
        echo "<script>alert('Invalid request method.'); window.location.href = 'classes1.php';</script>";
    }
} catch (mysqli_sql_exception $e) {
    echo "<script>alert('Database Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
}
?>

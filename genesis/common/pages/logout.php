<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$_SESSION = array();
session_destroy();
header("Location: login.php");
exit();
?>

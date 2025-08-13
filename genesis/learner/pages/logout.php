<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
$_SESSION = array();
session_destroy();
header("Location: ../../common/pages/login.php"); 
exit();
?>


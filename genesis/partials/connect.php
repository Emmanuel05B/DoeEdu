<?php
$Host ="localhost";
$User ="root";
$Password ="";
$dbname = "newgenesis";
$connect = mysqli_connect($Host,$User,$Password,$dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
<?php
$host = 'localhost';
$user = 'rwkhfkzv_Udin';
$pass = 'Sahrul1102#';
$dbname = 'rwkhfkzv_gadgetar';

$con = mysqli_connect($host, $user, $pass, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
<?php
ini_set('display_errors', 1);
// error_reporting(~0);

$host = "localhost";
$username = "root";
$pass = "";
$databaseName="ecomercc";
$dbConnect = mysqli_connect($host, $username, $pass,$databaseName);

?>
<?php
global $con;
$host = "localhost";
$user = "root";
$password = "YOUR_MYSQL_PASSWORD";   // replace with your actual password
$dbname = "uilkashdb_b";

$con = mysqli_connect($host, $user, $password, $dbname);
if (!$con) {
    echo "Failed to establish secure connection. Please try again!";
    exit;
}
?>

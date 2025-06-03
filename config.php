<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'tro1';
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
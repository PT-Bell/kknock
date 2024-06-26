<?php
$servername = "localhost";
$username = "bell";
$password = "1207";
$dbname = "kknock-web";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


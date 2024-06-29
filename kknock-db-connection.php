<?php
$servername = "db";  // Docker Compose에서 데이터베이스 서비스의 이름
$username = "ybell";
$password = "gungail127";
$dbname = "kknock_db";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Successfully connected to the database";
}
?>

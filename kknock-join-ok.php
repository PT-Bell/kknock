<?php
session_start();
include 'kknock-db-connection.php';

if (!isset($_POST['join-name']) || !isset($_POST['join-id']) || !isset($_POST['join-pw'])) {
    header("Content-type: text/html; charset=UTF-8");
    echo "<script>alert('기입하지 않은 정보가 있거나 잘못된 접근입니다.');";
    echo "window.location.replace('kknock-join.php');</script>";
    exit;
}

$joinName = $_POST['join-name'];
$joinId = $_POST['join-id'];
$joinPw = $_POST['join-pw'];

// 비밀번호 해싱
$hashedPw = password_hash($joinPw, PASSWORD_DEFAULT);

// 데이터베이스 연결
$conn = mysqli_connect('localhost', 'bell', '1207', 'kknock-web');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 준비된 문을 사용한 안전한 쿼리
$stmt = $conn->prepare("INSERT INTO users (username, user_id, password, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $joinName, $joinId, $hashedPw);

if ($stmt->execute()) {
    echo "<script>alert('회원가입이 완료되었습니다.');";
    echo "window.location.replace('kknock-login.php');</script>";
    exit;
} else {
    echo "<script>alert('저장에 문제가 생겼습니다. 관리자에게 문의해주세요.');</script>";
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<meta http-equiv="refresh" content="0;url=kknock-main.php">

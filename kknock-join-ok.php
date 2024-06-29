<?php
if (!isset($_POST['joinName']) || !isset($_POST['joinId']) || !isset($_POST['joinPw'])) {
    header("Content-type: text/html; charset=UTF-8");
    echo "<script>alert('기입하지 않은 정보가 있거나 잘못된 접근입니다.');";
    echo "window.location.replace('kknock-join.php');</script>";
    exit;
}

$join_name = $_POST['joinName'];
$join_id = $_POST['joinId'];
$join_pw = password_hash($_POST['joinPw'], PASSWORD_DEFAULT); // 비밀번호 해시화

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 신규 회원정보 삽입
$sql = "INSERT INTO users (username, user_id, user_password, created_at) VALUES (?, ?, ?, now())";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $join_name, $join_id, $join_pw);
$res = mysqli_stmt_execute($stmt);

if ($res) {
    echo "<script>alert('회원가입이 완료되었습니다.');";
    echo "window.location.replace('kknock-login.php');</script>";
    exit;
} else {
    echo "<script>alert('저장에 문제가 생겼습니다. 관리자에게 문의해주세요.');";
    echo mysqli_error($conn);
    echo "</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
<meta http-equiv="refresh" content="0;url=kknock-main.php">

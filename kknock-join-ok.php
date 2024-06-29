<?php
if (!isset($_POST['joinName']) || !isset($_POST['joinId']) || !isset($_POST['joinPw'])) {
    header("Content-type: text/html; charset=UTF-8");
    echo "<script>alert('기입하지 않은 정보가 있거나 잘못된 접근입니다.');";
    echo "window.location.replace('kknock-join.php');</script>";
    exit;
}
$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

$join_name = mysqli_real_escape_string($conn, $_POST['joinName']);
$join_id = mysqli_real_escape_string($conn, $_POST['joinId']);
$join_pw = mysqli_real_escape_string($conn, $_POST['joinPw']);
$join_pw2 = mysqli_real_escape_string($conn, $_POST['joinPw2']);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 회원 가입 쿼리 실행
$sql = "INSERT INTO users (username, user_id, user_password, created_at) VALUES ('$join_name', '$join_id', '$join_pw', NOW())";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script>alert('회원가입이 완료되었습니다.');</script>";
    header("Location: kknock-login.php");
    exit;
} else {
    echo "<script>alert('저장에 문제가 생겼습니다. 관리자에게 문의해주세요.');</script>";
    echo mysqli_error($conn);
}

mysqli_close($conn);
?>
<meta http-equiv="refresh" content="0;url=kknock-main.php">

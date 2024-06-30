<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    error_log("로그인이 필요합니다.");
    echo "<script>alert('로그인이 필요합니다.'); window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_POST['comment']) || !isset($_POST['post_id']) || !isset($_POST['board_type'])) {
    error_log("잘못된 접근입니다. comment: " . isset($_POST['comment']) . " post_id: " . isset($_POST['post_id']) . " board_type: " . isset($_POST['board_type']));
    echo "<script>alert('잘못된 접근입니다.'); window.location.replace('kknock-main.php');</script>";
    exit;
}

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die("Connection failed: " . mysqli_connect_error());
}

$post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
$board_type = mysqli_real_escape_string($conn, $_POST['board_type']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);
$user_id = $_SESSION['userId'];
$user_name = $_SESSION['userName'];

$sql = "INSERT INTO comments (post_id, board_type, user_id, user_name, comment) VALUES ('$post_id', '$board_type', '$user_id', '$user_name', '$comment')";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('댓글이 작성되었습니다.'); window.location.replace('kknock-board-view.php?id=$post_id&board_type=$board_type');</script>";
} else {
    error_log("댓글 작성 실패: " . mysqli_error($conn));
    echo "<script>alert('댓글 작성에 실패했습니다. 다시 시도해주세요.'); window.history.back();</script>";
}

mysqli_close($conn);
?>

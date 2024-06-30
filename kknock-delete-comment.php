<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_POST['comment_id']) || !isset($_POST['post_id']) || !isset($_POST['board_type'])) {
    echo "<script>alert('잘못된 접근입니다.'); window.location.replace('kknock-main.php');</script>";
    exit;
}

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
$post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
$board_type = mysqli_real_escape_string($conn, $_POST['board_type']);
$user_id = $_SESSION['userId'];

$sql = "DELETE FROM comments WHERE id = '$comment_id' AND user_id = '$user_id'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('댓글이 삭제되었습니다.'); window.location.replace('kknock-board-view.php?id=$post_id&board_type=$board_type');</script>";
} else {
    echo "<script>alert('댓글 삭제에 실패했습니다. 다시 시도해주세요.'); window.history.back();</script>";
}

mysqli_close($conn);
?>

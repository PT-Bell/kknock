<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('로그인이 필요합니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['board_type'])) {
    echo "<script>alert('잘못된 접근입니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$board_type = mysqli_real_escape_string($conn, $_GET['board_type']);
$table = '';

switch ($board_type) {
    case 'general':
        $table = 'general_board';
        break;
    case 'free':
        $table = 'free_board';
        break;
    case 'qna':
        $table = 'qna_board';
        break;
    default:
        echo "<script>alert('잘못된 게시판 유형입니다.');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
}

$sql = "DELETE FROM $table WHERE id='$id' AND writer='".$_SESSION['userId']."'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('게시글이 삭제되었습니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
} else {
    echo "<script>alert('삭제 중 오류가 발생했습니다.');";
    echo "window.history.back();</script>";
}

mysqli_close($conn);
?>

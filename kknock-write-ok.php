<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('로그인이 필요합니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['board_type'])) {
    echo "<script>alert('기입하지 않은 정보가 있거나 잘못된 접근입니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$board_type = mysqli_real_escape_string($conn, $_POST['board_type']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$writer = $_SESSION['userId'];
$name = $_SESSION['userName'];
$file_path = '';

if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['uploaded_file']['name']);
    $file_path = $upload_dir . $file_name;
    if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
        $file_path = '';
    }
}

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

$sql = "INSERT INTO $table (title, writer, name, written, content, file_path) VALUES ('$title', '$writer', '$name', NOW(), '$content', '$file_path')";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('게시글이 작성되었습니다.');";
    echo "window.location.replace('kknock-{$board_type}-board.php');</script>";
} else {
    echo "<script>alert('저장에 문제가 생겼습니다. 관리자에게 문의해주세요.');";
    echo "window.history.back();</script>";
}

mysqli_close($conn);
?>

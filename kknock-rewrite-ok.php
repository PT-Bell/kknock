<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('로그인이 필요합니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['board_type']) || !isset($_POST['title']) || !isset($_POST['content'])) {
    echo "<script>alert('잘못된 접근입니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = mysqli_real_escape_string($conn, $_POST['id']);
$board_type = mysqli_real_escape_string($conn, $_POST['board_type']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$table = '';
$file_path = '';

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

// 파일 삭제 체크
$delete_file = isset($_POST['delete_file']) && $_POST['delete_file'] == '1';

if ($delete_file) {
    $sql = "SELECT file_path FROM $table WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (!empty($row['file_path']) && file_exists($row['file_path'])) {
            unlink($row['file_path']);
        }
        $file_path = '';
    }
}

// 새 파일 업로드
if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['uploaded_file']['name']);
    $file_path = $upload_dir . $file_name;
    if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
        $file_path = '';
    }
}

// 파일 경로 업데이트
$sql = "UPDATE $table SET title='$title', content='$content', file_path='$file_path' WHERE id='$id' AND writer='".$_SESSION['userId']."'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('게시글이 수정되었습니다.');";
    echo "window.location.replace('kknock-board-view.php?id=$id&board_type=$board_type');</script>";
} else {
    echo "<script>alert('수정 중 오류가 발생했습니다.');";
    echo "window.history.back();</script>";
}

mysqli_close($conn);
?>

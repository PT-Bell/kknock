<?php
session_start();
if(!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('비회원입니다!');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

$title = $_POST['title'];
$content = $_POST['content'];
$writer = $_SESSION['userId'];
$name = $_SESSION['userName'];
$board_type = $_POST['board_type'];

echo "<script>console.log('Board Type: " . $board_type . "');</script>";


$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$table = "";
switch ($board_type) {
    case 'general':
        $table = "general_board";
        break;
    case 'free':
        $table = "free_board";
        break;
    case 'qna':
        $table = "qna_board";
        break;
    default:
        echo "<script>alert('잘못된 게시판입니다.');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
}

$stmt = $conn->prepare("INSERT INTO $table (title, writer, name, written, content) VALUES (?, ?, ?, NOW(), ?)");
$stmt->bind_param("ssss", $title, $writer, $name, $content);

if ($stmt->execute()) {
    echo "<script>alert('게시글이 작성되었습니다.');";
    echo "window.location.replace('kknock-$board_type-board.php');</script>";
} else {
    echo "<script>alert('저장에 문제가 생겼습니다. 관리자에게 문의해주세요.');</script>";
}

$stmt->close();
$conn->close();
?>

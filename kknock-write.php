<?php
session_start();
if(!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('비회원입니다!');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

$board_type = $_GET['board_type'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Write</title>
</head>
<body>
    <h2>글쓰기</h2>
    <form method="post" action="kknock-write-ok.php?board_type=<?php echo htmlspecialchars($board_type); ?>" autocomplete="off">
        <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($board_type); ?>">
        <p><input type="text" size="25" name="title" placeholder="제목" required></p>
        <hr width="250px" align="left">
        <p><textarea cols="35" rows="15" name="content" placeholder="내용을 입력하세요." required></textarea></p>
        <p><input type="submit" value="글쓰기"></p>
    </form>
</body>
</html>

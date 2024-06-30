<?php
    session_start();
    if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
        echo "<script>alert('로그인이 필요합니다.');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
    }

    if (!isset($_GET['id'])) {
        echo "<script>alert('잘못된 접근입니다.');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
    }

    $conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $board_type = ''; // 여기서 어떤 게시판인지 확인할 변수 초기화

    // 일반 게시판에서 게시글 찾기
    $sql = "SELECT * FROM general_board WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $board_type = 'general'; // 일반 게시판인 경우 변수 설정
    }

    // 자유 게시판에서 게시글 찾기 (예시, 필요에 따라 추가)
    if (!$board_type) {
        $sql = "SELECT * FROM free_board WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $board_type = 'free'; // 자유 게시판인 경우 변수 설정
        }
    }

    // 질의응답 게시판에서 게시글 찾기 (예시, 필요에 따라 추가)
    if (!$board_type) {
        $sql = "SELECT * FROM qna_board WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $board_type = 'qna'; // 질의응답 게시판인 경우 변수 설정
        }
    }

    mysqli_close($conn);

    if (!$board_type) {
        echo "<script>alert('게시글을 찾을 수 없습니다.');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?> - 게시글 보기</title>
    <link href="/kknock-header.css" rel="stylesheet">
    <style>
        .container {
            max-width: 960px;
            height: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .post {
            padding: 20px;
            height: 500px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .post-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .post-meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .post-content {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <header>
        <h1>게시글 보기</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>

    <div class="container">
        <div class="post">
            <h2 class="post-title"><?php echo htmlspecialchars($row['title']); ?></h2>
            <div class="post-meta">
                작성자: <?php echo htmlspecialchars($row['writer']) . " (" . htmlspecialchars($row['name']) . ")"; ?><br>
                작성일: <?php echo htmlspecialchars($row['written']); ?>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
        </div>
    </div>
</body>
</html>

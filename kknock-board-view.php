<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.replace('kknock-main.php');</script>";
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['board_type'])) {
    echo "<script>alert('잘못된 접근입니다.'); window.location.replace('kknock-main.php');</script>";
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
        echo "<script>alert('잘못된 게시판 유형입니다.'); window.location.replace('kknock-main.php');</script>";
        exit;
}

$sql = "SELECT * FROM $table WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('게시글을 찾을 수 없습니다.'); window.location.replace('kknock-main.php');</script>";
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
            height: 700px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
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
            height: 500px;
        }

        .post-actions {
            margin-top: 20px;
            text-align: center;
        }

        .post-actions button {
            margin: 0 5px;
            padding: 10px 20px;
            font-size: 14px;
        }

        .file-download {
            margin-top: 20px;
            text-align: center;
        }

        .file-download a {
            text-decoration: none;
            color: blue;
        }

        .comment-section {
            margin-top: 50px;
        }

        .comment-form {
            margin-bottom: 20px;
            display: flex;
            gap: 18px;
        }

        .comment-list {
            list-style-type: none;
            padding: 0;
        }

        .comment-list li {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        textarea {
            width: 800px;
        }
    </style>
</head>
<body>
    <header>
        <h1>게시글 보기</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-general-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>
    <div class="container">
        <div class="post">
            <h2 class="post-title"><?php echo htmlspecialchars($row['title']); ?></h2>
            <div class="post-meta">
                작성자: <?php echo htmlspecialchars($row['name']); ?><br>
                작성일: <?php echo htmlspecialchars($row['written']); ?>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
            <?php if (!empty($row['file_path'])): ?>
            <div class="file-download">
                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>첨부파일 다운로드</a>
            </div>
            <?php endif; ?>
            <?php if ($_SESSION['userId'] == $row['writer']): ?>
                <div class="post-actions">
                    <button onclick="location.href='kknock-rewrite.php?id=<?php echo $id; ?>&board_type=<?php echo $board_type; ?>'">수정</button>
                    <button onclick="if(confirm('정말 삭제하시겠습니까?')) { location.href='kknock-delete.php?id=<?php echo $id; ?>&board_type=<?php echo $board_type; ?>'; }">삭제</button>
                </div>
            <?php endif; ?>
        </div>
        <!-- 댓글 입력 폼 -->
        <div class="comment-section">
            <h3>댓글</h3>
            <form action="kknock-add-comment.php" method="post" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                <input type="hidden" name="board_type" value="<?php echo $board_type; ?>">
                <textarea name="comment" placeholder="댓글을 입력하세요" required></textarea>
                <button type="submit">댓글 달기</button>
            </form>

            <!-- 댓글 목록 -->
            <ul class="comment-list">
                <?php
                $conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
                $sql = "SELECT * FROM comments WHERE post_id = '$id' AND board_type = '$board_type' ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                while ($comment = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($comment['user_name']); ?></strong>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        <small><?php echo $comment['created_at']; ?></small>
                        <?php if ($_SESSION['userId'] == $comment['user_id']): ?>
                            <form action="kknock-delete-comment.php" method="post" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="board_type" value="<?php echo $board_type; ?>">
                                <button type="submit">삭제</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
                <?php mysqli_close($conn); ?>
            </ul>
        </div>
    </div>
</body>
</html>

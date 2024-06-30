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

$sql = "SELECT * FROM $table WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('게시글을 찾을 수 없습니다.');";
    echo "window.location.replace('kknock-main.php');</script>";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 수정</title>
    <link href="/kknock-header.css" rel="stylesheet">
    <style>
        .modify-container {
            text-align: center;
        }
        #title {
            width: 876px;
        }
        #content {
            width: 876px;
            height: 500px;            
        }
        .write-content {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .file-content {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .modify {
            margin-top: 20px;
        }
        .modify > button,
        .file-content > button {
            margin: 0 5px;
            padding: 10px 20px;
            font-size: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .modify > button:hover,
        .file-content > button:hover {
            background-color: #0056b3;
        }
        #uploaded_file {
            display: none;
        }
    </style>
    <script>
        function confirmDelete() {
            if (confirm('정말 파일을 삭제하시겠습니까?')) {
                document.getElementById('delete_file_hidden').value = '1';
                document.getElementById('current_file').style.display = 'none';
            }
        }

        function validateForm() {
            const deleteFile = document.getElementById('delete_file_hidden').value;
            const uploadedFile = document.getElementById('uploaded_file').files.length;
            const hasExistingFile = <?php echo !empty($row['file_path']) ? 'true' : 'false'; ?>;
            
            if (hasExistingFile && deleteFile === '0' && uploadedFile > 0) {
                alert('기존 파일이 있습니다. 먼저 파일을 삭제해주세요.');
                return false;
            }
            return true;
        }

        function triggerFileUpload() {
            document.getElementById('uploaded_file').click();
        }

        function displayFileName() {
            const fileInput = document.getElementById('uploaded_file');
            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;
                document.getElementById('file_name_display').innerText = fileName;
                document.getElementById('cancel_upload').style.display = 'inline-block';
            } else {
                document.getElementById('file_name_display').innerText = '';
                document.getElementById('cancel_upload').style.display = 'none';
            }
        }

        function cancelFileUpload() {
            const fileInput = document.getElementById('uploaded_file');
            fileInput.value = ''; // Reset the file input
            document.getElementById('file_name_display').innerText = '';
            document.getElementById('cancel_upload').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <h1>게시글 수정</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-general-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>
    <div class="modify-container">
        <form action="kknock-rewrite-ok.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="board_type" value="<?php echo $board_type; ?>">
            <input type="hidden" name="delete_file" id="delete_file_hidden" value="0">
            <div class="write-content">
                <label for="title">제목</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
            </div>
            <div class="write-content">
                <label for="content">내용</label>
                <textarea name="content" id="content" required><?php echo htmlspecialchars($row['content']); ?></textarea>
            </div>
            <?php if (!empty($row['file_path'])): ?>
            <div class="file-content" id="current_file">
                <button type="button" onclick="confirmDelete()">파일 삭제</button>
                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download><?php echo basename($row['file_path']); ?></a>
            </div>
            <?php endif; ?>
            <div class="file-content">
                <button type="button" onclick="triggerFileUpload()">새 파일 업로드</button>
                <input type="file" name="uploaded_file" id="uploaded_file" onchange="displayFileName()">
                <span id="file_name_display"></span>
            </div>
            <div class="file-content">
                <button type="button" id="cancel_upload" onclick="cancelFileUpload()" style="display: none;">업로드 취소</button>
            </div>
            <div class="modify">
                <button type="submit">수정</button>
            </div>
        </form>
    </div>
</body>
</html>

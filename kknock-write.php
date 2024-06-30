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
    <link href="kknock-header.css" rel="stylesheet">
    <link href="kknock-board.css" rel="stylesheet">
    <style>
        .upload-container {
            text-align: center;
        }
        #title {
            width: 100%;
        }
        #content {
            width: 100%;
            height: 500px;
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
        .modify > input,
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
        <h1>Board</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-general-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>
    <h2>글쓰기</h2>
    <div class="upload-container">
        <form method="post" action="kknock-write-ok.php?board_type=<?php echo htmlspecialchars($board_type); ?>" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($board_type); ?>">
            <p><input id="title" type="text" name="title" placeholder="제목" required></p>
            <hr width="250px" align="left">
            <p><textarea id="content" cols="35" rows="15" name="content" placeholder="내용을 입력하세요." required></textarea></p>
            <div class="file-content">
                <button type="button" onclick="triggerFileUpload()">새 파일 업로드</button>
                <input type="file" name="uploaded_file" id="uploaded_file" onchange="displayFileName()">
                <span id="file_name_display"></span>
            </div>
            <div class="file-content">
                <button type="button" id="cancel_upload" onclick="cancelFileUpload()" style="display: none;">업로드 취소</button>
            </div>
            <div class="modify">
                <input type="submit" value="완료">
            </div>
        </form>
    </div>
</body>
</html>

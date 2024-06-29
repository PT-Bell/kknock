<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Main</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0 auto;
            width: 960px;
            height: 100vh;
            border-left: 1px solid gray;
            border-right: 1px solid gray;
        }
        header {
            width: 100%;
            height: 60px;
            border-bottom: 1px solid gray;
        }
        header > h1 {
            text-align:center;
        }
    </style>
</head>
<body>
    <header>
        <h1>MAIN</h1>
    </header>
    <?php
        if(!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
            echo "<p>로그인을 해 주세요.</p>";
            echo "<p><button onclick=\"window.location.href='kknock-login.php'\">로그인</button> 
            <button onclick=\"window.location.href='kknock-join.php'\">회원가입</button></p>";
        } else {
            $userId = $_SESSION['userId'];
            $userName = $_SESSION['userName'];
            echo "<p>$userName($userId)님 환영합니다.";
            echo "<p><button onclick=\"window.location.href='kknock-logout.php'\">로그아웃</button></p>";
        }
    ?>
    <div>

    </div>
</body>
</html>
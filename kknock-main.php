<!DOCTYPE html>
<?php session_start(); ?>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Main</title>
</head>
<body>
    <h1>MAIN</h1>
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
</body>
</html>
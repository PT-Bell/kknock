<!DOCTYPE html>
<?php session_start(); ?>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h2>로그인</h2>
        <?php 
            if(!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) { ?>
                <form method="post" action="login_ok.php" autocomplete="off">
                    <p>아이디: <input type="text" name="user-id" required></p>
                    <p>비밀번호: <input type="password" name="user-pw" required></p>
                    <p><input type="submit" value="로그인"></p>
                </form>
                <small><a href="kknock-join.php">처음 오셨나요?</a><small>
        <?php 
            } else {
            $userId = $_SESSION['userId'];
            $userName = $_SESSION['userName'];
            echo "<p>$userName($userId)님은 이미 로그인되어 있습니다.";
            echo "<p><button onclick=\"window.location.href='kknock-main.php'\">메인으로</button>
            <button onclick=\"window.location.href='kknock-logout.php'\">로그아웃</button></p>";
        } ?>
    </body>
</html>
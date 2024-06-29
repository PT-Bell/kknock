<?php
    session_start();

    if (!isset($_POST['userId']) || !isset($_POST['userPw'])) {
        header("Content-type: text/html; charset=UTF-8");
        echo "<script>alert('아이디와 비밀번호를 입력해주세요.');";
        echo "window.location.replace('kknock-login.php');</script>";
        exit;
    }

    $userId = $_POST['userId'];
    $userPw = $_POST['userPw'];

    $conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // user_id가 일치하는 사용자 정보를 가져옵니다.
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($res = mysqli_fetch_array($result)) {
        // 비밀번호가 일치하는지 확인합니다.
        if (password_verify($userPw, $res['user_password'])) {
            $_SESSION['userId'] = $res['user_id'];
            $_SESSION['userName'] = $res['username'];
            echo "<script>alert('로그인에 성공했습니다!');";
            echo "window.location.replace('kknock-main.php');</script>";
            exit;
        } else {
            echo "<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');";
            echo "window.location.replace('kknock-login.php');</script>";
            exit;
        }
    } else {
        echo "<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');";
        echo "window.location.replace('kknock-login.php');</script>";
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
<meta http-equiv="refresh" content="0;url=kknock-main.php">

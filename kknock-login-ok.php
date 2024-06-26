<?php
    $userId = $_POST['userId'];
    $userPw = $_POST['userPw'];
    $conn= mysqli_connect('localhost', 'bell', '1207', 'kknock-web');
    $sql = "SELECT * FROM member where loginId='$userId' and loginPw=md5'$userPw'";
    $res = mysqli_fetch_array(mysqli_query($conn,$sql));
    if($res){
        session_start();
        $_SESSION['userId'] = $res['loginId'];
        $_SESSION['userName'] = $res['name'];
        echo "<script>alert('로그인에 성공했습니다!');";
        echo "window.location.replace('kknock-main.php');</script>";
        exit;
    }
    else{
       echo "<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');";
       echo "window.location.replace('kknock-login.php');</script>";
    }
?>
<meta http-equiv="refresh" content="0;url=kknock-main.php">
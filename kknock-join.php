<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join</title>
    <script>
        function checkid() {
            var userid = document.getElementById("uid").value.trim();
            if (userid) {
                // 새 창 열기
                var url = "kknock-check.php?userid=" + encodeURIComponent(userid);
                window.open(url, "chkid", "width=400,height=200");
            } else {
                alert("아이디를 입력하세요.");
            }
        }

        function decide(isAvailable) {
            if (isAvailable) {
                document.getElementById("decide").innerHTML = "<span style='color:blue;'>사용 가능한 아이디입니다.</span>";
                document.getElementById("uid").setAttribute("data-valid", "true");
                document.getElementById("join_button").disabled = false;
            } else {
                document.getElementById("decide").innerHTML = "<span style='color:red;'>이미 사용 중인 아이디입니다.</span>";
                document.getElementById("uid").setAttribute("data-valid", "false");
                document.getElementById("join_button").disabled = true;
            }
        }

        function resetCheck() {
            document.getElementById("decide").innerHTML = "<span style='color:red;'>ID 중복 여부를 확인해주세요.</span>";
            document.getElementById("uid").removeAttribute("data-valid");
            document.getElementById("join_button").disabled = true;
        }
    </script>
</head>
<body>
<h2>회원가입</h2>
<?php if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) { ?>
    <form method="post" action="kknock-join-ok.php" autocomplete="off">
        <p>이름: <input type="text" name="joinName" required></p>

        <p>아이디: <input type="text" name="joinId" id="uid" required onkeyup="resetCheck();"></p>
        <input type="hidden" name="decideId" id="decideId">

        <p><span id="decide" style='color:red;'>ID 중복 여부를 확인해주세요.</span>
            <input type="button" id="check_button" value="ID 중복 검사" onclick="checkid();"></p>

        <p>비밀번호: <input type="password" name="joinPw" required></p>
        <p>비밀번호 확인: <input type="password" name="joinPw2" required></p>
        <p><input type="submit" id="join_button" value="가입하기" disabled></p>
    </form>
    <small><a href="kknock-login.php">이미 회원이신가요?</a></small>
<?php } else {
    $user_id = $_SESSION['userId'];
    $user_name = $_SESSION['userName'];
    echo "<p>$user_name($user_id)님은 이미 로그인되어 있습니다.";
    echo "<p><button onclick=\"window.location.href='kknock-main.php'\">메인으로</button> <button onclick=\"window.location.href='kknock-logout.php'\">로그아웃</button></p>";
} ?>
</body>
</html>

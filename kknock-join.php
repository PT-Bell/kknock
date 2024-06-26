<!DOCTYPE html>
<?php session_start(); ?>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title>Join</title>
        <script>
            //ID 중복 검사 함수
            function checkId(){
                //userid에 DB에서 uid 받아옴
                var userid = document.getElementById("uid").value;
                if(userid) {
                    url = "kknock-check.php?userid=" + userid;
                    window.open(url, "chkid", "width=400,height=200");
                } else {
                    alert("아이디를 입력하세요.");
                }
            }

            function decide(){
                document.getElementById("decide").innerHTML = "<span style='color:red;'>ID 중복 여부를 확인해주세요.</span>";
                document.getElementById("decide-id").value = document.getElementById("uid").value;
                document.getElementById("uid").disabled = true;
                document.getElementById("join-button").disabled = false;
                document.getElementById("check-button").value = "다른 ID로 변경";
                document.getElementById("check-button").setAttribute("onclick", "change()");
            }

            function change() {
                document.getElementById("decide").innerHTML = "";
                document.getElementById("decide-id").value = "";
                document.getElementById("uid").disabled = false;
                document.getElementById("join-button").disabled = true;
                document.getElementById("check-button").value = "ID 중복 검사";
                document.getElementById("check-button").setAttribute("onclick", "checkId()");
            }
        </script>

    </head>
    <body>
    <h2>회원가입</h2>
        <?php if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) { ?>
            <form method="post" action="kknock-join-ok.php" autocomplete="off">
                <!-- 이름 입력 -->
                <p>이름: <input type="text" name="join-name" required></p>
                <!-- ID 입력 -->
                <p>아이디: <input type="text" name="join-id" id="uid" required></p>
                <input type="hidden" name="decide-id" id="decide-id">
                <!-- ID 중복 검사 권유 메세지 -->
                <p><span id="decide" style='color:red;'>ID 중복 여부를 확인해주세요.</span>
                <!-- ID 중복 검사 버튼 -->
                <input type="button" id="check-button" value="ID 중복 검사" onclick="checkId();"></p>
                <!-- PW 입력 -->
                <p>비밀번호: <input type="password" name="joinPw" required></p>
                <!-- PW 확인(재입력) -->
                <p>비밀번호 확인: <input type="password" name="joinPw2" required></p>
                <p><input type="submit" id="join-button" value="가입하기" disabled=true></p>
            </form>
            <small><a href="kknock-login.php">이미 회원이신가요?</a><small>
        <?php } else {
            $userId = $_SESSION['userId'];
            $userName = $_SESSION['userName'];
            echo "<p>$userName($userId)님은 이미 로그인되어 있습니다.</p>";
            echo "<p><button onclick=\"window.location.href='kknock-main.php'\">메인으로</button>";
            echo "<button onclick=\"window.location.href='kknock-logout.php'\">로그아웃</button></p>";
        } ?>
    </body>
</html>

<?php
    $conn= mysqli_connect('localhost', 'choco', '7173', 'study_login');
    $uid= $_GET["userid"];  //GET으로 넘긴 userid
    $sql= "SELECT * FROM member where login_id='$uid'";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));

    if(!$result){
        echo "<span style='color:blue;'>$uid</span> 는 사용 가능한 아이디입니다.";
       ?><p><input type=button value="이 ID 사용" onclick="opener.parent.decide(); window.close();"></p>
        
    <?php
    } else {
        echo "<span style='color:red;'>$uid</span> 는 중복된 아이디입니다.";
        ?><p><input type=button value="다른 ID 사용" onclick="opener.parent.change(); window.close()"></p>
    <?php
    }
?>

<?php
include 'db_connection.php';

if (isset($_GET["userid"])) {
    $uid = $_GET["userid"];

    // 준비된 문을 사용한 안전한 쿼리
    $stmt = $conn->prepare("SELECT * FROM member WHERE loginId = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<span style='color:blue;'>$uid</span> 는 사용 가능한 아이디입니다.";
        echo '<p><input type="button" value="이 ID 사용" onclick="opener.parent.decide(); window.close();"></p>';
    } else {
        echo "<span style='color:red;'>$uid</span> 는 중복된 아이디입니다.";
        echo '<p><input type="button" value="다른 ID 사용" onclick="opener.parent.change(); window.close();"></p>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('잘못된 접근입니다.'); window.close();</script>";
}
?>

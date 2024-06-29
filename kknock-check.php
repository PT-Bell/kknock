<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$uid = mysqli_real_escape_string($conn, $_GET["userid"]);

$sql = "SELECT * FROM users where user_id='$uid'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>window.opener.decide(true); window.close();</script>";
} else {
    echo "<script>window.opener.decide(false); window.close();</script>";
}

mysqli_close($conn);
?>

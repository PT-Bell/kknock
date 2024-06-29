<?php
header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT username, user_id FROM users";
$result = mysqli_query($conn, $sql);

$users = array();
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);

mysqli_close($conn);
?>

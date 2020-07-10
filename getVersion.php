<?php
$connect=mysqli_connect("localhost", "id", "pw", "db") or
    die("SQL server에 연결할 수 없습니다.");
session_start();
mysqli_query($connect, "set session character_set_client=utf8");
mysqli_query($connect, "set session character_set_connection=utf8");
mysqli_query($connect, "set session character_set_results=utf8");
mysqli_set_charset($connect, "utf8");
$sql = "select * from version";
$res = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($res);
echo json_encode($row);
 mysqli_close($connect);

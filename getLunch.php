<?php
    $connect=mysqli_connect("localhost", "id", "pw", "db") or
        die("SQL server에 연결할 수 없습니다.");
   session_start();
   mysqli_query("set session character_set_client=utf8");
   mysqli_query("set session character_set_connection=utf8");
   mysqli_query("set session character_set_results=utf8");
   mysqli_set_charset($connect, "utf8");
   $sql = "select * from Lunch";
   $res = mysqli_query($connect, $sql);
   $row = mysqli_fetch_array($res);
   echo json_encode($row);
   $rp = mysqli_query($connect, 'SELECT * FROM `count`');
   $vl = mysqli_fetch_array($rp);
   mysqli_query($connect, 'UPDATE count SET request='.($vl[0]+1));
   mysqli_close($connect);

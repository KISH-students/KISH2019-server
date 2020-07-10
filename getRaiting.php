<?php
if (! $_GET["date"] or ! $_GET["id"]) {
    die("404");
}
$connect=mysqli_connect("localhost", "id", "pw", "db") or
    die("500");
    session_start();
 mysqli_set_charset($connect, "utf8");
 $sql = "SELECT * FROM LunchRating where date='".$_GET["date"]."'";
 $res = mysqli_query($connect, $sql);
 $row = mysqli_fetch_array($res);
if (mysqli_error($connect)) {
    die("500");
}
 if ($row == null) {
     die("n,0,0");
 }
 $mod = n;
 if (in_array($_GET["id"], json_decode($row["gd_USERS"]))) {
     $mod = 't';
 }
 if (in_array($_GET["id"], json_decode($row["bd_USERS"]))) {
     $mod = 'f';
 }
  echo $mod.",".count(json_decode($row["gd_USERS"])).",".count(json_decode($row["bd_USERS"]));

<?php
if (! $_GET["date"] or ! $_GET["is_good"] or ! $_GET["id"]) {
    die("404");
}
$connect=mysqli_connect("localhost", "id", "pw", "db") or
    die("500");
    session_start();
 mysqli_set_charset($connect, "utf8");
 $sql = "SELECT * FROM LunchRating where date='".$_GET["date"]."'";
 $res = mysqli_query($connect, $sql);
 $row = mysqli_fetch_array($res);
 if ($row == null) {
     mysqli_query($connect, 'INSERT INTO LunchRating (date, nice, bad, gd_USERS, bd_USERS) VALUES ("'.$_GET["date"].'", 0, 0, "[]", "[]")');
 }
if (mysqli_error($connect)) {
    die(mysqli_error($connect));
}
$sql = "SELECT * FROM LunchRating where date='".$_GET["date"]."'";
$res = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($res);
 if ($row == null) {
     die("502");
 }
 $json_gd = json_decode($row["gd_USERS"]);
 $json_bd = json_decode($row["bd_USERS"]);
 $id = $_GET["id"];
 if ($_GET["is_good"] == "true") {
     if (in_array($_GET["id"], $json_bd)) {
         if (in_array($_GET["id"], $json_gd)) {
             if (($key = array_search($id, $json_bd)) !== false) {
                 unset($json_bd[$key]);
             }
         } else {
             if (($key = array_search($id, $json_bd)) !== false) {
                 unset($json_bd[$key]);
             }
             array_push($json_gd, $id);
         }
     } else {
         if (in_array($id, $json_gd)) {
             die(count($json_gd).",".count($json_bd));
         }
         array_push($json_gd, $id);
     }
     mysqli_query($connect, "UPDATE LunchRating SET 	gd_USERS='".json_encode($json_gd)."' , bd_USERS='".json_encode($json_bd)."' WHERE date='".$_GET["date"]."'");
     if (mysqli_error($connect)) {
         die("505");
     }
     echo count($json_gd).",".count($json_bd);
 } elseif ($_GET["is_good"] == "false") {
     if (in_array($_GET["id"], $json_gd)) {
         if (in_array($_GET["id"], $json_bd)) {
             if (($key = array_search($id, $json_gd)) !== false) {
                 unset($json_gd[$key]);
             }
         } else {
             if (($key = array_search($id, $json_gd)) !== false) {
                 unset($json_gd[$key]);
             }
             array_push($json_bd, $id);
         }
     } else {
         if (in_array($id, $json_bd)) {
             die(count($json_gd).",".count($json_bd));
         }
         array_push($json_bd, $id);
     }
     mysqli_query($connect, "UPDATE LunchRating SET 	gd_USERS='".json_encode($json_gd)."' , bd_USERS='".json_encode($json_bd)."' WHERE date='".$_GET["date"]."'");
     if (mysqli_error($connect)) {
         die("506");
     }
     echo count($json_gd).",".count($json_bd);
 } else {
     die("503");
 }

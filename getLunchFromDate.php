<?php
$connect=mysqli_connect("localhost", "id", "pw", "db") or
    die("SQL server에 연결할 수 없습니다.");
session_start();
mysqli_set_charset($connect, "utf8");
$sql = "select * from Lunch";
$res = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($res);
if (((int)$_GET["m"] != (int)date("m") or (int)$_GET["y"] != (int)date("Y")) and ($_GET["m"] and $_GET["y"])) {
} else {
    if ($row["0"] < (time()-1800) || $row["date"] != date("d")) {
        $url = 'http://hanoischool.net/default.asp?menu_no=47&ChangeDate='.$_GET["date"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $output =  preg_replace("(\<(/?[^\>]+)\>)", "", $output);

        $output = iconv("EUC-KR", "UTF-8", $output);
        $output = explode("날짜별 식단안내", $output);
        $output = explode("개인정보처리방침", $output[1]);

        $num = 1;
        $day = array();
        $output = ".. ".$output[0];
        while ($num < 32) {
            $str= "".$num."일";
            if (strpos($output, $str) !==false) {
                $tmp = explode($str, $output);
                $tmp = $tmp[1];
                if (strpos($output, "".($num+1)."일") !==false) {
                    $tmp = explode("".($num+1)."일", $tmp);
                    $tmp = $tmp[0];
                }

                $tmp = str_replace("\r\n\t\t", "", $tmp);
                $tmp = str_replace("\t", "", $tmp);
                $tmp = str_replace("\r\n", "\n", $tmp);
                $tmp = str_replace(", ", "\n", $tmp);
                $tmp = str_replace("염도", "\n염도:", $tmp);
                $tmp = str_replace("중식", "", $tmp);
                $tmp = trim($tmp);

                if (trim(iconv_substr($tmp, 3)) == "") {
                    $tmp = $tmp."\n\n급식정보가 없습니다.";
                }
                $tmp = $str.$tmp;
                $tmp2 = array();
                $tmp2[] = date("Y")."/".(int)date("m")."/".$num;
                $tmp2[] = $tmp;
                array_push($day, $tmp2);
            } else {
                break;
            }
            $num ++;
        }
        $rst = json_encode($day);
        echo $rst;
        $sql = "SELECT * FROM Lunch where date=".date("Y").date("m");
        $res = mysqli_query($connect, $sql);
        $row = mysqli_fetch_array($res);
        if ($row == null) {
            mysqli_query($connect, 'INSERT INTO Lunch (`0` , `JSON`, `date`) VALUES (1, "[]", "'.date("Y").date("m").'")');
        }
        if (mysqli_error($connect)) {
            die("501");
        }
        mysqli_query($connect, "UPDATE `Lunch` SET `JSON` = '".addslashes($rst)."' where date='".date("Y").date("m")."'");
        mysqli_query($connect, "UPDATE `Lunch` SET `0` = '".time()."' where date='".date("Y").date("m")."'");
        mysqli_query($connect, "set session character_set_client=utf8");
        mysqli_query($connect, "set session character_set_connection=utf8");
        mysqli_query($connect, "set session character_set_results=utf8");
        mysqli_set_charset($connect, "utf8");
        $rp = mysqli_query($connect, 'SELECT * FROM `count`');
        $vl = mysqli_fetch_array($rp);
        mysqli_query($connect, 'UPDATE count SET request='.($vl[0]+1));
        mysqli_close($connect);
        curl_close($ch);
        return $output;
    } else {
        echo $row["JSON"];
        $rp = mysqli_query($connect, 'SELECT * FROM `count`');
        $vl = mysqli_fetch_array($rp);
        mysqli_query($connect, 'UPDATE count SET request='.($vl[0]+1));
        mysqli_close($connect);
    }
}

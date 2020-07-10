<?php
$connect = mysqli_connect( "localhost", "id", "pw", "db") or
    die( "SQL server에 연결할 수 없습니다.");
session_start();
mysqli_set_charset($connect, "utf8");
$sql = "select * from calender";
$res = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($res);
$DefURL = 'http://hanoischool.net/?menu_no=41&ChangeDate';
if ($_GET["date"] == "all") {
  if($row["all_update"] != date("d")){
    $arr = array();
    for ($month = 1; $month < 13; $month ++) {
        $arr[$month.''] =  load($row, $connect, $DefURL.'=2019-'.$month.'-1',$month );
    }
    echo json_encode($arr);
    mysqli_query($connect , "UPDATE `calender` SET `all_date` = '".date("Y").'/'.(int)date("m")."'");
    mysqli_query($connect , "UPDATE `calender` SET `all_update` = '".date("d")."'");
  }else echo json_encode($row);
} else {
    echo load($row, $connect, $DefURL, (int)date("m"), true);
}
function load($row, $connect, $url, $month_1, $is_single = false)
{
  if($is_single){
    if($row["single_update"] == date("d")){
      $arr = array();
      array_push($arr , [$row[$month_1], $month_1]);
      return json_encode($arr);
    }
  }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $output = iconv('EUC-KR', 'UTF-8', $output);
    $month = $output;
    $month = explode('<div class="h_category">', $month);
    $month = explode('<td class="h_bar">', $month[1], 2);
    $month = explode('<span class="design_font">', $month[1]);
    $month = explode('</span', $month[1]);
    $month = $month[0];
    $month = explode('월', $month);
    $month = trim($month[0]);
    if (mb_strlen($month, 'utf-8') > 10) {
        $month = "";
    }
    $output = explode('<div class="defTable2">', $output);
    $output = explode('<div class="h_btn_area">', $output[1]);
    $output = explode('<tr height', $output[0]);
    $arr = array();
    array_shift($output);
    foreach ($output as  $value) {
        $tmp = explode('<td valign', $value);
        foreach ($tmp as $val) {
            if (strpos($val, '<div style') !==false) {
                $temp = $val;
                $k = $temp;
                $k = explode('<strong class="line">', $k);
                $k = explode('</strong', $k[1]);
                $temp = explode('<div style', $temp);
                $temp = explode('>', $temp[1]);
                $temp = explode('</div', $temp[1]);
                array_push($arr, $k[0].'일 '.$temp[0]);
            }
        }
    }
    array_unshift($arr, $month);
    if($is_single){
      mysqli_query($connect , "UPDATE `calender` SET `single_update` = '".date("d")."'");
      mysqli_query($connect , "UPDATE `calender` SET `single_date` = '".date("Y").'/'.(int)date("m")."'");
    }
    $final = ($is_single) ? json_encode($arr) : $arr;
    mysqli_query($connect , "UPDATE `calender` SET `".$month_1."` = '".addslashes(json_encode($arr))."'");
    return $final;
}

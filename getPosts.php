<?php
$url = 'http://hanoischool.net/default.asp?board_mode=list&menu_no=38&page='.$_GET["page"];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);

$output = iconv("EUC-KR", "UTF-8", $output);
$output = explode('<!-- 게시판 목록 -->', $output);
$output = explode('<!-- 관리자 로그인정보 노출 -->', $output[1]);
$output = explode('<tr class="h_line_dot">', $output[0]);
$container = [];
foreach ($output as $key => $value) {
    $value = explode('</tr>', $value);
    $value = explode('<td>', $value[0]);
    $temp = [];
    foreach ($value as $k => $vl) {
        /*
        0 : id
        1 : title
        2 : writer
        3 : date
        4 : view
        5 : has attachment ( bool )
        */
        $vl = explode('</td>', $vl);
        $vl = trim($vl[0]);
        switch ($k) {
      case 0:
        $temp['id'] = $vl;
        break;
      case 1:
      $vl = explode('class="no1">', $vl);
      $vl = explode('</a>', $vl[1]);
      $temp['title'] = trim($vl[0]);
      break;
      case 2:
        $temp['writer'] = trim($vl);
        break;
      case 3:
        $temp['date'] = trim($vl);
        break;
      case 4:
        $temp['view'] = trim($vl);
        break;
      case 5:
        $temp['attachment'] = trim($vl) == '-' ? false : true;
        break;
      default:

        break;
    }
    }
    $container[] = $temp;
}

echo json_encode($container);

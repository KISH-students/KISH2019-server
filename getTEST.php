<?php
if ($_GET["HIGH"] != null) {
    $TESTs=[1571788800,1571875200,1571961600,1576627200,1576713600,1576800000,"n"];
    $ex_TEST = array();
    echo getDDay($TESTs, $ex_TEST);
} else {
    $TESTs=[1571788800,1571875200,1571961600,1576627200,1576713600,1576800000,"n"];
    $ex_TEST = array();
    $ex_TEST[2] = "중등이상";
    $ex_TEST[5] = "중등이상";
    echo getDDay($TESTs, $ex_TEST);
}

function getDDay(array $TESTs, array $ex_TEST)
{
    $d = date("d");
    $m = date("m");
    $y = date("Y");
    $TEST = "불러올 수 없음";
    $tt2 = "n";
    $timestamp = 0;
    foreach ($TESTs as $key => $vl) {
        if ($vl == 'n') {
            $TEST = "정보 없음";
            break;
        }
        if (date("Ymd", $vl) == date("Ymd")) {
            $TEST = "D-Day";
            if (isset($ex_TEST[$key])) {
                $tt2 = $ex_TEST[$key];
            }
            $timestamp = $vl;
            break;
        }
        if ($vl > time()) {
            $str = "D";
            if ((time()-$vl) > 0) {
                $str = "D+";
            }
            $TEST = $str.(intval((time()-$vl) / 86400) - 1);
            //$TEST = "D-".date("d",($vl - time()));
            if (isset($ex_TEST[$key])) {
                $tt2 = $ex_TEST[$key];
            }
            $timestamp = $vl;
            break;
        }
    }
    if ($TEST == "정보 없음") {
        $tt2 = "곧 새로운 디자인으로 업데이트 됩니다.\n관련 정보는 '업데이트 가능'을 터치하여 확인해주세요. ";
    }
    if ($timestamp != 0) {
        $str = ($TEST.",".date("Y/m/d", $timestamp).",".$tt2.","."준비중");
    } else {
        $str = ($TEST.","."정보 없음".",".$tt2.","."준비중");
    }
    return $str;
}

<?php
error_reporting(E_ERROR | E_PARSE);

function l($val){
    echo $val;
}
function p($val) {
    echo "<p>".htmlentities($val,ENT_QUOTES)."</p>";
}

function span($val) {
    echo "<span>".htmlentities($val,ENT_QUOTES)."</span>";
}

function h($h_num, $val) {  
    $h_tag_start = "<h".$h_num.">";
    $h_tag_end = "</h".$h_num.">";
    echo $h_tag_start.htmlentities($val,ENT_QUOTES).$h_tag_end;
}

function h1($val) {
    h(1, $val);
}

function h2($val) {
    h(2, $val);
}

function h3($val) {
    h(3, $val);
}

function h4($val) {
    h(4, $val);
}

function h5($val) {
    h(5, $val);
}

function h6($val) {
    h(6, $val);
}

function concatArrayVals($arr) {
    $ans = "";
    for($i = 0; $i<count($arr); $i++) {
        if($i == (count($arr) -1)) {
            $ans = $ans.$arr[$i];
        }else {
            $ans = $ans.$arr[$i].", ";
        }
    }
    return $ans;
}

function getDummyDBParams($param_nums) {
    $ans = "";
    for($i = 0; $i<$param_nums; $i++){
        if($i == ($param_nums -1)) {
            $ans = $ans.":p".$i;
        }else{
            $ans = $ans.":p".$i.", ";
        }
    }
    return $ans;
}

/*
Unit Test Secition
*/
$unit_testing = 0;
if($unit_testing == 1) {
    $arr = array("june", "july", "august");
    p(concatArrayVals($arr));
    
    p(getDummyDBParams(5));
}


?>
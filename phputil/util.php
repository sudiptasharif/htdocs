<?php

function a($href = "", $title = "") {
    $msg = "<a href=$href>$title</a>";
    return $msg;
}

function p($msg = "") {
    $msg = "<p>$msg</p>";
    return $msg;
}

function nbsp($msg = "", $nbspCount = 1) {
    $nbsp = "";
    for ($i = 0; $i < $nbspCount; $i++) {
        $nbsp = $nbsp . "&nbsp;";
    }
    return $msg . $nbsp;
}

function h($num = 1, $val = "") {
    $h = "<h$num>$val</h$num>";
    return $h;
}


function l($msg = "") {
    echo $msg;
}

function ln($msg = "") {
    echo $msg . "<br>";
}

function d($msg="", $vardump=false) {
    // use this function for debugging
    // use vardump = true for catching FALSE
    l("<pre>");
    if ($vardump === true) {
        var_dump($msg);
    } else {
        print_r($msg);
    }
    l("</pre>");
}

function ul($lists=array()) {
    $lTags = '';
    for($idx = 0; $idx < count($lists); $idx++) {
        $lTags = $lTags . "<li>$lists[$idx]</li>";
    }
    $ulTag = "<ul>$lTags</ul>";
    return $ulTag;
}

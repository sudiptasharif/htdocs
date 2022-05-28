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

function printNavBar() {
    l(nbsp(a("index.php", "Home"), 2));
    l(nbsp(a("test.php", "Test"), 2));
    l(nbsp(a("exp.php", "Expressions"), 2));
    l(nbsp(a("constrc.php", "Control Structures"), 2));
}

function l($msg = "") {
    echo $msg;
}

function ln($msg = "") {
    echo $msg . "<br>";
}

printNavBar();

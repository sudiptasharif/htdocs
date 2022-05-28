<?php
include_once 'global.php';
include_once 'db_oracle.php';

// $usernum = 12;
// if ($usernum>10) {
//   trigger_error("Number cannot be larger than 10");
// }

$arr = array("june", "july", "august");
p(concatArrayVals($arr));

p(getDummyDBParams(5));

?>

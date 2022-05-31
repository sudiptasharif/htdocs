<?php 
require_once '../phputil/util.php';
$invalidcolor = 'color:red';
$infocolor = 'color:green';
$boolmsgcolor = 'color:blue';

$bool = 'bool';
$boolval = '';
$boolmsg = '';
if (isset($_POST[$bool])) {
    $boolval = htmlentities($_POST[$bool]);
    if (!filter_var($boolval, FILTER_VALIDATE_BOOLEAN)) {
        $boolmsg = "Invalid input";
        $boolmsgcolor = $invalidcolor;
    } else {
        $boolmsg = "Valid input"; 
    }
}

$bool2 = 'bool2';
$boolval2 = '';
$boolmsg2 = '';
$boolmsgcolor2 = '';
if (isset($_POST[$bool2])) {
    $boolval2 = htmlentities($_POST[$bool2]);
    if (filter_var($boolval2, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true) {
        $boolmsg2 = "true is returned";
    } else if (filter_var($boolval2, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false) {
        $boolmsg2 = "false is returned"; 
    } else {
        $boolmsg2 = "null is returned";
    }
    $boolmsgcolor2 = $infocolor;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Practice filter_var()</title>
    </head>
    <body>
        <h1>Practice filter_var()</h1>
        <p><a href="https://www.php.net/manual/en/function.filter-var.php" target="_blank">filter_var()</a></p>
        <p><a href="https://www.php.net/manual/en/filter.filters.php" target="_blank">filters</a></p>
        <p>Below I am playing around with the various filters of filter var, read the docs to learn more and practice</p>
        <h3>Validate filters</h3>
        <form method="post">
            <p>
                <label for="bool">filter_var($input, FILTER_VALIDATE_BOOLEAN)</label>
                <input type="text" id="bool" name="bool" value="<?= $boolval?>"/>
                <span style="<?=$boolmsgcolor?>"><?=$boolmsg?></span>
            </p>
            <p>
                <label for="bool2">filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)</label>
                <input type="text" id="bool2" name="bool2" value="<?= $boolval2?>"/>
                <span style="<?=$boolmsgcolor2?>"><?=$boolmsg2?></span>
            </p>            
            <input type="submit"/>
            <input type="reset"/>
            <input type="button" onclick="location.href = 'index.php'; return false;" value="Escape"/>
            <?php ln(a("filter.php", 'Reset this page')); ?>            
        </form>
        <?php
        ln();
        l('$_POST = ');
        d($_POST);
        ?>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="get.php">GET Form</a></li>
            <li><a href="post.php">POST Form</a></li>
            <li><a href="mvc.php">MVC</a></li>
            <li><a href="filter.php">Practice filter_var()</a></li>
        </ul>          
    </body>
</html>


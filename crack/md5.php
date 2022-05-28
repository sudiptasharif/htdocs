<?php
$encode = "";
$encodeparam = 'encode';
$colorred = "color:red";
$output = "";
$errormsg = 'Input must be exactly four numeric characters';

if (isset($_GET[$encodeparam])) {
    $encode = $_GET[$encodeparam];
    $md5Hash = md5($encode);
    $output = "<p>MD5: $md5Hash</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sudipta Sharif MD5</title>
    </head>
    <body>
        <h1>MD5 Maker</h1>
        <?= $output ?>
        <form>
            <input type="text" name="encode" value='<?= $encode ?>'/>
            <input type="submit" value="Compute MD5"/>
        </form>
        <ul>
            <li><a href="md5.php">Reset</a></li>
            <li><a href="index.php">Back to Cracking</a></li>
        </ul>

    </body>
</html>


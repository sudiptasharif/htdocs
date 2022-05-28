<?php 
$pin = "";
$pinparam = 'pin';
$colorred = "color:red";
$output = "";
$errormsg = 'Input must be exactly four numeric characters';

if (isset($_GET[$pinparam])) {
    $pin = trim($_GET[$pinparam]);
    if (!is_numeric($pin) || strlen($pin) !== 4) {
        $output = "<p style=$colorred>$errormsg</p>";
    } else {
      $md5Hash = md5($pin);  
      $output = "<p>MD5 value: $md5Hash</p>";  
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sudipta Sharif Make PIN</title>
    </head>
    <body>
        <h1>MD5 PIN Maker</h1>
        <?=$output?>
        <form>
            <input type="text" name="pin" value='<?=$pin?>'/>
            <input type="submit" value="Compute MD5 for PIN"/>
        </form>
        <ul>
            <li><a href="makepin.php">Reset this page</a></li>
            <li><a href="index.php">Back to Cracking</a></li>
        </ul>

    </body>
</html>


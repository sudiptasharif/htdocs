<?php include_once '../phpUtil/util.php';?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP Functions</title>
    </head>
    <body>
        <?php
        l(h(1, "PHP Functions"));
        l(h(2, "Pass by Reference"));
        $msg = "Hello World";
        $test = &$msg;
        ln('$msg = '."$msg");
        ln('$test = '."$test");
        
        $test = "This is Sudipta Sharif";
        ln('$msg = '."$msg");
        ln('$test = '."$test");
        ?>
    </body>
</html>

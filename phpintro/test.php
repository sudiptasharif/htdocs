<?php include_once 'util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Test</title>
    </head>
    <body>

        <h2>$x % 2</h2>
        <p>$x = 123</p>
        <?php
        $x = 123;
        $ans = $x % 2;
        ln('$x % 2 = ' . $ans);

        if ($x % 2) {
            ln("true");
        } else {
            ln("false");
        }
        ?>

        <h2>$x % 2</h2>
        <p>$x = 120</p>
        <?php
        $x = 120;
        $ans = $x % 2;
        ln('$x % 2 = ' . $ans);

        if ($x % 2) {
            ln("true");
        } else {
            ln("false");
        }
        ?>

        <?php
        l(h(2, "True/False"));
        ln(TRUE);
        ln(FALSE); // this prints nothing to the screen

        $flag = TRUE;
        if ($flag) {
            ln("true");
        } else {
            ln("false");
        }

        $flag = 1;
        if ($flag) {
            ln("true");
        } else {
            ln("false");
        }

        $flag = FALSE;
        if ($flag) {
            ln("true");
        } else {
            ln("false");
        }

        $flag = 0;
        if ($flag) {
            ln("true");
        } else {
            ln("false");
        }
        ?>
    </body>
</html>


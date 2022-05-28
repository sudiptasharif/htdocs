<?php include_once 'util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Home</title>
    </head>
    <body>
        <h1>Hello from my first web page</h1>
        <?php
        $abc = 12;
        $total = 0;
        $largest_so_far = 0;

        $abc = 12;
        $php2 = 0;
        $bad_punc = 0;

        echo $abc . "<br>";
        // echo "<br>";

        $x = 2;
        $y = $x + 5;
        echo $y . "<br>";

        // $y = array("x" => "Hello");
        // echo $y[x];

        echo "this is a simple string" . "<br>"; // note in the browser you can't get this newline, you will have to use the <br> tag

        echo "you can also have embedded newlines in <br> strings this way as it is okay to do <br>"; // same as  above, but in the terminal if you run this script you will get the new lines

        $expand = 12;
        echo "variables do $expand (expand)<br>";

        echo 'this is also a simple string <br>';

        echo 'Arnold once said: "I\'ll be back" <br>';

        $either = 50;

        echo 'Variables do not $expand $either <br>';

        echo "hello world! ", "i love you Allah, please forgive me <br>";

        $x = "sdgfs" + 27; // warning but not an error!

        echo $x;
        ?>
    </body>
</html>




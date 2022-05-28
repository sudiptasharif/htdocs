<?php include_once 'util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Control Structure</title>
    </head>
    <body>
        <?php
        l(h(1, "Control Structures"));
        l(h(2, "Conditional-if"));
        $ans = 42;
        if ($ans === 42) {
            ln("Hello world!");
        } else {
            ln("Wrong answer");
        }

        l(h(2, "Multi-way"));
        $x = 1;
        if ($x < 2) {
            ln("small");
        } elseif ($x < 10) {
            ln("medium");
        } else {
            ln("large");
        }
        ln("all done");

        l(h(2, '"zero-trip" while loop'));
        $fuel = 10;
        while ($fuel > 1) {
            ln("vrom vrom");
            $fuel = $fuel - 1;
        }

        l(h(2, '"one-trip" do-while loop'));
        $count = 1;
        do {
            ln("$count * 5 = " . ($count * 5));
        } while (++$count <= 10);

        l(h(2, 'for loop (aka counted loop)'));
        for ($count = 1; $count <= 10; $count++) {
            ln("$count * 6 = " . ($count * 6));
        }

        l(h(2, "Breaking out of loop"));
        for ($count = 1; $count <= 600; $count++) {
            if ($count == 5) {
                break;
            }
            ln("Count: $count");
        }
        ln("Done");
        
        l(h(2, "Finishing an Iteration with continue"));
        for ($count = 1; $count <= 10; $count++) {
            if (($count % 2) == 0) {
                continue;
            }
            ln("Count: $count");
        }
        ln("Done");
        ?>
    </body>
</html>


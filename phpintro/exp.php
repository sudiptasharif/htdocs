<?php include_once 'util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Expressions</title>
    </head>
    <body>
        <h1>Expressions</h1>

        <h2>Aggressive Implicit Type Conversion</h2>
        <?php
        $x = "15" + 27;
        ln($x);
        ?>

        <h2>Increment/Decrement</h2>
        <?php
        $x = 12;
// $y = 15 + $x++; // we should avoid this, and instead have the following
        $y = 15 + $x;
        $x = $x + 1;
        echo "x is $x and y is $y" . "<br>";
        ?>

        <h2>Ternary</h2>
        <?php
        $www = 123;

        $msg = $www > 100 ? "Large" : "Small";
        echo "First: $msg" . "<br>";

        $msg = ($www % 2 == 0) ? "Even" : "Odd";
        echo "Second: $msg" . "<br>";

        $msg = ($www % 2) ? "Odd" : "Even";
        echo "Third: $msg" . "<br>";
        ?>

        <?php
        l(h(2, "Side-Effect Assignment (use sparingly)"));
        $out = "Hello";
        $out = $out . " ";
        $out .= "World";
        ln($out);

        $count = 0;
        $count += 1;
        ln("Count: $count");

        l(h(2, "Casting"));

        $a = 56;
        $b = 12;
        $c = $a / $b;
        ln("a = $a");
        ln("b = $b");
        ln('c = a / b');
        ln(nbsp("C: $c") . "(division forces operands to be floating point)");

        $d = "100" + 36.25 + TRUE;
        ln('$d = "100" + 36.25 + TRUE => 137.25' . " (this is something freaky)");
        ln("D: $d");
        ln("TRUE gets converted/cast to 1");
        ln('"100" gets converted/cast to 100');
        ln("I guess the conversion of TRUE to 1 and the addition are the freaky bits");

        $e = (int) 9.9 - 1;
        ln('$e = (int) 9.9 - 1');
        ln("E: $e");

        $f = "sam" + 25;
        ln('$f = "sam" + 25 (this throws a warning but it\'s not an error!)');
        ln("F: $f");
        ln('"sam" gets converted/cast to 0');

        $g = "sam" . 25;
        ln('$g = "sam" . 25 (this does not throw an error, which is ok, Java does the same)');
        ln("G: $g");
        ln('25 gets converted/cast to "25" (string)');

        l(h(2, "TRUE/FALSE concatenation operator (.) casting"));
        ln("The concatenation operator tries to convert its operands to strings. TRUE becomes an integer 1 and then becomes a string.  FALSE is “not there” -  it is even “smaller” than zero, at least when it comes to width.");
        ln();
        ln('"A".FALSE."B"');
        ln("A" . FALSE . "B");
        ln();
        ln('"X".TRUE."Y"');
        ln("X" . TRUE . "Y");

        l(h(2, "Equality vs Identity"));
        l(p("The equality operator (==) in PHP is far more aggressive than in most other languages when it comes to data conversion during expression evaluation."));

        ln('if ( 123 == "123" ) print ("Equality 1\n");');
        if (123 == "123") {
            ln("Equlity 1");
        }
        ln('"123" gets converted/cast to 123');
        ln("'==' operator does this conversion before checking if the values on either side is equal or not");

        l(p());

        ln('if ( 123 == "100"+23 ) print ("Equality 2\n");');
        if (123 == "100" + 23) {
            ln("Equality 2");
        }
        ln('"100" gets converted to 100 and then added to 23 which is = 123');
        ln("'==' operator after doing the above conversion checks for equality");

        l(p());
        ln('if ( FALSE == "0" ) print ("Equality 3\n");');
        if (FALSE == "0") {
            ln("Equality 3");
        }
        ln("'==' operator converts \"0\" to 0, and the FALSE to 0 before checking for equality");

        l(p());
        ln('if ( (5 < 6) == "2"-"1" ) print ("Equality 4\n");');
        if ((5 < 6) == "2" - "1") {
            ln("Equality 4");
        }
        ln("subhanAllah!");
        ln('"==" operator converts the (5<6) to TRUE which is then converted to 1');
        ln('"==" operator also converts the "2"-"1" to "1" to 1, and then checks for equality');

        l(p());
        ln('if ( (5 < 6) === TRUE ) print ("Equality 5\n");');
        if ((5 < 6) === TRUE) {
            ln("Equality 5");
        }
        ln('(5 < 6) gets converted/cast to TRUE');
        ln('"===" operator checks if both the value and data-type are equal');
        l(p("In other programing languages like Java, since we declare the data type a single '==' operator is enough to check if the values are equal"));
        l(p('In PHP there is no data type declaration as such to figure out if both data-type and value is equal or not "===" is used. and to just check if value are same or not "==" is used.'));

        l(h(2, "var_dump"));
        $x = FALSE;
        ln(var_dump($x));

        $x = "FALSE";
        ln(var_dump($x));

        $x = "FALSE";
        ln(var_dump($x));

        l(h(2, "strpos"));
        ln('$vv = "Hello World!";');
        $vv = "Hello World!";
        ln('echo "First:" . strpos($vv, "Wo") . "\n";');
        ln("First: " . strpos($vv, "Wo"));
        ln('echo "Second: " . strpos($vv, "He") . "\n";');
        ln("Second: " . strpos($vv, "He"));
        ln('echo "Third: " . strpos($vv, "ZZ") . "\n";');
        ln("Third: " . strpos($vv, "ZZ"));
        ln("Notice in the above FALSE is detectable but not visible. var_dump reveals it.");
        ln(var_dump(strpos($vv, "ZZ")));
        
        ln('if (strpos($vv, "He") == FALSE ) echo "Wrong A\n";');
        if (strpos($vv, "He") == FALSE) {
            ln("Wrong");
        }
        ln("In the above, strpos is returning the correct value which is 0. Since 0 get's cast to FALSE, the we end up entering the if statement");
        
        l(p("Moral of the story: don't use strpos and == for checking equality, use === and check identity"));
        l(p("Also: <em>Beware</em> of FALSE variables. They are <em>detectable</em> but <em>not visible</em>..."));
        ?>
    </body>
</html>


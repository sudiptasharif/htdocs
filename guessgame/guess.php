<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Guessing Game for Sudipta Sharif</title>
    </head>
    <body>
        <h1>Welcome to my guessing game</h1>
        <?php
        $correct_number = 69;
        play_game($correct_number);
     
        function l($msg = "") {
            echo $msg;
        }

        function p($msg = "") {
            $p = "<p>$msg</p>";
            return $p;
        }

        function play_game($correct_number) {
            if (!isset($_GET['guess'])) {
                l(p("Missing guess parameter"));
            }elseif (strlen($_GET['guess']) < 1) {
                l(p("Your guess is too short"));
            }elseif (!is_numeric($_GET['guess'])) {
                l(p("Your guess is not a number"));
            }elseif ($_GET['guess'] > $correct_number) {
                l(p("Your guess is too high"));
            } elseif ($_GET['guess'] < $correct_number) {
                l(p("Your guess is too low"));
            } else {
                l(p("Congratulations - You are right"));
            }
        }
        ?>
    </body>
</html>

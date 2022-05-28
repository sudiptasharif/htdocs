<?php
require_once '../phpUtil/util.php';
$md5param = 'md5';
$crackparam = 'crackmethod';
$debug = "Debug Output:";
$pin = "Not found";
$md5list = array();
$hashprintlimit = 15;
$md5input = "";
$bfchecked = true;
$DIGITLIMIT = 4;

if (isset($_GET[$md5param]) && strlen($_GET[$md5param]) === $DIGITLIMIT) {
    $md5input = $_GET[$md5param];
    $crackmethod = $_GET[$crackparam];
    if (strcasecmp($crackmethod, "bruteforce") == 0) {
        $starttime = microtime(true);
        $pin = crackByBruteForce($md5input, $md5list);
        $endtime = microtime(true);
        $bfchecked = true;
    } else {
        $starttime = microtime(true);
        $pin = crackByGuesses($md5input, $md5list);
        $endtime = microtime(true);
        $bfchecked = false;
    }
    $checkedpins = getReversedMd5Hash($md5list, $hashprintlimit);
    $debug = $debug . "<br>" . $checkedpins;
    $debug = $debug . "Total checks: " . count($md5list) . "<br>";
    $debug = $debug . "Ellapsed time: " . ($endtime - $starttime) . " seconds";
}

function crackByGuesses($md5Input, &$md5list) {
    while (count($md5list) !== 10000) {
        $thousandsUnit = rand(0, 9);
        $houndredsUnit = rand(0, 9);
        $tensUnit = rand(0, 9);
        $units = rand(0, 9);
        $guessPin = (string) $thousandsUnit . (string) $houndredsUnit
                . (string) $tensUnit . (string) $units;
        $md5Guess = md5($guessPin);
        $md5list[$guessPin] = $md5Guess;
        if (strcmp($md5Guess, $md5Input) == 0) {
            return $guessPin;
        }
    }
    return "Not found";
}

function crackByBruteForce($md5Input, &$md5list) {
    $count = 0;
    for ($thousandsUnit = 0; $thousandsUnit < 10; $thousandsUnit++) {
        for ($houndredsUnit = 0; $houndredsUnit < 10; $houndredsUnit++) {
            for ($tensUnit = 0; $tensUnit < 10; $tensUnit++) {
                for ($units = 0; $units < 10; $units++) {
                    $guessPin = (string) $thousandsUnit . (string) $houndredsUnit
                            . (string) $tensUnit . (string) $units;
                    $md5Guess = md5($guessPin);
                    $md5list[$guessPin] = $md5Guess;

                    if (strcmp($md5Guess, $md5Input) === 0) {
                        return $guessPin;
                    }
                }
            }
        }
    }
    return "Not found";
}

function getReversedMd5Hash($md5List, $limit) {
    $count = 0;
    $checkedPins = "";
    foreach ($md5List as $key => $val) {
        if ($count < $limit) {
            $checkedPins = $checkedPins . "$val $key" . "<br>";
        } else {
            break;
        }
        $count++;
    }
    return $checkedPins;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sudipta Sharif MD5 Cracking</title>
    </head>
    <body>
        <h1>MD5 cracker</h1>
        <p>This application takes an MD5 hash of a four digit pin and checks all 10,000 possible four digit PINs to determine the PIN.</p>
        <pre><?= $debug ?></pre>
        <p>PIN: <?= $pin ?></p>    
        <form>
            <input type="radio" id="bruteforce" name="crackmethod" value="bruteforce" <?php echo $bfchecked ? 'checked' : ''?>>
            <label for="bruteforce">Brute Force</label>            
            <input type="radio" id="guesses" name="crackmethod" value="guesses" <?php echo !$bfchecked ? 'checked' : ''?>>
            <label for="guesses">Random Guesses</label><br>         
            <input type="text" name="md5" size="50" value='<?= $md5input ?>'>
            <input type="submit" value="Crack MD5">
        </form>
        <ul>
            <li><a href=index.php>Reset this page</a></li>
            <li><a href=makepin.php>Make an MD5 PIN</a></li>
            <li><a href=md5.php>MD5 Encoder</a></li>
        </ul>
    </body>
</html>

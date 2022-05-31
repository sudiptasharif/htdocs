<!-- MODEL START (PHP CODE INVISIBLE IN THE BROWSER)-->
<?php
/**
 * ****************************************************
 *                     MODEL START
 * ****************************************************
 */
require_once '../phputil/util.php';
$guess = '';
$msg = false;
if (isset($_POST['guess'])) {
    $rightguess = 69;
    $guess = htmlentities($_POST['guess']);
    if (strlen($guess) < 1) {
        $msg = "Too short";
    } else if (!is_numeric($guess)) {
        $msg = "Guess must be numberic";
    } else if ($guess == $rightguess) {
        $msg = "Great job!";
    }else if ($guess < $rightguess) {
        $msg = "Too low";
    } else {
        $msg = "Too high";
    }
}
/**
 * ****************************************************
 *                     MODEL END
 * ****************************************************
 */
?>
<!-- MODEL END (PHP CODE SILENT PRE-PROCESSING DONE)-->
<!-- THE INVISIBLE LINE BELOW WHICH THERE SHOULD NOT BE ANY HUGE PHP CODE OR ANY DB RELATED CODE -->
<!-- VIEW START -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MVC Pattern</title>
    </head>
    <body>
        <h1>A Guessing game</h1>
        <p>A simple MVC pattern using the guessing game</p>
        <!-- IT'S OK TO TO HAVE THIS KIND OF PHP (INVISIBLE IN THE BROWSER)-->
        <?php 
        // THIS IS OK - $msg and $guess are part if the 'context' that is
        // sent down by the model
        if ($msg !== false) {
           l(p($msg));
        }
        ?>
        <form method="post">
            <p>
                <label for="guess">Input Guess</label>
                <input type="text" name="guess" id="guess" size="40" value="<?=$guess?>"/>
                <input type="submit"/>
                <!-- This a neat trick to close the current page, not submit the form and return to a specified page like the cancel button of a GUI form -->
                <input type="button" onclick="location.href='index.php'; return false;" value="Escape"/>
            </p>
        </form>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="get.php">GET Form</a></li>
            <li><a href="post.php">POST Form</a></li>
            <li><a href="mvc.php">MVC</a></li>
            <li><a href="filter.php">Practice filter_var()</a></li>
        </ul>            
    </body>
</html>
<!-- VIEW END -->
<!-- MODEL + VIEW = CONTROLLER FOR THIS PAGE -->

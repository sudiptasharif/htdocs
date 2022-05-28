<?php require_once '../phputil/util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP Forms</title>
    </head>
    <body>
        <h1>PHP Forms using GET</h1>
        <form>
            <p>
                <label for="guess">Input Guess</label>
                <input type="text" name="guess" id="guess"/>
            </p>
            <p>
                <label for="pass">Input Password</label>
                <input type="password" name="pass" id="pass">
            </p>
            <fieldset> 
                <legend>
                    Choose your monster's features:
                </legend>
                <p>
                    <input type="checkbox" id="scales" name="scales"/>
                    <label for="scales">Scales</label>
                </p>      
                <p>
                    <input type="checkbox" id="horns" name="horns"/>
                    <label for="horns">Horns</label>
                </p>                   
            </fieldset>
            <p>
                <input type="submit"/>
            </p>
        </form>
        <ul>
            <li><?php ln(a("index.php", "Reset")); ?></li>
        </ul>
        <?php
        d($_GET);
        ?>
    </body>
</html>

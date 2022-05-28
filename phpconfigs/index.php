<?php
    $nameSha256 = hash('sha256', 'Sudipta Sharif');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sudipta Sharif PHP</title>
    </head>
    <body>
        <h1>Sudipta Sharif PHP</h1>
        <p>
            The SHA256 hash of "Sudipta Sharif" is <?php echo $nameSha256; ?> 
        </p>
        <pre>ASCII ART:

    ***********
    **
    **
    ***********
    ***********
             **
             **
    ***********</pre>
        <p>
            <a href="fail.php" target="_blank">Click here to check the error setting</a>
            <br>
            <a href="check.php" target="_blank">Click here to cause a traceback</a>
        </p>
    </body>
</html>

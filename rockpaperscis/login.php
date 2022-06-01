<?php
$who = '';
$pass = '';
$errcolor = 'color:red';
$msgcolor = '';
$msg = '';
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if (isset($_POST['login'])) {
    $who = htmlentities($_POST['who']);
    $pass = htmlentities($_POST['pass']);
    if (strlen($who) < 1 || strlen($pass) < 1) {
        $msgcolor = $errcolor;
        $msg = 'User name and password are required';
    } else {
        $salt = 'XyZzy12*_';
        $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
        $input_hash = hash('md5', $salt . $pass);
        if (strcasecmp($stored_hash, $input_hash) !== 0) {
            $msgcolor = $errcolor;
            $msg = 'Incorrect password';
        } else {
            $redir_page = 'game.php?name='.urldecode($who);
            header("Location: http://$host$uri/$redir_page");
            die();
        }
    }
} elseif (isset($_POST['cancel'])) {
    $redir_page = 'index.php';
    header("Location: http://$host$uri/$redir_page");
    // die() stops the execution of the rest of the page, in this case
    // the html below. You can check the network tab of the developer options.
    // if you check the file size you will notice the difference of havig die() vs 
    // having it commented out. 
    die(); // this call is crucial - read the docs and user notes
    // use die() instead if exit, read the docs. exit does not always exit. 
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sudipta Sharif's Login page</title>
    </head>
    <body>
        <h1>Please Log In</h1>
        <p style="<?= $msgcolor ?>"><?= $msg ?></p>
        <form method='post'>
            <p>
                <label for="who">User Name</label>
                <input type="text" id="who" name="who"/>
            </p>
            <p>
                <label for="pass">Password</label>
                <input type="text" id="pass" name="pass"/>
            </p>
            <input type="submit" name="login" id="login" value="Log In"/>
            <input type="submit" name="cancel" id="cancel" value="Cancel" />
            <br/>
            <span>For a password hint, view source and find a password hint in the HTML comments.</span>
            <!-- Hint: The password is the three character name of the 
            programming language used in this class (all lower case) 
            followed by 123. -->
        </form>
    </body>
</html>

<?php
$name = '';
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$play_result = "Please select a strategy and press Play.";
if (isset($_GET['name'])) {
    $name = htmlentities($_GET['name']);
    if (isset($_POST['logout'])) {
        $redir_page = 'index.php';
        header("Location: http://$host$uri/$redir_page");
        die();
    } elseif (isset($_POST['play'])) {
        $user_play = $_POST['user_play'] + 0;
        if ($user_play > 0 && $user_play < 4) {
            $computer_play = rand(1, 3);
            $play_result = check($computer_play, $user_play);
            $user_play = getPlay($user_play);
            $computer_play = getPlay($computer_play);
            $play_result = "Human=$user_play Computer=$computer_play Result=$play_result";
        } else {
            $play_result = 'TODO';
        }
    }
} else {
    die('Name parameter missing');
}

function check($computer, $user) {
    $ROCK = 1;
    $PAPER = 2;
    $SCISSOR = 3;    
    $result = "";
    if ($computer == $user) {
        $result = "Tie";
    } elseif (($user == $ROCK) && ($computer == $SCISSOR)) {
        $result = "You win";
    } elseif (($computer == $ROCK) && ($user == $SCISSOR)) {
        $result = "You Lose";
    } elseif (($user == $SCISSOR) && ($computer == $PAPER)) {
        $result = "You win";
    } elseif (($computer == $SCISSOR) && ($user == $PAPER)) {
        $result = "You Lose";
    } elseif (($user == $PAPER) && ($computer == $ROCK)) {
        $result = "You win";
    } elseif (($computer == $PAPER) && ($user == $ROCK)) {
        $result = "You Lose";
    }  
    return $result;
}

function getPlay($playId) {
    $play = "";
    if ($playId == 1) {
        $play = "Rock";
    } elseif($playId == 2) {
        $play = "Paper";
    } elseif ($playId == 3) {
        $play = "Scissor";
    }
    return $play;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sudipta Sharif's Rock Paper Scissor Game</title>
    </head>
    <body>
        <h1>Rock Paper Scissors</h1>
        <p>Welcome: <span><?= $name ?></span></p>
        <form method='post'>
            <select name="user_play" id="user_play">
                <option value="0">Select</option>
                <option value="1">Rock</option>
                <option value="2">Paper</option> 
                <option value="3">Scissor</option>
                <option value="4">Test</option>
            </select>
            <input type="submit" name="play" value="Play"/>
            <input type="submit" name="logout" value="Logout" />
        </form>
        <pre><?=$play_result?>
        </pre>
    </body>
</html>

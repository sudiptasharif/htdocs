<?php
# Name: Sudipta Sharif
session_start();
$info = NULL;
$username = NULL;
$email = NULL;
$fullname = NULL;
$errArr = NULL; # used during dev, removed in prod

if(!isset($_SESSION["username"])){
    $_SESSION["info"] = "Please first login using your credentials.";
    header("location:http://localhost:8888/project5/login.php");
}else{
    $username = $_SESSION["username"];
    $email = $_SESSION["email"];
    $fullname = $_SESSION["fullname"];
    $errArr = array();
}

if(isset($_REQUEST['logout'])){
    session_unset();
    session_destroy(); 
    header("location:http://localhost:8888/project5/login.php");
}

if(isset($_REQUEST['add'])){
    $friend = $_REQUEST['add'];
    $username = $_SESSION['username'];
    addFriendToUser($username, $friend);
}

if(isset($_REQUEST['remove'])){
    $friend = $_REQUEST['remove'];
    $username = $_SESSION['username'];  
    removeFriendFromUser($username, $friend);  
}

function createDBConn(){
    // 
    $dbh = new PDO("mysql:host=127.0.0.1:8889;dbname=yourdbname","yourusername","yourpassword",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    return $dbh;
}

function getFriendsArrByUsername($username){
    $err_tag = "getFriendsArrByUsername";
    $friendsArr = array();
    try{
        $dbConn = createDBConn();
        $sql = "SELECT friend FROM friends WHERE user = :user";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute(array(':user' => $username));
        $rows = $stmt->fetchAll(); 
        foreach($rows as $row){
            $friendsArr[] = $row['friend'];
        }
        return $friendsArr;
    }catch (PDOException $e) {
        logError($err_tag . ": " . tagBegin("b") . $e->getMessage() . tagEnd("b"));
        return array(); 
    }
}

function getOthersArrByUsername($username){
    $err_tag = "getOthersArrByUsername";
    $othersArr = array();
    try{
        $dbConn = createDBConn();
        $sql = "SELECT username FROM users u WHERE u.username NOT IN (SELECT friend FROM friends WHERE user = :user) AND u.username <> :username";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute(array(':user' => $username, ':username' => $username));
        $rows = $stmt->fetchAll(); 
        foreach($rows as $row){
            $othersArr[] = $row['username'];
        }
        return $othersArr;
    }catch (PDOException $e) {
        logError($err_tag . ": " . tagBegin("b") . $e->getMessage() . tagEnd("b"));
        return array(); 
    }   
}

function getUserDetailsAsStr($username){
    $err_tag = "getUserDetailsAsStr";
    $details = "";
    try{
        $dbConn = createDBConn();
        $sql = "SELECT * FROM users WHERE username = :user";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute(array(':user' => $username));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        $details .= $username . ", " . $row['fullname'] . ", " . $row['email']; 
        return $details; 
    }catch (PDOException $e) {
        logError($err_tag . ": " . tagBegin("b") . $e->getMessage() . tagEnd("b"));
        return ""; 
    }
}

function getOrderedFreindListOfUser($username){
    $friendsArr = getFriendsArrByUsername($username);
    $friend = "";
    $ol = "";
    $li = "";
    for($i = 0; $i < count($friendsArr); $i++){
        $friend = $friendsArr[$i];
        $li .= tagBegin("li") . getUserDetailsAsStr($friend) . "&nbsp&nbsp" . getTargetBtnByName("Remove", $friend) .tagEnd("li");
    }
    $ol = tagBegin("ol") . $li . tagEnd("ol");
    return $ol;
}

function getOrderedOtherListOfUser($username){
    $othersArr = getOthersArrByUsername($username);
    $other = "";
    $ol = "";
    $li = "";
    for($i = 0; $i < count($othersArr); $i++){
        $other = $othersArr[$i];
        $li .= tagBegin("li") . getUserDetailsAsStr($other) . "&nbsp&nbsp" . getTargetBtnByName("Add", $other) .tagEnd("li");
    }
    $ol = tagBegin("ol") . $li . tagEnd("ol");
    return $ol;    
}

function getTargetBtnByName($targtBtnName, $friend){
    $friend = urlencode($friend);
    $methodName = "add";
    if(strcmp("REMOVE", strtoupper($targtBtnName)) == 0){
        $methodName = "remove";
    }
    return "<button class='liBtn' type='submit' formaction='network.php?$methodName=$friend'>$targtBtnName</button>";  
}

function addFriendToUser($username, $friend){
    $err_tag = "addFriendToUser";
    try{
        $dbConn = createDBConn();
        $sql = "INSERT INTO friends (user, friend) VALUES (:user, :friend)";
        $stmt = $dbConn->prepare($sql);        
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':friend', $friend);
        $stmt->execute();
    }catch (PDOException $e) {
        logError($err_tag . ": " . tagBegin("b") . $e->getMessage() . tagEnd("b"));
    } 
}

function removeFriendFromUser($username, $friend){
    $err_tag = "addFriendToUser";
    try{
        $dbConn = createDBConn();
        $sql = "DELETE FROM friends WHERE user=:user AND friend=:friend";
        $stmt = $dbConn->prepare($sql);        
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':friend', $friend);
        $stmt->execute();
    }catch (PDOException $e) {
        logError($err_tag . ": " . tagBegin("b") . $e->getMessage() . tagEnd("b"));
    }     
}

function logError($info){
    global $errArr;
    $errArr[] = $info;
}

function tagBegin($tag_name){
    return "<$tag_name>";
}

function tagEnd($tag_name){
    return "</$tag_name>";
}

function safeEcho($var){
    if(isset($var)){
        echo $var;
    }
}

function safeParseStr($val){
    if(isset($val)){
        return $val;
    }else{
        return "";
    }
}

function html($tag, $val, $label = ""){
    echo tagBegin($tag) . $label . $val . tagEnd($tag); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network</title>
    <style>
        li{
            margin-bottom:10px;
        }
    </style>
</head>
<body>
    <h1>A Social Network using PHP and MySQL</h1>
    <form method="post">
        <h2>User Details</h2>
        <p>
            <b>Full name:</b> <?=$fullname?><br/>
            <b>Username:</b> <?=$username?><br/>
            <b>Email:</b> <?=$email?>
        </p>
        <h2>Friends</h2>
        <?php echo getOrderedFreindListOfUser($username); ?>
        <h2>Others</h2>
        <?php echo getOrderedOtherListOfUser($username);?>
        <p><button type="submit" formaction="network.php?logout=FALSE">Logout</button></p>
    </form>
</body>
</html>
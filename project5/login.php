<?php
# Name: Sudipta Sharif
session_start();
$user = NULL;
$pwd = NULL;
$info = NULL;

if(isset($_SESSION["info"])){
    $info = $_SESSION["info"];
}

if(isset($_POST["username"]) && isset($_POST["password"])){
    $user = $_POST["username"];
    $pwd = $_POST["password"];

    if(validUser($user, $pwd)){
        header("location:http://localhost:8888/project5/network.php");
    }else{
        session_unset();
        session_destroy();  
    }
}

function validUser($user, $pwd){
    global $info;
    $pwd = md5($pwd);
    $row = NULL;
    try {
        $dbh = new PDO("mysql:host=127.0.0.1:8889;dbname=network","root","root",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));        
        $stmt = $dbh->prepare("SELECT username, email, fullname FROM users WHERE username = :uname AND password = :pwd");
        $stmt->execute(array(':uname' => $user, ':pwd' => $pwd));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 

        if($row){
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pwd;
            $_SESSION["email"] = $row["email"];
            $_SESSION["fullname"] = $row["fullname"];
            return TRUE;
        }else{
            $info = "Invalid credentials. Please try again."; 
            return FALSE;
        }
      } catch (PDOException $e) {
        $info = tagBegin("b") . "Error: ". $e->getMessage() . tagEnd("b");
    }
    return FALSE;
}

function safeEcho($var){
    if(isset($var)){
        echo $var;
    }
}

function html($tag, $val, $label = ""){
    echo tagBegin($tag) . $label . $val . tagEnd($tag); 
}

function tagBegin($tag_name){
    return "<$tag_name>";
}

function tagEnd($tag_name){
    return "</$tag_name>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>A Social Network using PHP and MySQL</h1>
    <form action="login.php" method="post">
        <label for="username"><b>Username:</b></label>
        <input type="text" name="username" value="<?php safeEcho($user);?>"/>

        <label for="password"><b>Password:</b></label>
        <input type="password" name="password" value="<?php safeEcho($pwd);?>"/>

        <button type="submit">Login</button>
    </form>  
    <?php
        if(isset($info)){
    ?>
        <p><?=$info;?></p>
    <?php        
        }
    ?>
</body>
</html>
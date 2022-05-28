<?php
/**
 * Name: Sudipta Sharif
 */
require_once 'DropboxClient.php';
require_once 'demo-lib.php';
demo_init();
set_time_limit(0);
$return_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";
$infoMsg = NULL;
$downloadFolder = "downloads";
$selectedImgSrc = NULL;
$selectedImgName = NULL;

$dropbox = new DropboxClient( array(
	'app_key' => "hg4uhtdif7c5hls",      
	'app_secret' => "uoxg9eus5k0s52q",   
	'app_full_access' => false,
));

$bearer_token = demo_token_load( "bearer" );
if($bearer_token){
	$dropbox->SetBearerToken( $bearer_token );
}elseif(!empty($_GET['auth_redirect'])){
	$bearer_token = $dropbox->GetBearerToken( null, $return_url );
	demo_store_token( $bearer_token, "bearer" );
}elseif ( ! $dropbox->IsAuthorized() ) {
	$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
	die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

if(isset($_FILES['userfile']['name'])){
    $upload_name = $_FILES['userfile']['name'];
    $meta = $dropbox->UploadFile($_FILES['userfile']['tmp_name'],$upload_name);
    if(empty($meta) && empty($upload_name)){
        $infoMsg = "Please choose a file to upload to Dropbox.";
    }elseif(empty($meta)){
        $infoMsg = "Failed to Upload $upload_name to Dropbox.";
    }else{
        $infoMsg = "Successfully Uploaded $upload_name to Dropbox.";
        $jpg_files = $dropbox->Search( "/", ".jpg");
    }
}

if(isset($_REQUEST['download'])){
    $imgName = $_REQUEST['download'];
    $imgPath = $_REQUEST['img_path'];
    $destPath = $downloadFolder . $imgPath;
    $meta = $dropbox->DownloadFile($imgPath, $destPath);
    if(empty($meta)){
        $infoMsg = "Failed to Download $imgName from Dropbox.";
    }else{
        $infoMsg = "Successfully Downloaded $imgName from Dropbox.";
        setSelectedImageName($imgName);
        setSeletedImgSrc($destPath);
    }
}

if(isset($_REQUEST['delete'])){
    $imgName = $_REQUEST['delete'];
    $imgPath = $_REQUEST['img_path'];    
    $meta = $dropbox->Delete($imgPath);
    if(empty($meta)){
        $infoMsg = "Failed to Delete $imgName from Dropbox.";
    }else{
        $infoMsg = "Successfully Deleted $imgName from Dropbox.";
    }    
}

function setSelectedImageName($imgName){
    global $selectedImgName;
    $selectedImgName = $imgName;
}

function setSeletedImgSrc($destPath){
    global $selectedImgSrc;
    $selectedImgSrc = $destPath;
}

function displayJPGFilesAsUnorderList($jpg_files){
    echo "<h3>Uploaded Images</h3>";
    echo "<ol>";
    $li = "";
    $a = "";
    foreach($jpg_files as $jpg){
        $a = makeAnchorTagUsingImgInfo($jpg->name, $jpg->path);
        $btn = makeDeleteButtonForImg($jpg->name, $jpg->path);
        $li = "<li>" . $a . $btn . "</li>" ;
        echo $li;
    }
    echo "</ol>";
}

function makeAnchorTagUsingImgInfo($img_name, $img_path){
    $encodedImgName = urlencode($img_name);
    $img_path = urlencode($img_path);
    $a = "<a href=";
    $href = "album.php?download=" . $encodedImgName . "&img_path=$img_path";
    $a .= $href . ">$img_name</a>";
    return $a;
}

function makeDeleteButtonForImg($img_name, $img_path){
    $img_name = urlencode($img_name);
    $img_path = urlencode($img_path);
    return "<button class='liBtn' type='submit' formaction='album.php?delete=$img_name&img_path=$img_path'>Delete</button>"; 
}

function html($tag_name, $val){
    $html = tagBegin($tag_name) . $val . tagEnd($tag_name); 
    echo $html;
}

function img($src, $alt = 'Unable to display Image'){
    $img = "<img src='$src' alt='$alt' >";
    return $img;
}

function tagBegin($tag_name){
    return "<$tag_name>";
}

function tagEnd($tag_name){
    return "</$tag_name>";
}

function sanitizeStr($val){
    if(isset($val)){
        return $val;
    }else{
        return "";
    }
}

function safeEcho($var){
    if(empty($var)){
        echo "";
    }else{
        echo $var;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project 6</title>
    <style>
    input{
        margin-bottom:10px;
    }
    li{
        margin-bottom:10px;
    }
    #dropbox-image-list{
        width:25%;
        height:100%;
        float:left;
    }
    #selected-image{
       float:left;
        width:50%;
        height:100%;
    }
    .liBtn{
        margin-left:15px;
    }
    img{
        max-width:100%;
        height:auto;
        border-radius:4px;
        border:1px solid #ddd;
        padding: 5px;
    }
    </style>
</head>
<body>
    <h2>Project 6: Using Cloud (Dropbox) Storage</h2>
    <h3>Upload an Image (.jpg)</h3>
    <form action="album.php" enctype="multipart/form-data" method="post">
        <div>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input name="userfile" type="file"/><br/>
            <input type="submit" value="Upload" />
        </div>
        <?php html("h5", sanitizeStr($infoMsg)); ?>
        <div id="dropbox-image-list">
            <?php
                $jpg_files = $dropbox->Search( "/", ".jpg");
                if(empty($jpg_files)){
                    echo "<h3>0 Uploaded Images</h3>";
                }else{
                    displayJPGFilesAsUnorderList($jpg_files);
                }
            ?>
        </div>
        <div id="selected-image">
            <?php 
                if(isset($selectedImgSrc) && isset($selectedImgName)){
                    html("h3", "Selected Image: $selectedImgName");
                    safeEcho(img($selectedImgSrc, "Selected Dropbox Image."));
                }
            ?>
        </div>
    </form>
</body>
</html>

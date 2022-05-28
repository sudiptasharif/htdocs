<?php
/**
* Sudipta Sharif
*/
$api_key = ""; // TMDb api key
$api_key = "api_key=$api_key";

$urlBase = "https://api.themoviedb.org";
$mSearchMethod = "/3/search/movie";
$mDetailsMethod = "/3/movie/";

$mSearchJSONArr = NULL;
$mDetailsJSONArr = NULL;
$mCreditsJSONArr = NULL;
$search = NULL;
$movie_id = NULL;
$mSearchURL = NULL;
$mDetailsURL = NULL;
$mCreditsURL = NULL;

$total_results = NULL;
$total_pages = NULL;
$resultsArr = NULL;


if(isset($_GET["search"])){
    $page = 1;
    $search = $_GET["search"];
    $mSearchURL = $urlBase . $mSearchMethod . "?$api_key&query=" . urlencode($search) ."&page=$page";  
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mSearchURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $mSearchJSONArr = json_decode(curl_exec($ch), true);

    $total_results = $mSearchJSONArr["total_results"];
    $total_pages = $mSearchJSONArr["total_pages"];
    $resultsArr = $mSearchJSONArr["results"];
    if($total_pages > 1){
        for($i = 2; $i <= $total_pages; $i++){
            $page = $i;
            $mSearchURL = $urlBase . $mSearchMethod . "?$api_key&query=" . urlencode($search) ."&page=$page";  
            curl_setopt($ch, CURLOPT_URL, $mSearchURL);
            $mSearchJSONArr = json_decode(curl_exec($ch), true);
            $resultsArr = array_merge($resultsArr, $mSearchJSONArr["results"]);
        }
    }
    curl_close($ch);
}
if(isset($_GET["movie_id"])){
    $movie_id = $_GET["movie_id"];
    $mDetailsURL = $urlBase . $mDetailsMethod . $movie_id . "?$api_key";
    $ch = curl_init($mDetailsURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $mDetailsJSONArr = json_decode(curl_exec($ch), true);
    curl_close($ch);
    
    $mCreditsMethod = $mDetailsMethod . $movie_id . "/credits";
    $mCreditsURL = $urlBase . $mCreditsMethod . "?$api_key";
    $ch = curl_init($mCreditsURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $mCreditsJSONArr = json_decode(curl_exec($ch), true);
    curl_close($ch);
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

function safeEcho($var){
    if(isset($var)){
        echo $var;
    }
}
function safeGetVar($var){
    if(isset($var)){
        return $var;
    }else{
        return "";
    }    
}

function safeGetArrVal($arr, $key){
    if(array_key_exists($key, $arr)){
        return $arr[$key];
    }else{
        return "";
    }
}

function prettyVarDump($data){
    html("pre", var_export($data, true));
}

function makePosterURL($postPath, $imageURLBase="https://image.tmdb.org/t/p/w185"){
    return $imageURLBase . $postPath;
}

function getSearchTerm(){
    global $search;
    global $searchWithAnchor;
    if(isset($search)){
        return $search;
    }elseif(isset($searchWithAnchor)){
        return $searchWithAnchor;
    }else{
        return "type a title to search!";
    }
}
function getYearFromYYYYMMDD($dateInYYYMMDD){
    return explode("-", $dateInYYYMMDD)[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSE 5335 Project 4</title>
    <style>
        #movie-list {
            width:30%;
            height:100%;
            float:left;
            margin-right: 20px;
        }
        #selected-movie-details{
            float:left;
            width:60%;
            height:100%;
        }
        .bold{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>The Movie Web Application in PHP</h1>
    <form action="" method="get">
        <label>Movie title: <input type="text" name="search" value="<?php echo safeEcho($search);?>"/></label>
        <input type="submit" value="Display Info"/>
    <div id="search-result">
        <div id="movie-list">
            <?php
                if(isset($resultsArr)){
                    echo "<h3>Movie Search Result (Total Found: $total_results)</h3>";
                }
            ?>
            <ol>
                <?php
                    if(isset($resultsArr)){
                        for($i = 0; $i < count($resultsArr); $i++){
                            $id = safeGetArrVal($resultsArr[$i], "id");
                            $title = safeGetArrVal($resultsArr[$i], "title");
                            $release_date = safeGetArrVal($resultsArr[$i], "release_date");
                            if(strlen(trim($release_date)) === 0) $release_date = "No Release Year From TMDb";
                            $title .= " (" . getYearFromYYYYMMDD($release_date) . ")";
                            $urlEncodeSearch = urlencode($search);
                            $href = "movies.php?search=$urlEncodeSearch&movie_id=$id";
                ?>
                    <li>
                        <a href="<?php echo $href?>"><?= $title?></a>
                    </li>
                    <br/>
                <?php
                        }
                    }
                ?>
            </ol>
        </div>
        <div id="selected-movie-details">
            <?php
                if(isset($mDetailsJSONArr)){
                   $poster_path = safeGetArrVal($mDetailsJSONArr, "poster_path");
                   //if(strlen($poster_path) == 0){
                       //$poster_path = "no_poster.png";
                   //}else{
                       $poster_path = makePosterURL($poster_path);
                   //}
                   $title = safeGetArrVal($mDetailsJSONArr, "title");
                   $overview = safeGetArrVal($mDetailsJSONArr, "overview");
                   $vote_avg = safeGetArrVal($mDetailsJSONArr, "vote_average");
                   $vote_count = safeGetArrVal($mDetailsJSONArr, "vote_count"); # not showing it, just for me! 
                   $release_date = safeGetArrVal($mDetailsJSONArr, "release_date");
                   if(strlen($release_date) == 0){
                       $release_date = "No Release Year From TMDb";
                   }
                   $genreArr = NULL;
                   $genreStr = "";
                   if(array_key_exists("genres", $mDetailsJSONArr)){
                        $genreArr = $mDetailsJSONArr["genres"];
                        for($i = 0; $i < count($genreArr); $i++){
                            if($i == count($genreArr) - 1){
                                $genreStr .= $genreArr[$i]["name"] . ".";
                            }else{
                                $genreStr .= $genreArr[$i]["name"] . ", ";
                            }
                        }
                   }
            ?>
            <h2>Poster</h2>
             <div>
                   <img src="<?=$poster_path?>" alt="No Movie Poster From TMDb">
             </div>
             <h2>Details</h2>
             <div>
                <p><span class="bold">Title: </span><?=$title?></p>
                <p><span class="bold">Release Date: </span><?=$release_date?></p>
                <p><span class="bold">Genre(s): </span><?=$genreStr?></p>
                <p><span class="bold">Vote Average: </span><?=$vote_avg?></p>
                <!--<p><span class="bold">Vote Count: </span></p>-->
                <p><span class="bold">Overview: </span><?=$overview?></p>
             </div>    
            <?php
                }
            ?>        

                <?php
                    if(isset($mCreditsJSONArr)){
                        $hLabel = "";
                        $castArr = NULL;
                        $topCasts = array();
                        $total_cast = 0;
                        $top_total = 5;
                        if(array_key_exists("cast", $mCreditsJSONArr)){
                            $castArr = $mCreditsJSONArr["cast"];
                            $total_cast = count($castArr);
                            if($total_cast < $top_total){
                                $top_total = $total_cast;
                                $hLabel = "($top_total found!)";
                            }
                            for($i = 0; $i<$top_total; $i++){
                                $topCasts[] = $castArr[$i]["name"];
                            }
                        }
                        $hLabel = "Top Cast Members ". $hLabel;
                ?>
                <h2><?=$hLabel?></h2>
                <ol>
                <?php
                        for($i=0; $i<$top_total; $i++){
                            $html = "<li>$topCasts[$i]</li>";
                            echo $html;
                        }
                    }
                ?>
                </ol>
       
    </div>
    </form>
</body>
</html>
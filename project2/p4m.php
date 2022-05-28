<?php
/**
* Name: Sudipta Sharif
*/
$api_key = ""; // fill in when needed
$api_key = "api_key=$api_key";

$mSearchRespJSON = NULL;
$mDetailsRespJSON = NULL;
$mCreditsRespJSON = NULL;

$respJSONStr = NULL;

$search = NULL;
$movie_id = NULL;

$urlBase = "https://api.themoviedb.org";
$mSearchMethod = "/3/search/movie";
$mDetailsMethod = "/3/movie/";


$mSearchURL = NULL;
$mDetailsURL = NULL;
$mCreditsURL = NULL;


if(isset($_GET["search"])){
    $search = $_GET["search"];
    $mSearchURL = $urlBase . $mSearchMethod . "?$api_key?query=" . urlencode($search);  
    
    //header("Content-type: application/json\n\n");
    //$ch = curl_init($mSearchURL);
    //$respJSONStr = curl_exec($ch);
}
if(isset($_GET["movie_id"])){
    $movie_id = $_GET["movie_id"];
    $mDetailsURL = $urlBase . $mDetailsMethod . $movie_id . "?$api_key";
    
    $mCreditsMethod = $mDetailsMethod . $movie_id . "/credits";
    $mCreditsURL = $urlBase . $mCreditsMethod . "?$api_key";
}

function l($val, $label=""){

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSE 5335 Project 4</title>
</head>
<body>
    <h1>The Movie Web Application in PHP</h1>
    <form action="movies.php" method="get">
        <label>Movie title: <input type="text" name="search" value="<?php if(isset($search)) echo $search;?>"/></label>
        <input type="submit" value="Display Info"/>
    
    <p>
        search = <?= $search?> <br/>
        movie_id = <?= $movie_id ?> <br/>
        movie search url = <?=$mSearchURL?> <br/>
        movie details url = <?=$mDetailsURL ?> <br/>
        movie credits url = <?=$mCreditsURL?> <br/>
        movie search resp JSON Str: <br/>
        <?=$respJSONStr?>
    </p>
    <div>
        <h3>Movie Search Result</h3>
        <label> 1 <input type="hidden" name="movie_id" value="603"/><input type="submit" value="The Matrix"/></label>
    </div>
    </form>
</body>
</html>
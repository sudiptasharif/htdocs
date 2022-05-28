<?php
include_once 'db_oracle.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/global.css">
    <title>Report: Customer Movie Count</title>
</head>
<body>
<h1>Customer Movie Count</h1>
<p>This report returns the list of customer ids and the number of movies watched based on a genre after a given date</p>
<form action="" method="post">
    <p>
        <label for="target_genre">Select Genre: </label>
        <select name="target_genre" id="target_genre">
            <option value="Action">Action</option>
            <option value="Adventure">Adventure</option>
            <option value="Animation">Animation</option>
            <option value="Comedy">Comedy</option>
            <option value="Crime">Crime</option>
            <option value="Drama">Drama</option>
            <option value="Family">Family</option>
            <option value="Fantasy">Fantasy</option>
            <option value="History">History</option>
            <option value="Horror">Horror</option>
            <option value="Music">Music</option>
            <option value="Mystery">Mystery</option>
            <option value="Romance">Romance</option>
            <option value="ScienceFiction">Science Fiction</option>
            <option value="Thriller">Thriller</option>
            <option value="War">War</option>
            <option value="Western">Western</option>
        </select>
    </p>
    <p>
        <label for="target_date">Select Date: </label>
        <input type="date" name="target_date" id="target_date" required>
    </p>
    <p>
        <input type="submit" value="Submit">
    </p>
</form>
<?php
if(isset($_POST["target_genre"]) && isset($_POST['target_date'])) {
    h2("Result");
    $tbl_cols = array('custid', 'total_movies');
    $target_genre = strtolower($_POST["target_genre"]);
    $target_date = $_POST["target_date"];
    span("Target Genre: ".$_POST["target_genre"]);
    l("<br/>");
    span("Target Date: ".$target_date);
    $sql = "select ".
                "sd.custid ".
                ",count(mg.movieid) as total_movies ".
            "from ".
                "f21_s001_20_customer c ".
                "inner join f21_s001_20_streaming_details sd ".
                    "on c.custid = sd.custid ".
                "inner join f21_s001_20_movie_genre mg ".
                    "on sd.movieid = mg.movieid ".
                "inner join f21_s001_20_genre g ".
                    "on mg.genreid = g.genreid ".
            "where ".
                "sd.streamdt > to_date(:p0, 'YYYY-MM-DD') ".
                "and lower(g.genrename) = :p1 ".
            "group by ".
                "sd.custid ".
            "order by ".
                "sd.custid";
    $count = 0;
    try {
        $conn = getDBConn();
        if(!$conn) {
            $err = getDBConnErrorMSg();
            h2("Database Connection Failed");
            h3($err);
            die();
        }

        $stmt = oci_parse($conn, $sql);
        if(!$stmt) {
            $err = getOCIErrorMsg($stmt);
            h2("Error");
            h3($err);
            die();
        }
        oci_bind_by_name($stmt, ':p0', $target_date);
        if(strcmp($target_genre, "sciencefiction") == 0) {
            $target_genre = strtolower("Science Fiction");
        }
        oci_bind_by_name($stmt, ':p1', $target_genre);
        $r = oci_execute($stmt);
        if (!$r) {
            $err = getOCIErrorMsg($stmt);
            h2("Error");
            h3($err);
            die();
        }

        echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<th>#</th>";
        foreach($tbl_cols as $col_name){
            echo "<th>".strtoupper($col_name)."</th>";
        }
        echo "</tr>\n";
        while ($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo "<tr>\n";
            echo "<td>".($count+1)."</td>";
            $count++;
            foreach ($row as $item) {
                echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";

        freeDBStatement($stmt);
        closeDBConn($conn);
    } catch(Exception $e) {
        h2("Error");
        h3($e->getMessage());
        freeDBStatement($stmt);
        closeDBConn($conn);
        die();
    }
}
?>
</body>
</html>

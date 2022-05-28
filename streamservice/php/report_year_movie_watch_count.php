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
    <title>Report: Movie Watch Count</title>
</head>
<body>
<h1>Movie Watch Count</h1>
<p>This report analyzes movies watched by year using cube</p>
<form action="" method="post">
    <p>
        <label for="target_year">Select Year: </label>
        <select name="target_year" id="target_year">
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="all">All</option>
        </select>
    </p>
    <p>
        <input type="submit" value="Submit">
    </p>
</form>
<?php
if(isset($_POST['target_year'])){
    h2("Result");
    $tbl_cols = array('year', 'movie', 'watch count');
    $sql = "";
    if(strcmp($_POST['target_year'], 'all') == 0) {
        $sql = "select ".
            "extract(year from std.streamdt) as year, m.title, count(std.streamingid) as watch_count ".
            "from ".
            "f21_s001_20_streaming_details std ".
            "inner join f21_s001_20_movie m ".
            "on std.movieid = m.movieid ".
            "group by ".
            "cube(m.title, extract(year from std.streamdt)) ".
            "order by ".
            "extract(year from std.streamdt), m.title";
    } else {
        $sql = "select ".
            "extract(year from std.streamdt) as year, m.title, count(std.streamingid) as watch_count ".
            "from ".
            "f21_s001_20_streaming_details std ".
            "inner join f21_s001_20_movie m ".
            "on std.movieid = m.movieid ".
            "where ".
            "extract(year from std.streamdt) = :p1 ".
            "group by ".
            "cube(m.title, extract(year from std.streamdt)) ".
            "order by ".
            "extract(year from std.streamdt), m.title";
    }
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
        if(strcmp($_POST['target_year'], 'all') != 0) {
            oci_bind_by_name($stmt, ':p1', $_POST["target_year"]);
        }
        $r = oci_execute($stmt);
        if (!$r) {
            $err = getOCIErrorMsg($stmt);
            h2("Error");
            h3($err);
            die();
        }

        echo "<table border='1'>\n";
        echo "<tr>\n";
        foreach($tbl_cols as $col_name){
            echo "<th>".strtoupper($col_name)."</th>";
        }
        echo "</tr>\n";
        while ($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo "<tr>\n";
            foreach ($row as $item) {
                echo "    <td>" . ($item !== null ? $item : "&nbsp;") . "</td>\n";
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

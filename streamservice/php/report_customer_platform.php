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
    <title>Report: Customer Watch Platform</title>
</head>
<body>
<h1>Customer Watch Platform</h1>
<p>This report returns all platforms used by the customers to watch movies. It also returns the corresponding movie counts for a platform, and the grand total using rollup.</p>
<?php
h2("Result");
$tbl_cols = array('platform name', 'movies watched');
$sql = "select ".
            "pname, count(streamingid) as movies_watched ".
        "from ".
            "f21_s001_20_platform ".
        "group by ".
            "rollup(pname) ".
        "order by ".
            "pname";
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
            echo "    <td>" . ($item !== null ? $item : "Grand Total") . "</td>\n";
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
?>
</body>
</html>

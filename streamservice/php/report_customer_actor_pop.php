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
    <title>Report: Customer Actor Popularity</title>
</head>
<body>
<h1>Customer Actor Popularity</h1>
<p>This report returns all the actor names in the database ordered based on popularity by the avg rating given by all the customers in the target birth year (age)</p>
<form action="" method="post">
    <p>
        <label for="target_birth_year">Select Age/Birth Year: </label>
        <select name="target_birth_year" id="target_birth_year">
            <option value="1990">1990 (31 years)</option>
            <option value="1991">1991 (30 years)</option>
            <option value="1992">1992 (29 years)</option>
            <option value="1993">1993 (28 years)</option>
            <option value="1994">1994 (27 years)</option>
            <option value="1995">1995 (26 years)</option>
            <option value="1996">1996 (25 years)</option>
        </select>
    </p>
    <p>
        <input type="submit" value="Submit">
    </p>
</form>
<?php
function getBirthYearAge($val) {
    if(strcmp($val, "1990") == 0){
        return "1990 (31 years)";
    } else if (strcmp($val, "1991") == 0) {
        return "1991 (30 years)";
    } else if (strcmp($val, "1992") == 0) {
        return "1992 (29 years)";
    } else if (strcmp($val, "1993") == 0) {
        return "1993 (28 years)";
    } else if (strcmp($val, "1994") == 0) {
        return "1994 (27 years)";
    } else if (strcmp($val, "1995") == 0) {
        return "1995 (26 years)";
    } else if (strcmp($val, "1996") == 0) {
        return "1996 (25 years)";
    } else {
        return  "";
    }
}

if(isset($_POST["target_birth_year"])) {
    h2("Result");
    $tbl_cols = array('actor name', 'avg rating');
    span("Target Birth Year (age): ".$_POST['target_birth_year']);
    $sql = "select"
                ." a.actorname"
                ." ,round(avg(ar.rating),1) as avg_actor_rating"
            ." from"
                ." f21_s001_20_actor_rating  ar"
                ." inner join f21_s001_20_actor a"
                    ." on ar.actorid = a.actorid"
            ." where"
                ." ar.custid in (select distinct c.custid from f21_s001_20_customer c where extract(year from c.dob) = :p1)"
            ." group by"
                ." a.actorname"
            ." order by"
                ." avg_actor_rating desc";
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
        oci_bind_by_name($stmt, ':p1', $_POST["target_birth_year"]);
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

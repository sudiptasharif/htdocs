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
    <title>Report: Customer Complaints</title>
</head>
<body>
<h1>Customer Complaints</h1>
<p>This report returns the list of customers based on the number of complaints they made</p>
<form action="" method="post">
    <p>
        <label for="total_comp">Complaints Made: </label>
        <input type="text" id="total_comp" name="total_comp" required>
    </p>
    <p>
        <input type="submit" value="Submit">
    </p>
</form>
<?php
if(isset($_POST["total_comp"])) {
    h2("Result");
    $tbl_cols = array('custid', 'custname', 'sex', 'dob', 'num_complaints');
    $tot_comp = $_POST["total_comp"];
    span("Complaints >= : ".$tot_comp);
    $sql = "select c.*, subquery1.num_complaints from f21_s001_20_customer c, ( select c.custid,count(c.compid) as num_complaints from f21_s001_20_complaint c inner join f21_s001_20_complaint_details cd on c.compid = cd.compid group by c.custid having count(c.compid) >= :p ) subquery1 where c.custid = subquery1.custid";
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
        oci_bind_by_name($stmt, ':p', $tot_comp);
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

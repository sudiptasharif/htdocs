<?php
include_once 'global.php';

define('TBL_EMP', 'F21_S001_20_EMPLOYEE');
define('TBL_CUST', 'F21_S001_20_CUSTOMER');
define('TBL_COMP', 'F21_S001_20_COMPLAINT');
define('TBL_COMP_DTLS', 'F21_S001_20_COMPLAINT_DETAILS');
define('TBL_CUST_LOCS', 'F21_S001_20_CUSTOMER_LOCATIONS');
define('TBL_MOVIE', 'F21_S001_20_MOVIE');
define('TBL_ACTOR', 'F21_S001_20_ACTOR');
define('TBL_CAST', 'F21_S001_20_CAST');
define('TBL_GENRE', 'F21_S001_20_GENRE');
define('TBL_MOVIE_GENRE', 'F21_S001_20_MOVIE_GENRE');
define('TBL_STRM_DTLS', 'F21_S001_20_STREAMING_DETAILS');
define('TBL_PLATFORM', 'F21_S001_20_PLATFORM');
define('TBL_MOVIE_RATING', 'F21_S001_20_MOVIE_RATING');
define('TBL_ACTOR_RATING', 'F21_S001_20_ACTOR_RATING');
define('TBL_GENRE_RATING', 'F21_S001_20_GENRE_RATING');

define('TBL_MOVIE_COLS', array('MovieID', 'Title', 'Overview', 'Budget', 'Revenue', 'ReleaseDate', 'Runtime', 'PosterPath'));
define('TBL_ACTOR_COLS', array('ActorID', 'ActorName', 'PosterPath'));
define('TBL_GENRE_COLS', array('GenreID', 'Genrename'));
define('TBL_CUST_COLS', array('CustID', 'CustName', 'Sex', 'DOB'));
define('TBL_CUST_LOC_COLS', array('CustID', 'City', 'State', 'ZIP'));
define('TBL_EMP_COLS', array('empid', 'empname', 'sex', 'dob'));

function getDBConn() {
    $conn = oci_connect(DB_USERNAME, DB_PASSWORD, DB_CONN_STR);
    return $conn;
}

function freeDBStatement($stmnt) {
    if(!is_null($stmnt)) {
        oci_free_statement($stmnt);
    }
}

function closeDBConn($conn) {
    if(!is_null($conn)) {
        oci_close($conn);
    }    
}

function getDBConnErrorMSg() {
    $m = oci_error();
    return $m['message'];
}

function getOCIErrorMsg($oci_res) {
    $e = oci_error($oci_res);
    return $e['message'];
}

function getTblColsSubSet($target_tbl_cols, $start_index){
    $ans = array();
    if($start_index < count($target_tbl_cols)){
        for($i=$start_index; $i<count($target_tbl_cols); $i++){
            $ans[] = $target_tbl_cols[$i];
        }
    }
    return $ans;
}

function printTable($tbl_name, $tbl_cols) {
    $sql = "SELECT * FROM ".$tbl_name;
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

function insertIntoTable($tbl_name, $tbl_cols, $tbl_col_vals){
    $result = false;
    try{
        $sql = "INSERT INTO ".$tbl_name." (".strtoupper(concatArrayVals($tbl_cols)).") VALUES(".getDummyDBParams(count($tbl_col_vals)).")";
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

        for($i = 0; $i<count($tbl_cols); $i++){
            oci_bind_by_name($stmt, ':p'.($i), $tbl_col_vals[$i]);
        }

        $r = oci_execute($stmt);
        if (!$r) {
            $err = getOCIErrorMsg($stmt);
            h2("Error");
            h3($err);
            die();
        }else {
            $result = true;
        }
        freeDBStatement($stmt);
        closeDBConn($conn);
        return $result;
    } catch(Exception $e) {
        h2("Error");
        h3($e->getMessage());
        freeDBStatement($stmt);
        closeDBConn($conn);
        return $result;
        die();       
    } 
}

/*
Unit Test Secition
*/
$unit_testing = 0;
if($unit_testing == 1) {

}
?>
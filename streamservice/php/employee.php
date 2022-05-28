<?php
include_once 'db_oracle.php';

class Employee {
    public $empName;
    public $sex;
    public $dob;

    function addToDB(){
        $result = false;
        try{
            $sql = "INSERT INTO ".TBL_EMP." (EMPNAME, SEX, DOB)";
            $sql = $sql." VALUES ('".$this->empName."', '".strtoupper($this->sex)."', date '".$this->dob."')";
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

    function isValidSex($val) {
        $val = strtolower($val);
        return ((strcmp($val, 'm') == 0) || (strcmp($val, 'f') ==0));
    }

    function isValidEmpName($val) {
        if(is_numeric(($val))) {
            return false;
        } else if (ctype_alpha(str_replace(' ', '', $val)) === false) {
            return false;
        }else {
            return true;
        }
    }
}
?>
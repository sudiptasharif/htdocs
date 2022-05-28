<?php
    include_once 'employee.php';
    $invalid_sex = null;
    $invalid_name = null;
    $row_add_msg = null;
    if(isset($_POST["empname"]) && isset($_POST["sex"]) && isset($_POST["dob"])) {
        $employee = new Employee();
        $name = $_POST["empname"];
        $sex = $_POST["sex"];
        $dob = $_POST["dob"];
        if($employee->isValidSex($sex) && $employee->isValidEmpName($name)){
            $employee->empName = $name;
            $employee->sex = $sex;
            $employee->dob = date("Y-m-d",strtotime($dob));

            if($employee->addToDB()) {
                $row_add_msg = "Successfully Added Employee";
            } else {
                $row_add_msg = "Failed To Add Employee";
            }
        }
        if(!$employee->isValidSex($sex)){
            $invalid_sex = "Invalid Sex: ".$sex;
        }
        if(!$employee->isValidEmpName($name)) {
            $invalid_name = "Invalid Name: ".$name;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/global.css">
    <title>Add Employee</title>
</head>
<body>
    <h2>Add New Employee</h2>
    <form action="" method="post">
        <p>
            <label for="empname">Employee Name: </label>
            <input type="text" id="empname" name="empname" required>
            <?php
                if(isset($invalid_name)) {
                    span($invalid_name);
                }
            ?>
        </p>
        <p>
            <label for="sex">Sex (M/F):</label>
            <input type="text" id="sex" name="sex" required>
            <?php
                if(isset($invalid_sex)) {
                    span($invalid_sex);
                }
            ?>
        </p>
        <p>
            <label for="dob">Date of Birth (DOB)</label>
            <input type="date" name="dob" id="dob" required>
        </p>
        <p>
            <input type="submit" value="Add New Employee">
        </p>
    </form>
    <?php
        if(isset($row_add_msg)) {
            p($row_add_msg);
        }
    ?>
    <h2>Employee(s)</h2>
    <?php printTable(TBL_EMP, TBL_EMP_COLS);?>
</body>
</html>
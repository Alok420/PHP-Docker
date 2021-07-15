<?php
session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
$db = new DB($conn);

// Syntax for passing arguments in this controller
// 1. where = array("condition"=>"value")
// 2. tbname
// 3. loginid
// 4.file_column with comma(,) seperated like image,pdf,adhar.....

if (isset($_REQUEST["tbname"]) && isset($_REQUEST["loginid"])) {

    $tbname = $_REQUEST["tbname"];
    $loginid = $_REQUEST["loginid"];
    if($loginid !== $_SESSION['loginid']){
        ?>
        <script>
            alert("You are not logged in");
            window.history.back();
        </script>
        <?php
    }
    
    // Getting the value which is the where clause
    $i = 0;
    foreach($_REQUEST as $key => $value){
        if($i == 0){
            $column = $key;
            $colval = $value;
        }
        $i++;
    }
    
    $sql = "DELETE FROM $tbname WHERE $column = ?";
    $info = $db->deleteQuery($sql, $colval);
    
    if(isset($_REQUEST["file_column"]) && !empty($_REQUEST["file_column"])){
        $file_colums = $_REQUEST["file_column"];
        
        if($info[0] == 1){
            $file_colums = explode(",", $file_colums);
            if (count($file_colums) > 0) {
                foreach ($file_colums as $file_col_name) {
                    $sql = "SELECT $file_col_name FROM $tbname WHERE $column = ? ";
                    $file_col_value = $db->selectQuery($sql, $colval)->fetch_assoc()[$file_col_name];
                    $path = "../img/" . $tbname ."/".$file_col_value;
                    $db->deleteFile($path);
                }
            }

        }
    }
    if ($info[0] == 1) {
        ?>
        <script>
            alert("Record Deleted successfully");
            window.history.back();
        </script>
        <?php
        // $db->sendBack($_SERVER);
    } else {
        echo $info[0];
        echo $info[1];
        echo $info[2];
    }
} else {
    echo "loginid or table name is not set";
}


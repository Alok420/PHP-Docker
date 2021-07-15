<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
    $user_api_key = $_POST["api_key"];
    $loginid = $_POST["loginid"];
    $users = $db->select("user", "*", array("id" => $loginid));
    $user = $users->fetch_assoc();
    $db_user_api_key = $user["api_key"];
    if ($db_user_api_key == $user_api_key) {
        
    } else {
        echo json_encode(array("error" => "Invalid API Key"));
        die();
    }
} else {
    echo json_encode(array("error" => "Invalid API Key Or Logged in user id"));
    die();
}
$tbname = "service_requests";

//SELECT * FROM `service_requests` WHERE `userdate` >= '2020-09-04' AND `userdate` < '2020-09-08' AND`status` LIKE 'pending'


    $paid=$conn->query("select COUNT(*) as `countpaid` from $tbname WHERE `status` LIKE 'Paid' AND `vendorid` LIKE '$loginid'")->fetch_assoc()['countpaid'];

    $unpaid=$conn->query("select COUNT(*) as `countpaid` from $tbname WHERE `status` LIKE 'Unpaid' AND `vendorid` LIKE '$loginid'")->fetch_assoc()['countpaid'];
    
    $workInProgress=$conn->query("select COUNT(*) as `countpaid` from $tbname WHERE `status` LIKE 'Accepted' OR  `status` LIKE 'Verified' AND `vendorid` LIKE '$loginid'")->fetch_assoc()['countpaid'];

    $data=array(status=>"success", "paid"=>$paid, "unpaid"=>$unpaid, "workInProgress"=>$workInProgress);

$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;


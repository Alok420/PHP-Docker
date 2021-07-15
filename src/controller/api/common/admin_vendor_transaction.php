<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
    $user_api_key = $_POST["api_key"];
    $loginid = $_POST["loginid"];
    $transaction_type = $_POST["transaction_type"];
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
$tbname = $_REQUEST["tbname"];
$todate = $_REQUEST["todate"];
$fromdate = $_REQUEST["fromdate"];
//$status = $_REQUEST["status"];

//SELECT * FROM `service_requests` WHERE `creation_date` >= '2020-09-04' AND `userdate` < '2020-09-08' AND`status` LIKE 'pending'
//select * from admin_vendor_transaction WHERE date(`creation_date`) BETWEEN date('2020-09-01') AND date('2020-09-10') AND `user_id` LIKE '4' AND `transaction_type` LIKE 'Credit'

    $query = "select * from $tbname WHERE date(`creation_date`) BETWEEN date('$fromdate') AND date('$todate') AND `user_id` LIKE '$loginid' AND `transaction_type` LIKE '$transaction_type'";

    if($todate == ''){
        $query = "select * from $tbname WHERE `user_id` LIKE '$loginid' AND `transaction_type` LIKE '$transaction_type'";

    } 
    else if($todate == $fromdate) {
        $query = "select * from $tbname WHERE `creation_date` BETWEEN date('$fromdate') AND date('$todate') AND `user_id` LIKE '$loginid' AND `transaction_type` LIKE '$transaction_type'";
        
    }
    
    //echo $query;

$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    
    $data[$i] = $row;
    // $vehicle=$conn->query("select * from vehicles where id=".$row["vehicles_id"])->fetch_assoc();
    // $vehicle_model=$conn->query("select * from vehicle_model where id=".$vehicle["vehicle_model_id"])->fetch_assoc();
    // $vehicle_make=$conn->query("select * from vehicle_make where id=".$vehicle_model["vehicle_make_id"])->fetch_assoc();
    // $vehicle_type=$conn->query("select * from vehicle_type where id=".$vehicle_make["vehicle_type_id"])->fetch_assoc();
    // $user=$conn->query("select id, name, contact, full_address, city, pin, image from user where id=".$row["userid"])->fetch_assoc();
    // $data[$i]=array("service"=>$row, "vehicle"=>$vehicle, "vehicle_model"=>$vehicle_model,"vehicle_make"=>$vehicle_make,"vehicle_type"=>$vehicle_type, "user"=> $user);
    $i++;
    
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

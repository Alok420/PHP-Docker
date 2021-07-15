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
$tbname = $_REQUEST["tbname"];
$todate = $_REQUEST["todate"];
$fromdate = $_REQUEST["fromdate"];
$status = $_REQUEST["status"];

//SELECT * FROM `service_requests` WHERE `userdate` >= '2020-09-04' AND `userdate` < '2020-09-08' AND`status` LIKE 'pending'

    $query = "select * from $tbname WHERE `userdate` >= '$fromdate' AND `userdate` < '$todate' AND `status` LIKE '$status'";

    if($todate == ''){
        $query = "select * from $tbname WHERE `status` LIKE '$status' ";
    } 
    else if($todate == $fromdate) {
        $query = "select * from $tbname WHERE `userdate` LIKE '$todate' AND `status` LIKE '$status' ";
    }
    
    //echo $query;

$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    
    //$data[$i] = $row;
    $vehicle=$conn->query("select * from vehicles where id=".$row["vehicles_id"])->fetch_assoc();
    $vehicle_model=$conn->query("select * from vehicle_model where id=".$vehicle["vehicle_model_id"])->fetch_assoc();
    $vehicle_make=$conn->query("select * from vehicle_make where id=".$vehicle_model["vehicle_make_id"])->fetch_assoc();
    $vehicle_type=$conn->query("select * from vehicle_type where id=".$vehicle_make["vehicle_type_id"])->fetch_assoc();
    $service_type=$conn->query("select * from services where id=".$row["services_id"])->fetch_assoc();
    $user=$conn->query("select id, name, contact, full_address, city, pin, image from user where id=".$row["userid"])->fetch_assoc();
    $data[$i]=array("service"=>$row, "service_type"=>$service_type, "vehicle"=>$vehicle, "vehicle_model"=>$vehicle_model,"vehicle_make"=>$vehicle_make,"vehicle_type"=>$vehicle_type, "user"=> $user);
    $i++;
    
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

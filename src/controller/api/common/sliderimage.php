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
$tbname = "slides";

//SELECT * FROM `service_requests` WHERE `userdate` >= '2020-09-04' AND `userdate` < '2020-09-08' AND`status` LIKE 'pending'

    $result=$conn->query("SELECT * FROM `slides` where status = 'enable'");
    
$data=array();

    while ($row = $result->fetch_assoc()) {
    //$data[$i] = $row;
     $data[] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;


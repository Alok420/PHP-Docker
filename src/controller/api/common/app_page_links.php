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
$query = "select * from app_page_links";
$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    $data['links'][$i] = $row;
    $i++;
}
//Fetching total app of the user
$userid = $_POST['loginid'];
$total_app = $db->select("user", "total_app", array("id"=>$userid))->fetch_assoc();
$data['total_app'] = $total_app['total_app'];

//Fetching Latest App version
$sort = "id desc";
$app_version = $db->select("app_version","*", array(),$sort)->fetch_assoc();
$data['app_version'] = $app_version;

$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

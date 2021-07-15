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
$user_id = $_REQUEST["id"];
$wallet_type = $_REQUEST["wallet_type"];

$query = "select * from $tbname where user_id = $user_id and wallet_type = '$wallet_type' order by id desc";
$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    // Coverting india zone by adding +5 hours 30 minutes
    $row['created_at'] = date("F j, Y, g:i a", strtotime('+5 hours 30 minutes', strtotime($row['created_at'])));
    $data[$i] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

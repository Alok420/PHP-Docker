<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
if (isset($_REQUEST["api_key"]) && isset($_REQUEST["loginid"])) {
    $user_api_key = $_REQUEST["api_key"];
    $loginid = $_REQUEST["loginid"];
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

if (isset($_REQUEST["id"])) {
    $query = "select * from " . $_REQUEST['tbname'] . " where id=" . $_REQUEST['id'];
    $result = $conn->query($query);
   
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i] = $row;
        $i++;
    }
    $string = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $string;
}
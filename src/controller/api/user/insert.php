<?php
session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$auto = array();
$name = $_POST["name"];
$key = $db->apiKey($name);
$userid = $db->userId($name);

array_push($auto, $key);
array_push($auto, $userid);
$_POST["api_key"] = $key;
$_POST["userid"] = $userid;

$_POST["type"] = "user";
$_POST["tb_name"] = "user";
$useridExist = "yes";
while ($useridExist != "no") {
    $data = $db->select($_POST["tb_name"], "*", array("userid" => $_POST["userid"]));
    if ($data->num_rows > 0) {
        $useridExist = "yes";
        $_POST["userid"] = $db->userId($name);
    } else {
        $useridExist = "no";
    }
}
$info = array();
if ($useridExist == "no") {
    if ($db->exist($_POST["tb_name"], array("email" => $_POST["email"])) == "no") {
        $info = $db->insert($_POST, $_POST["tb_name"]);
        if ($info[0] == 1) {
            $info["status"] = "success";
            $info["key"] = $key;
            $info["uniqueid"] = $userid;
            $info["message"] = "Data  saved";
            $info["userid"] = $_SESSION["recentinsertedid"];
            echo json_encode($info);
        } else if ($info[0] == 0) {
            $info["status"] = "failed";
            $info["message"] = "Data not saved ! server error";
            echo json_encode($info);
        }
    } else {
        $info["status"] = "failed";
        $info["message"] = "This email is already exist";
        echo json_encode($info);
    }
}

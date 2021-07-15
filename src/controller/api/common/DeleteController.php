<?php

include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$info = array();
if (isset($_POST["loginid"])) {
    if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
        $user_api_key = $_POST["api_key"];
        $loginid = $_SESSION["loginid"];
        $users = $db->select("user", "*", array("id" => $loginid));
        $user = $users->fetch_assoc();
        $db_user_api_key = $user["api_key"];
        if ($db_user_api_key == $user_api_key) {
            
        } else {
            echo json_encode(array("error" => "Invalid API Key"));
        }
    } else {
        echo json_encode(array("error" => "Invalid API Key Or Logged in user id"));
    }
    if (isset($_REQUEST["id"]) && isset($_REQUEST["tbname"])) {
        
        if (isset($_REQUEST["img_col"]) && isset($_REQUEST["path"])) {
            
        }

        $db->delete($_REQUEST["id"], $_REQUEST["tbname"]);
//        $sql = "delete from " . $_REQUEST["tbname"] . " where id=" . $_REQUEST["id"];
        if ($conn->query($sql)) {
            $info["status"] = "success";
            $info["message"] = "data removed";
            echo json_encode($info);
        } else {
            $info["status"] = "failed";
            $info["message"] = "data not removed";
            echo json_encode($info);
        }
    } else {
        $info["status"] = "failed";
        $info["message"] = "user id or post id is not set";
        echo json_encode($info);
    }
} else {
    echo json_encode(array("error" => "Invalid loginid"));
}


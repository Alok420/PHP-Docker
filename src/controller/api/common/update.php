<?php
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Mail.php';
$db = new DB($conn);
//$info = array();
$location = "../img";


$_POST['updated_at'] = date("Y-m-d H:i:s");

$_POST['updated_date'] = date("Y-m-d H:i:s");

if (isset($_POST["loginid"])) {
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
    $tbname = $_POST["tbname"];
    if ($tbname == "user") {
        $location = "../../../img/user/";
    } else if ($tbname == "task") {
        $location = "../../../img/task/";
    } else if ($tbname == "branch") {
        $location = "../img/branch/";
    } else if ($tbname == "admin") {
        $location = "../img/admin/";
    } else if ($tbname == "ads") {
        $location = "../img/ads/";
    } else if ($tbname == "user_profile") {
        $location = "../img/user_profile/";
    }
    if (isset($_POST["id"])) {
        $recentinsertedid = $_POST["id"];
    }
    unset($_POST["id"]);
    unset($_POST["tbname"]);
    unset($_POST["loginid"]);
    unset($_POST["api_key"]);

    $info = $db->update($_POST, $tbname, $recentinsertedid);
    if ($info[0] == 1) {
        if (count($_FILES) > 0) {
            $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,PNG,JFIF,jpg,png,jfif,jpeg,JPEG");
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $recentinsertedid;
            $data = $db->select($tbname, "*", array("id"=>$recentinsertedid))->fetch_assoc();
            $return["data"] = $data;
            echo json_encode($return);
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $recentinsertedid;
            $data = $db->select($tbname, "*", array("id"=>$recentinsertedid))->fetch_assoc();
            $info["data"] = $data;
            echo json_encode($info);
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
        echo json_encode($info);
    }
} else {
    $info = array();
    $info["status"] = "failed";
    $info["message"] = "Log in first";
    echo json_encode($info);
}


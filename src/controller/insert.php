<?php

session_start();

include '../Config/Connection.php';
include '../Config/DB.php';

$db = new DB($conn);
$_POST["name"] = isset($_POST["name"]) ? $_POST["name"] : "NA";
if (isset($_POST["api_key"]) && isset($_SESSION["loginid"])) {
    $user_api_key = $_POST["api_key"];
    $loginid = $_SESSION["loginid"];
    $users = $db->select("user", "*", array("id" => $loginid));
    $user = $users->fetch_assoc();
    $db_user_api_key = $user["api_key"];
    if ($db_user_api_key == $user_api_key) {
        $_POST["role"] = isset($_POST["role"]) ? $_POST["role"] : "user";
    } else {
        die("<h2>API key is invalid</h2>");
    }
} else {
    $_POST["role"] = "user";
}
$location = "../img/user/";
$tbname = isset($_POST["tbname"]) ? $_POST["tbname"] : "user";
$_POST["role"] = isset($_POST["role"]) ? $_POST["role"] : "user";

if ($tbname == "user") {
    $location = "../img/user/";
} else if ($tbname == "products") {
    $location = "../img/products/";
}
unset($_POST["tbname"]);

$auto = array();
$name = $_POST["name"];
$key = $db->apiKey($name);
$userid = $db->userId($name);
array_push($auto, $key);
array_push($auto, $userid);
$_POST["api_key"] = $key;
$_POST["userid"] = $userid;
$_POST["password"] = isset($_POST["password"]) ? $_POST["password"] : $userid;

$useridExist = "yes";
while ($useridExist != "no") {
    $data = $db->select($tbname, "*", array("userid" => $_POST["userid"]));
    if ($data->num_rows > 0) {
        $useridExist = "yes";
        $_POST["userid"] = $db->userId($name);
    } else {
        $useridExist = "no";
    }
}
$info1 = "";
if (isset($_POST["info"])) {
    $info1 = $_POST["info"];
}
$info = array();
if ($useridExist == "no") {
    if ($db->exist($tbname, array("email" => $_POST["email"])) == "no") {
        $info = $db->insert($_POST, $tbname);
        //var_dump($info);
        // if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {
        if (isset($_SESSION["recentinsertedid"])) {
            $recentinsertedid = $_SESSION["recentinsertedid"];
        }
        if ($info[0] == 1) {
            if (count($_FILES) > 0) {
                $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,PNG,JFIF,jpg,png,jfif");
                $return = array();
                $return["status"] = "success";
                $return["message"] = "Data and image saved";
                $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
                //        var_dump($return);
                $db->sendBack($_SERVER, "?" . http_build_query($return));
            } else {
                $info = array();
                $info["status"] = "success";
                $info["message"] = "Data saved";
                $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
                //        var_dump($info);
                $db->sendBack($_SERVER, "?" . http_build_query($info));
            }
        } else if ($info[0] == 0) {

            $info["status"] = "failed";
            $info["message"] = "Data not saved";
            //    var_dump($info);
            $db->sendBack($_SERVER, "?" . http_build_query($info));
        }
    } else {
        $info["0"] = "0";
        $info["status"] = "failed";
        $info["message"] = "This email is already exist";
        $db->sendBack($_SERVER, "?" . http_build_query($info));
    }
}

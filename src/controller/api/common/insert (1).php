<?php

session_start();

include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Configuration.php';

$location = "../../../img/user/";
$tbname = $_POST["tbname"];

if ($tbname == "user") {
    $location = "../../../img/user/";
}

unset($_POST["tbname"]);
$_POST["role"] = isset($_POST["role"]) ? $_POST["role"] : "user";
$db = new DB($conn);
$auto = array();
$name = $_POST["name"];
$key = $db->apiKey($name);
$userid = $db->userId($name);
array_push($auto, $key);
array_push($auto, $userid);
$_POST["api_key"] = $key;
$_POST["userid"] = $userid;
//------------------important these lines are just for auto doctor system remove it for any other project
$_POST["email"] = isset($_POST["email"]) ? $_POST["email"] : "";
$_POST["email"] = $_POST["email"] == "" ? $userid : $_POST["email"];


$tbname = "user";
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
$emptyarray = array();

if (empty($_POST["name"])) {
    $emptyarray["name"] = "Name can not be empty";
}
if (empty($_POST["email"])) {
    $emptyarray["email"] = "Email can not be empty";
}
if (empty($_POST["contact"])) {
    $emptyarray["contact"] = "Contact can not be empty";
}
if (empty($_POST["password"])) {
    $emptyarray["password"] = "Password can not be empty";
}

if (count($emptyarray) == 0) {

    if ($useridExist == "no") {

        if ($db->exist($tbname, array("email" => $_POST["email"])) == "no" && $db->exist($tbname, array("contact" => $_POST["contact"])) == "no") {
            $info = $db->insert($_POST, $tbname);
// echo json_encode($info);
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
                    echo json_encode($return);
//                $db->sendBack($_SERVER, "?" . http_build_query($return));
                } else {
                    $info = array();
                    $info["status"] = "success";
                    $info["message"] = "Data  saved";
                    $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
                    echo json_encode($info);
//                $db->sendBack($_SERVER, "?" . http_build_query($info));
                }
            } else if ($info[0] == 0) {

                $info["status"] = "failed";
                $info["message"] = "Data not saved";
                echo json_encode($info);
//            $db->sendBack($_SERVER, "?" . http_build_query($info));
            }
        } else {
            $info["0"] = "0";
            $info["status"] = "failed";
            $info["message"] = "This userid or contact is already exist";
            echo json_encode($info);
//        $db->sendBack($_SERVER, "?" . http_build_query($info));
        }
    }
} else {
    echo json_encode($emptyarray);
}

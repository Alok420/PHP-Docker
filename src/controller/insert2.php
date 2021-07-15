<?php

session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
$db = new DB($conn);
$location = "../img";
$curr_date = date("Y-m-d");

$_POST['created_at'] = date("Y-m-d H:i:s");
$_POST['updated_at'] = date("Y-m-d H:i:s");
$_POST['created_date'] = date("Y-m-d H:i:s");
$_POST['updated_date'] = date("Y-m-d H:i:s");

if (isset($_REQUEST["return_operator"])) {
    $return_operator = $_REQUEST["return_operator"];
} else {
    $return_operator = "?";
}
if (isset($_SESSION["loginid"])) {
    if (isset($_POST["api_key"]) && isset($_SESSION["loginid"])) {
        $user_api_key = $_POST["api_key"];
        $loginid = $_SESSION["loginid"];
        $users = $db->select("user", "*", array("id" => $loginid));
        $user = $users->fetch_assoc();
        $db_user_api_key = $user["api_key"];
        if ($db_user_api_key == $user_api_key) {
            
        } else {
            die("<h2>API key is invalid</h2>");
        }
    } else {
        die("<h2>Login first</h2>");
    }
    
    
    $tbname = $_POST['tbname'];

    if(count($_POST) == 0){     //checking if we have only file to upload withoud any post date
        if(count($_FILES) > 0){
            $upload_file = $db->uploadFile($_FILES, $tbname);
            if($upload_file[0] == 1){
                $return = array();
                $return["status"] = "success";
                $return["message"] = "Image uploaded successfully";
                $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
        
                $db->sendBack($_SERVER, $return_operator . http_build_query($return));
            }
        }
    }
    
    


    $info = $db->insertQuery($_POST, $tbname);
//var_dump($info);
// if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {
    if (isset($_SESSION["recentinsertedid"])) {
        $recentinsertedid = $_SESSION["recentinsertedid"];
    }
    if ($info[0] == 1) {
        
        if (count($_FILES) > 0) {
            
            $return = $db->uploadFile($_FILES, $tbname, $recentinsertedid);
            $return = array();
            $return["status"] = "success";
            $return["message"] = "Data Saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
       //var_dump($return);
             $db->sendBack($_SERVER, $return_operator . http_build_query($return));
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
        
            $db->sendBack($_SERVER, $return_operator . http_build_query($info));
            //var_dump($info);
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
//    var_dump($info);  
        $db->sendBack($_SERVER, $return_operator . http_build_query($info));
    }
} else {
    echo 'Log in first';
}    
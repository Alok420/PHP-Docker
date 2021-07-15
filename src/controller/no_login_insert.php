<?php

session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
require '../vendor/autoload.php';
include '../Config/Mail.php';
//error_reporting(2);
$db = new DB($conn);
$location = "../img";
$mail = new Mail();
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
    
    $tbname = $_POST['tbname'];

 //==========================Don't remove these lines ================================================//
 $unset_status = $db->unsetExtraColumn($_POST, $tbname);
    
 //==========================================================================//
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
   
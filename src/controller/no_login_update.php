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
error_reporting(0);

$_POST['updated_at'] = date("Y-m-d H:i:s");

    if (isset($_POST["id"])) {
        $recentinsertedid = $_POST["id"];
    }
    $tbname = $_POST["tbname"];
    
    //==========================Don't remove these lines ================================================//
    $unset_status = $db->unsetExtraColumn($_POST, $tbname);
    
    //==========================================================================//
   
    if(count($_POST) == 0){     //checking if we have only file to upload withoud any post data
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
    

    
    $info = $db->updateQuery($_POST, $tbname, $recentinsertedid);
    
    if ($info[0] == 1) {
        if (count($_FILES) > 0) {
            
            $return = $db->uploadFile($_FILES, $tbname, $recentinsertedid);
        var_dump($return);die;
//        $return = array();
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
            $db->sendBack($_SERVER, $_REQUEST["info"]);
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $recentinsertedid;
//        var_dump($info);
            $db->sendBack($_SERVER, $_REQUEST["info"]);
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
//    var_dump($info);

        $db->sendBack($_SERVER, "?info=Data not saved");
    }

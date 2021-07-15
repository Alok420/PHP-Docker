<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Mail.php';
//error_reporting(2);
$db = new DB($conn);
$info = array();
$location = "../img";  

$_POST['created_at'] = date("Y-m-d H:i:s");
$_POST['updated_at'] = date("Y-m-d H:i:s");
$_POST['created_date'] = date("Y-m-d H:i:s");
$_POST['updated_date'] = date("Y-m-d H:i:s");

//var_dump($_POST);
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

    unset($_POST["api_key"]);
    unset($_POST["loginid"]);
  
    $tbname = $_POST["tbname"];
    if ($tbname == "user") {
        $location = "../../../img/user/";
    } else if ($tbname == "task") {
        $location = "../../../img/task/";
    } else if ($tbname == "branch") {
        $location = "../../../img/branch/";
    } else if ($tbname == "admin") {
        $location = "../../../img/admin/";
    } else if ($tbname == "ads") {
        $location = "../../../img/ads/";
    } else if ($tbname == "user_profile") {
        $location = "../../../img/user_profile/";
    } else if ($tbname == "mobile_track") {
        $location = "../../../../img/mobile_track/";
    }

    unset($_POST["tbname"]);
    
    if($tbname == "emergency_number") {
        $query = "select * from emergency_number where user_id = ".$loginid;
        $rescheck=$conn->query($query);
         if (mysqli_num_rows($rescheck)>0) {
            $query = "UPDATE `emergency_number` SET `number`='".$_POST["number"]."',`number2`='".$_POST["number2"]."' WHERE user_id =".$loginid;
            $rescheck=$conn->query($query);
            $return = array();
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] =  0;
            echo json_encode($return);
            exit();
        } else {
             $info = $db->insert($_POST, $tbname);
             if (isset($_SESSION["recentinsertedid"])) {
                $recentinsertedid = $_SESSION["recentinsertedid"];
             }
             $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
            echo json_encode($info);
            exit();
    }
             
         }
    else {
        $info = $db->insert($_POST, $tbname);
    }
    

    if (isset($_SESSION["recentinsertedid"])) {
        $recentinsertedid = $_SESSION["recentinsertedid"];
    }
    
    if ($info[0] == 1) {
        if (count($_FILES) > 0) {
            $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,JPEG,PNG,JFIF,PDF,DOCX,mp4,jpg,jpeg,png,jfif,pdf,docx,3gp,ogg");
            
            $response = null;
            if($tbname == "mobile_track") {
                
                include 'send_sms.php';
                
                // $from = 'RANSTP';
                // $sms_text = urlencode('Someone tried to open your phone '.$contacts.' https://maps.google.com/?q='.$_POST["lat"].','.$_POST["lng"].' http://phoneraksha.com/img/mobile_track/'.$_POST["name"]);
                
                // //echo $sms_text;
                
                // $api_key = '2d76f827dad49dcc33469816ae37e2';
                        
                // $api_url = "http://login.bulksmslaunch.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$api_key."&message=".$sms_text."&senderId=RANSTP&routeId=1&mobileNos=".$_POST["number1"].",".$_POST["number2"]."&smsContentType=english";
                
                // //echo $api_url;
                
                // $response = file_get_contents($api_url);
            }
            
            $return = array();
            $return["status"] = "success";
            $return["response"] = $result;
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
            echo json_encode($return);
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
            echo json_encode($info);
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
        echo json_encode($info);
    }
} else {
    $info["status"] = "failed";
    $info["message"] = "Log in first";
    echo json_encode($info);
}


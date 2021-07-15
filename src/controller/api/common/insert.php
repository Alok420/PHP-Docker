<?php

session_start();

include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Configuration.php';
include '../../../Config/Mail.php';
include '../../../vendor/autoload.php';

$db = new DB($conn);
$mail = new Mail();



$location = "../../../img/user/";
$tbname = $_POST["tbname"];

$sort = "id desc";
$reward_settings = $db->select("reward_settings", "*", array(), $sort)->fetch_assoc();

$settings = $db->select("settings", "launching_date" )->fetch_assoc();
$launching_date = $settings['launching_date'];

$sql = "select count(id) as total from user where (role='user' and created_at > '$launching_date')";
$prime_users = mysqli_query($conn, $sql)->fetch_assoc()['total'];
if($prime_users <= 100){
    $_POST['prime'] = 1;
}

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

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$contact = $_POST['contact'];

$role = $_POST['role'];
$name = $_POST['name'];

if (count($emptyarray) == 0) {

    if ($useridExist == "no") {

        if ($db->exist($tbname, array("email" => $_POST["email"])) == "no" && $db->exist($tbname, array("contact" => $_POST["contact"])) == "no") {
           
            // Sending Mail
            
                $to = $email;
                $subject = "Registration Successfully";
                $txt = "Thank You for registering with us. Your Username - ".$contact."  and password - ".$password."  ";
                $txt = wordwrap($txt, 70);
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: info@phoneraksha.com' . "\r\n";
                // $headers .= 'Cc: myboss@example.com' . "\r\n";
            
               $sent_mail = $mail->sendMail($to, $subject, $txt);
               
               if($sent_mail){
                    $_POST["email_delivery_report"] = 1;
                } 
            
         
            //Only for phoneraksha
            $check_device = $db->select("user", "*", array("device_no"=>$_POST['device_no']));
            if($check_device->num_rows > 0){
                
                $return["status"] = "failed";
                $return["message"] = "Your device already registered with Us !";
                $return["recentinsertedid"] = 0;
                echo json_encode($return);
                die;
            }
            $info = $db->insert($_POST, $tbname);
// echo json_encode($info);
// if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {
            if (isset($_SESSION["recentinsertedid"])) {
                $recentinsertedid = $_SESSION["recentinsertedid"];
                
                
                // Creating a record with this new user id in wallets
                if($role == "user"){
                   
                    $data1 = array("user_id"=>$recentinsertedid);
                    $info = $db->insert($data1, 'wallets');
                }
                // Creating and inserting Promocode  --------------- It is only for phoneraksha
                $twoDigName = substr($name, 0, 2);
                $promocode = "PR".$twoDigName.$recentinsertedid;
                $sql = "UPDATE `user` SET `promocode` = '$promocode' WHERE `user`.`id` = $recentinsertedid";
                $result = mysqli_query($conn, $sql);
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
                    // $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
                    echo json_encode($info);
//                $db->sendBack($_SERVER, "?" . http_build_query($info));
                }
            } else if ($info[0] == 0) {

                $info["status"] = "failed";
                $info["message"] = "Data not saved";
                $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
                echo json_encode($info);
//            $db->sendBack($_SERVER, "?" . http_build_query($info));
            }
        } else {
            
            $info["status"] = "failed";
            $info["message"] = "This userid or contact is already exist";
            $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
            echo json_encode($info);
//        $db->sendBack($_SERVER, "?" . http_build_query($info));
        }
    }
} else {
    echo json_encode($emptyarray);
}

<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$myObj = (object)array();
//$role = $_POST["colval"];

$data = array();

if($_POST["sms"] == 1){
    
    $query = "SELECT * FROM `user` where contact =".$_POST["mobile"];
    //echo $query;
$rescheck = $conn->query($query);
    if (mysqli_num_rows($rescheck)>0) {
        
        
            $fourdigitrandom = rand(1000,9999);
$contacts = $_REQUEST["mobile"];
$from = 'RANSTP';
$sms_text = urlencode('Hello Your OTP For Phone Raksha Login is '.$fourdigitrandom);


$api_key = '2d76f827dad49dcc33469816ae37e2';
        
$api_url = "http://login.bulksmslaunch.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$api_key."&message=".$sms_text."&senderId=RANSTP&routeId=1&mobileNos=".$contacts."&smsContentType=english";
$response = file_get_contents($api_url);
//curl_close($ch);
//echo $response;
    
$myObj->message = "success";
$myObj->otp=$fourdigitrandom;
$myObj->mobile=$_REQUEST["mobile"];
$myJSON = json_encode($myObj);
echo $myJSON;
exit();
        

} else {
    $myObj->message = "This number is not registered";
$myJSON = json_encode($myObj);
echo $myJSON;
exit();
}
}

else if($_POST["sms"] == 2){
        $query = "SELECT * FROM `user` where contact =".$_REQUEST["mobile"];
$rescheck=$conn->query($query);
 if (mysqli_num_rows($rescheck)>0) {
          $myObj->message = "This number is already registered";
$myJSON = json_encode($myObj);
echo $myJSON;
exit();
 } else {
     
          $fourdigitrandom = rand(1000,9999);
$contacts = $_REQUEST["mobile"];
$from = 'RANSTP';
$sms_text = urlencode('Hello Your OTP For Phone Raksha Registration is '.$fourdigitrandom);


$api_key = '2d76f827dad49dcc33469816ae37e2';
        
$api_url = "http://login.bulksmslaunch.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$api_key."&message=".$sms_text."&senderId=RANSTP&routeId=1&mobileNos=".$contacts."&smsContentType=english";
$response = file_get_contents($api_url);
//curl_close($ch);
//echo $response;
    
$myObj->message = "success";
$myObj->otp=$fourdigitrandom;
$myObj->mobile=$_REQUEST["mobile"];
$myJSON = json_encode($myObj);
echo $myJSON;
exit();

 }
}

else if($_POST["sms"] == 3){
        $query = "SELECT ".$_REQUEST["type"]." FROM `order_request` where id =".$_REQUEST["order_request_id"];
$rescheck=$conn->query($query);
 if (mysqli_num_rows($rescheck)>0) {
     
          
      while($row = $rescheck->fetch_assoc()) {
          $fourdigitrandom = $_REQUEST["otp"];
          
$contacts = ($conn->query("SELECT contact FROM `user` where id =".$row[$_REQUEST["type"]]))->fetch_assoc()["contact"];
$from = 'RANSTP';
$sms_text = urlencode('Hello Your OTP For Phone Raksha Verification is '.$fourdigitrandom);


$api_key = '2d76f827dad49dcc33469816ae37e2';
    
$api_url = "http://login.bulksmslaunch.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$api_key."&message=".$sms_text."&senderId=RANSTP&routeId=1&mobileNos=".$contacts."&smsContentType=english";
$response = file_get_contents($api_url);
//curl_close($ch);
//echo $response;

$myObj->message = "success";
$myObj->otp=$fourdigitrandom;
$myObj->mobile=$contacts;
$myJSON = json_encode($myObj);
echo $myJSON;
exit();
      }

 } else {


 }
}

$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;
?>
 <?php
 
$contacts = $_POST["mobile"] ? $_POST["mobile"] : 9621831353;
$sender ='GRMPAY';
$mob = $_POST["number1"].",".$_POST["number2"];
$auth='D!~6734iKu7D0g3Ry';
$_POST["name"] = $_POST["name"] ? $_POST["name"] :"abcd.jpg";
$_POST["lat"] = $_POST["lat"] ? $_POST["lat"] : "";
$_POST["lng"] = $_POST["lng"] ? $_POST["lng"] : "";

$msg = urlencode('Someone tried to open your phone '.$contacts.' https://maps.google.com/?q='.$_POST["lat"].','.$_POST["lng"].' http://phoneraksha.com/img/mobile_track/'.$_POST["name"].' Vedanjali Wellness Pvt Ltd');


$url = 'https://api.datagenit.com/sms?auth='.$auth.'&msisdn='.$mob.'&senderid='.$sender.'&message='.$msg.'';  // API URL

$result = SendSMS($url);  // call function that return response with code
// echo $result;

//function define
function SendSMS($hostUrl){
    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $hostUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // change to 1 to verify cert
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
$result = curl_exec($ch);
return $result;
} 
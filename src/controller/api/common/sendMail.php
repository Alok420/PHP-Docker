<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Mail.php';

$db = new DB($conn);
//include '../../../Config/SMS.php';

$mail = new Mail();
//$sms=new SMS();
$db->insert($_REQUEST, "subscription");
//$contact=isset($_REQUEST["contact"])?$_REQUEST["contact"]:"";
//$name=isset($_REQUEST["name"])?$_REQUEST["name"]:"";
//$message=isset($_REQUEST["message"])?$_REQUEST["message"]:"";
$body = "Thanks for subscibing we will notify for new posts.";
//echo $body;
echo $mail->sendMail($_REQUEST["email"], "Subscription successfull", $body);

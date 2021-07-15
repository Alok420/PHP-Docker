<?php
session_start();
include 'Config/Connection.php';
include 'Config/DB.php';
$db = new DB($conn);
$user="";
if (isset($_SESSION["loginid"])) {
	$users=$db->select("user","*",array("id"=>$_SESSION["loginid"]));
	$user=$users->fetch_assoc();
	define("USER", $user);
}

?>
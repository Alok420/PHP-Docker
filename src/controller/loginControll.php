<?php

session_start();
include '../Config/Connection.php';
include '../Config/DB.php';

$db = new DB($conn);
//$db->showInTable("user");
$info = $db->login($_POST["userid"], $_POST["password"], $_POST["tbname"]);
//var_dump($info);
if ($info["status_number"] == 1) {
    $_SESSION["conn"] = $conn;
    if ($info["role"] == "user") {
        $db->sendTo("../user/index.php");
    } else if ($info["role"] == "admin") {
        $db->sendTo("../admin/index.php");
    }
} else {
    $db->sendBack($_SERVER, "?" . http_build_query($info));
}
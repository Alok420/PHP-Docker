<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $result=$conn->query("UPDATE `user` SET `password` = '".$pass."' where `contact` = '".$_POST["contact"]."'");
    
$data=array();

    if ($result === TRUE) {
        
     $string = json_encode(array("message"=>"success"), JSON_UNESCAPED_SLASHES);
    echo $string;
    exit();
} else {
    $string = json_encode(array("message"=>"failed"), JSON_UNESCAPED_SLASHES);
    echo $string;
    exit();
}



<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

$user_id = $_POST["userid"];
$timeRand = time()."_".rand();
$info = $db->login($_POST["userid"], $_POST["password"], $_POST["tbname"]);
if ($info["status_number"] == 1) {
    
    // This only for Briefn
    $login_id = $timeRand.$user_id;
    $sql = "UPDATE `user` SET `session_id` = '$login_id' WHERE `user`.`contact` = $user_id";
    mysqli_query($conn, $sql);
    
    $details = $db->select("user", "*", array("contact"=>"$user_id"))->fetch_assoc();
    
    $sql = "SELECT * FROM `payments` WHERE user_id = $user_id AND status = 'TXN_SUCCESS'";
    
    $check_payment = mysqli_query($conn, $sql);
    if($check_payment->num_rows > 0){
        $details["payment_done"] = "yes";
    }else{
        $details["payment_done"] = "no";
    }
    
    
    $info['profile'] = $details;
    $info["status"] = "success";
    
    echo json_encode($info);
} else {
    $info["status"]="failed";
    
    echo json_encode($info);
}

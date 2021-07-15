<?php 
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$details = array();
if(isset($_REQUEST["user_id"])){ 
    $user_id = $_REQUEST["user_id"];
$sql = "SELECT * FROM `payments` WHERE user_id = $user_id AND status = 'TXN_SUCCESS'";
    
    $check_payment = mysqli_query($conn, $sql);
    if($check_payment->num_rows > 0){
        $details["payment_done"] = "yes";
    }else{
        $details["payment_done"] = "no";
    }
}else{
    $details["message"] = "User id can't be empty";
}

echo json_encode(array($details));
die;
?>
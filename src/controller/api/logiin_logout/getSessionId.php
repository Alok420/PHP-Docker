<?php 
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$data = array();
if(isset($_REQUEST['user_id'])){
    $user_id = $_REQUEST['user_id'];
    $password = $_REQUEST['password'];
    $device_no = $_REQUEST['device_no'];
    
    $user = $db->select("user", "*", array("id"=>$user_id))->fetch_assoc();
    
    $hash = $user['password'];
    if ($password != $hash){
        $data['status'] = 0;
        $data['message'] = "Your password is changed !";
    }elseif($device_no !== $user['device_no']){
        $data['status'] = 0;
        $data['message'] = "Your are not registered with this device !";
    }elseif($user['blocked'] == 1){
        $data['status'] = 0;
        $data['message'] = "Your are Blocked please contact to Admin !";
    }else{
        $data['status'] = 1;
        
    }
    echo json_encode($data);
}else{
    $data['status'] = "userId not found";
    echo json_encode($data);
}

?>
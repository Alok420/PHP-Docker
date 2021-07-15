<?php 
// Making connection
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

if(isset($_REQUEST['redeem']) && $_REQUEST['redeem'] == 1){
    unset($_POST['redeem']);
    $data = array();
    $table = "wallet_payment";
    $data = $_POST;
    $cols = '';
    $vals = '';
    foreach ($data as $column => $value) {
                $cols .= $column.",";
                $vals .= "'".$value."',";
            }
            
           $colsCount =  strlen($cols);
           $columns = substr($cols, 0, $colsCount-1);
           
           $valsCount = strlen($vals);
           $values = substr($vals, 0, $valsCount-1);
            
             $SQL = "insert into $table($columns) values($values)";
             
                $m = mysqli_query($conn, $SQL);
                
                if ($m) {
                    $data['status'] = "success"; 
                    $data['message'] = "Your request placed successfully"; 
                } else {
                    $data['status'] = "failed"; 
                    $data['message'] = "There Are some problem. Please try again later"; 
                }
       
    echo json_encode($data);die;
}


if(isset($_REQUEST['check_referral']) && $_REQUEST['check_referral'] == 1){
    $referralCode = $_REQUEST['referralCode'];
    
    $sql = "SELECT * FROM `user` WHERE (promocode = '$referralCode' or business_promocode = '$referralCode') ";
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    $data = array();
    if(count($result) > 0){
        
        $data['status'] = "success";
        $data['message'] = $result['name']."'s referral code verified ." ;
    }else{
        $data['status'] = "failed";
        $data['message'] = "Referral Code Not available";
    }
    
    echo json_encode($data);
    die;
}
if(isset($_REQUEST['check_id']) && $_REQUEST['check_id'] != ''){
    $check_id = $_REQUEST['check_id'];
    $sql = "SELECT * FROM `user` WHERE fb_id = '$check_id'";
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    $data = array();
    if(count($result) > 0){
        
        $data['status'] = "success";
        $data['message'] = "1";
    }else{
        $data['status'] = "success";
        $data['message'] = "0";
    }
    
     echo json_encode($data);
    die;
}

if(isset($_REQUEST['wallet_history']) && $_REQUEST['wallet_history'] == 1){
    $fb_id = $_REQUEST['user_id'];
    $sql = "SELECT * FROM `users` WHERE fb_id = '$fb_id'";
    $results = mysqli_query($conn, $sql)->fetch_assoc();
    $user_id = $results['id'];
    $sql = "SELECT * FROM `wallet_transaction` WHERE users_id = '$user_id'";
    $results = mysqli_query($conn, $sql);
    $data = array();
    if($results->num_rows > 0){
        
        $i = 0;
        while($result = $results->fetch_assoc()){
            $data[$i] = $result;
            $i++;
        }
    }
    
     echo json_encode($data);
    die;
}
if(isset($_REQUEST['wallet_amount']) && $_REQUEST['wallet_amount'] == 1){
    $fb_id = $_REQUEST['user_id'];
    $sql = "SELECT wallet_amt FROM `users` WHERE fb_id = '$fb_id'";
    $results = mysqli_query($conn, $sql)->fetch_assoc();
    
    $data = array();
    $data['wallet_amount'] = $results['wallet_amt'];
    
     echo json_encode($data);
    die;
}
?>
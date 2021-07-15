<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
    $user_api_key = $_POST["api_key"];
    $loginid = $_POST["loginid"];
    $users = $db->select("user", "*", array("id" => $loginid));
    $user = $users->fetch_assoc();
    $db_user_api_key = $user["api_key"];
    if ($db_user_api_key == $user_api_key) {
        
    } else {
        echo json_encode(array("error" => "Invalid API Key"));
        die();
    }
} else {
    echo json_encode(array("error" => "Invalid API Key Or Logged in user id"));
    die();
}

$data = array();
$sort = "id desc";
$tbname = "daily_spin";
if(isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != ''){
    
    $recentinsertedid = $_REQUEST['loginid'];
    
    $tmpwallet = $db->select("user", "*", array("id"=>$_REQUEST['loginid']))->fetch_assoc();
    $tmp_wallet = $tmpwallet['tmp_wallet'];
    // var_dump($tmp_wallet);die;
    
    if($tmp_wallet > 500){
        $earned_coin = rand(1, $tmp_wallet/5);
    }elseif($tmp_wallet > 100){
        $earned_coin = rand(1, $tmp_wallet/4);
    }elseif($tmp_wallet > 50){
        $earned_coin = rand(1, $tmp_wallet/3);
    }elseif($tmp_wallet > 24){
        $earned_coin = rand(1, $tmp_wallet/2);
    }else{
        $earned_coin = rand(1, $tmp_wallet);
    }
    
    $loginid = $_REQUEST['loginid'];
    
    // Updating user's swarn coin wallet
    $wallets = $db->select("wallets", "*", array("user_id"=>$loginid));
    
    if($wallets->num_rows > 0){
        $wallet = $wallets->fetch_assoc();
        $final_swarnacoin_wallet = $wallet['swarnacoin_wallet'] + $earned_coin;
        $walet_id = $wallet['id'];
        $db->update(array("swarnacoin_wallet"=>$final_swarnacoin_wallet), "wallets", $walet_id);
    }else{
        
        $db->insert(array("swarnacoin_wallet"=>$earned_coin, "user_id"=>$loginid), "wallets");
    }
    
    // Updating user's temp_wallet
    $final_temp_wallet = $tmp_wallet - $earned_coin;
    $info = $db->update(array("tmp_wallet"=>$final_temp_wallet), "user", $recentinsertedid);
    
    // Inserting data in daily spin
    $daily_spins = $db->select("daily_spin", "*", array("user_id"=>$_REQUEST['loginid']), $sort);
    $daily_spin_data = array("user_id"=>$_REQUEST['loginid'], "spin_time"=>date('Y-m-d H:i:s'), "created_date"=>date('Y-m-d'));
    $info = $db->insert($daily_spin_data, $tbname);
   
    // Inserting data in wallet transaction
    $description = "You got ".$earned_coin. " Coins From the Diamond Spin";
    $wallet_transaction_data = array("wallet_type"=>"swarnacoin_wallet", "transaction_type"=>"Credit", "amount"=>$earned_coin, "description"=>$description, "source"=>"wallet", "user_id"=>$loginid );
    $info = $db->insert($wallet_transaction_data, "wallet_transactions");
    
}

// $earned_coin = rand(10, 100);
$data[0] = array("earned_coin"=>$earned_coin);
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

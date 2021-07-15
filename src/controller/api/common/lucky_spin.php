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
$curr_date = date("Y-m-d");
$reward_setings = $db->select("reward_settings", "*", array(), "id desc")->fetch_assoc();
if(isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != ''){
    
    $loginid = $_REQUEST['loginid'];
    $invested_coin = $_REQUEST['coin'] ? $_REQUEST['coin'] : 1;
    $status = $_REQUEST['status'];
    $invest_date = $_REQUEST['date'];
    
    if($status == 'invest'){
        
        
        // Updating user's wallet
        $wallets = $db->select("wallets", "*", array("user_id"=>$loginid))->fetch_assoc();
        $final_swarnacoin_wallet = $wallets['swarnacoin_wallet'] - $invested_coin;
        $walet_id = $wallets['id'];
        $db->update(array("swarnacoin_wallet"=>$final_swarnacoin_wallet), "wallets", $walet_id);
        
        // Inserting data in wallet transaction
        // For deduct invested swarn coin
        $description1 = $invested_coin. " Coin Deducted from your Swarn Coin wallet By Lucky Spin";
        $wallet_deduct_transaction_data = array("wallet_type"=>"swarnacoin_wallet", "transaction_type"=>"Debit", "amount"=>$invested_coin, "description"=>$description1, "source"=>"wallet", "user_id"=>$loginid,"created_at"=>date("Y-m-d H:i:s") );
        $info = $db->insert($wallet_deduct_transaction_data, "wallet_transactions");
        
        // Updating lucky_quiz_transaction
        $lucky_quiz_transactions = $db->select("lucky_quiz_transaction", "*", array("date"=>$curr_date));
        
        if($lucky_quiz_transactions->num_rows > 0){
            
            $lucky_quiz_transaction = $lucky_quiz_transactions->fetch_assoc();
            $lucky_quiz_transaction_id = $lucky_quiz_transaction['id'];
            $total_invested_coin = $lucky_quiz_transaction['invested_coin'] + $invested_coin;
            $db->update(array("invested_coin"=>$total_invested_coin), "lucky_quiz_transaction", $lucky_quiz_transaction_id);
        }else{
            
            $db->insert(array("invested_coin"=>$invested_coin, "date"=>$curr_date), "lucky_quiz_transaction");
            
        }
        
        // Inserting data in lucky_investors
        $lucky_investors_data = array("coin"=>$invested_coin, "date="=>$curr_date, "user_id"=>$loginid);
        $db->insert($lucky_investors_data, "lucky_investors");
        
        $earned_coin = $invested_coin." Coin inserted";
        
    }elseif($status == 'play'){
        
        // Updating status in lucky_investors
        $invest_date = $_REQUEST['date'];
        $update_sql = "UPDATE `lucky_investors` SET status = 'played' WHERE date = '$invest_date' and user_id = $loginid";
        
        mysqli_query($conn, $update_sql);
        
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $lucky_quiz_transactions = $db->select("lucky_quiz_transaction", "*", array("date"=>$invest_date))->fetch_assoc();
        $lucky_quiz_transactions_id = $lucky_quiz_transactions['id'];
        $remaining_coin = $lucky_quiz_transactions['invested_coin']/2;
        
        if($remaining_coin > 10){
            $earned_coin = rand(0, 10);
        }else{
            $earned_coin = rand(0, $remaining_coin);
        }
        
        // Updating given coin and invested coin in lucky_quiz_transaction
        $final_given_coin = $lucky_quiz_transactions['given_coin'] + $earned_coin;
        $final_invested_coin = $lucky_quiz_transactions['invested_coin'] - $earned_coin;
        
        $db->update(array("given_coin"=>$final_given_coin,"invested_coin"=>$final_invested_coin), "lucky_quiz_transaction", $lucky_quiz_transactions_id);
        
       
        if($earned_coin == 0){
            
            $got_amount = $reward_setings['coin_rs']*$invested_coin;
            $wallets = $db->select("wallets", "*", array("user_id"=>$loginid))->fetch_assoc();
            $walet_id = $wallets['id'];
            $cashback_wallet = $wallets['cashback_wallet'];
            $final_cashback_wallet = $cashback_wallet + ($reward_setings['coin_rs']*$invested_coin);
            
            $db->update(array("cashback_wallet"=>$final_cashback_wallet), "wallets", $walet_id);
            
            // Updating given coin and invested coin in lucky_quiz_transaction
            $lucky_quiz_transactions = $db->select("lucky_quiz_transaction", "*", array("date"=>$invest_date))->fetch_assoc();
            $lucky_quiz_transactions_id = $lucky_quiz_transactions['id'];
            
            $final_given_cashback = $lucky_quiz_transactions['given_cashback'] + $got_amount;
           
            $db->update(array("given_cashback"=>$final_given_cashback), "lucky_quiz_transaction", $lucky_quiz_transactions_id);
            
            // Inserting data in wallet transaction
            // For getting Cashback
            
            $earned_coin = $description = "You got Rs ".$got_amount. " In your cashback wallet";
            $wallet_transaction_data = array("wallet_type"=>"cashback_wallet", "transaction_type"=>"Credit", "amount"=>$got_amount, "description"=>$description, "source"=>"wallet", "user_id"=>$loginid );
            $info = $db->insert($wallet_transaction_data, "wallet_transactions");
            
        }else{
            
            $wallets = $db->select("wallets", "*", array("user_id"=>$loginid))->fetch_assoc();
            $walet_id = $wallets['id'];
            $final_swarnacoin_wallet = $wallets['swarnacoin_wallet'] + $earned_coin;
            
            $db->update(array("swarnacoin_wallet"=>$final_swarnacoin_wallet), "wallets", $walet_id);
            $description = "You got ".$earned_coin. " Coins From the Lucky Spin";
            
            // Inserting data in wallet transaction
            // For getting swarn coin
            
            $wallet_transaction_data = array("wallet_type"=>"swarnacoin_wallet", "transaction_type"=>"Credit", "amount"=>$earned_coin, "description"=>$description, "source"=>"wallet", "user_id"=>$loginid );
            $info = $db->insert($wallet_transaction_data, "wallet_transactions");
        }
        
    }else{
        $earned_coin = "Status can't be empty";
    }
    
    
}


// $earned_coin = rand(10, 100);
$data[0] = array("earned_coin"=>$earned_coin);
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

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

$sort = "id desc";
$rewards = $db->select("reward_settings", "*", array(), $sort)->fetch_assoc();

$wallets = $db->select("wallets", "*", array("user_id"=>$_REQUEST['user_id']))->fetch_assoc();
$id = $wallets['id'];

$coins = $_REQUEST['coins'];
$final_coins = $wallets['swarnacoin_wallet'] - $coins; // Getting the remaining coin

$sql = "UPDATE wallets SET swarnacoin_wallet = '$final_coins' WHERE id = $id"; // Updating the remaining coin
mysqli_query($conn, $sql);

// Inserting  given data in wallet transaction
$description = $coins ." Coins deducted from your Swarn Coin Wallet by transferring in your Main Wallet";
$data = array("wallet_type"=>"swarnacoin_wallet", "transaction_type"=>"Debit", "amount"=>$coins,"description"=>$description,"source"=>"swarnacoin_wallet","user_id"=>$_REQUEST['user_id']);

$tbname = "wallet_transactions";
$info = $db->insert($data, $tbname);

$initial_amount = $_REQUEST['coins'] * $rewards['coin_rs'];
$final_amount = $wallets['main_wallet'] + $initial_amount; // Getting the increased wallet amount

$sql = "UPDATE wallets SET main_wallet = '$final_amount' WHERE id = $id"; // Updating the increased wallet amount
mysqli_query($conn, $sql);

// Inserting  receiver data in wallet transaction
$description ="Rs " .$initial_amount." Added in your Main Wallet By Converting Swarn Coin";
$data = array("wallet_type"=>"main_wallet", "transaction_type"=>"Credit", "amount"=>$initial_amount,"description"=>$description,"source"=>"main_wallet","user_id"=>$_REQUEST['user_id']);

$tbname = "wallet_transactions";
$info = $db->insert($data, $tbname);

$info["status"] = "success";
$info["message"] = "Data  saved";
// $info["recentinsertedid"] = $recentinsertedid;
// $data = $db->select($tbname, "*", array("id"=>$recentinsertedid))->fetch_assoc();
// $info["data"] = $data;


echo json_encode($info);

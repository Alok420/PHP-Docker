<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
include '../../../Config/Mail.php';
//error_reporting(2);
$db = new DB($conn);
$info = array();
$location = "../../../img/fund_request/"; 
//var_dump($_POST);
if (isset($_POST["loginid"])) {
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

    unset($_POST["api_key"]);
    unset($_POST["loginid"]);
  
    $tbname = 'wallet_transactions';

    $wallet_type = $_POST["wallet_type"];
    $wallet_name = str_replace('_', ' ', $wallet_type);
    $amount = $_POST["amount"];
    $user_id = $_POST["user_id"];
    $receiver_id = $_POST["receiver_id"];
    
    $receiver_name = $db->select("user", "*", array("id"=>$receiver_id))->fetch_assoc();
    $sender_name = $db->select("user", "*", array("id"=>$user_id))->fetch_assoc();
    
    $_POST["transaction_type"] = "Debit";
    
    if($wallet_type == 'swarnacoin_wallet'){
        $_POST["description"] = $amount." Coin is transferred in ".$receiver_name['name']." 's ".ucwords($wallet_name);
    }elseif($wallet_type == 'cashback_wallet'){
        $_POST["description"] = $amount." Coin is transferred in ".$receiver_name['name']." 's ".ucwords($wallet_name);
    }
    
    $_POST["source"] = $wallet_type;
    
    unset($_POST["tbname"]);
    unset($_POST["receiver_id"]);
    $info = $db->insert($_POST, $tbname);
    
    
    

    if (isset($_SESSION["recentinsertedid"])) {
        $recentinsertedid = $_SESSION["recentinsertedid"];
        // updating sender wallet
        $sql = "SELECT * FROM `wallets` WHERE ( user_id = ".$user_id.")";
        $sender_wallet = mysqli_query($conn, $sql)->fetch_assoc();
        
        $final_amount = $sender_wallet[$wallet_type] - $amount;
        $id = $sender_wallet['id'];
        
        $sql = "UPDATE wallets SET $wallet_type = '$final_amount' WHERE id = $id";
        mysqli_query($conn, $sql);
        
        // updating receiver wallet
        $sql = "SELECT * FROM `wallets` WHERE  user_id = ".$receiver_id;
        $receiver_wallet = mysqli_query($conn, $sql);
        if($receiver_wallet->num_rows > 0){
            $receiver_wallet = $receiver_wallet->fetch_assoc();
            
            $final_amount = $receiver_wallet[$wallet_type] + $amount;
            $id = $receiver_wallet['id'];
            
            $sql = "UPDATE wallets SET $wallet_type = '$final_amount' WHERE id = $id";
            mysqli_query($conn, $sql);
        }else{
            
            $tbname = "wallets";
            $data = array($wallet_type=>$amount, "user_id"=>$receiver_id);
            
            $info = $db->insert($data, $tbname);
        }
        
        
        
        // Inserting  receiver data in wallet transaction
        $description =$amount." Coin Added in your ".ucwords($wallet_name). " By ".ucwords($sender_name['name']);
        $data = array("wallet_type"=>$wallet_type, "transaction_type"=>"Credit", "amount"=>$amount,"description"=>$description,"source"=>"wallet","user_id"=>$receiver_id);
        
        $info = $db->insert($data, $tbname);
    }
    if ($info[0] == 1) {
        if (count($_FILES) > 0) {
            $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,JPEG,PNG,JFIF,PDF,DOCX,mp4,jpg,jpeg,png,jfif,pdf,docx");
            
            $return = array();
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
            echo json_encode($return);
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
            echo json_encode($info);
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
        echo json_encode($info);
    }
} else {
    $info["status"] = "failed";
    $info["message"] = "Log in first";
    echo json_encode($info);
}


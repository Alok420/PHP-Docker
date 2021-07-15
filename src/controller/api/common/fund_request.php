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
  
    $tbname = 'fund_requests';

    unset($_POST["tbname"]);
    $amount = $_POST["amount"];
    $user_id = $_POST["user_id"];
    $role = $db->select("user", "role", array("id"=>$user_id))->fetch_assoc()['role'];
    
    $sql = "SELECT SUM(value) as total_charge FROM `charges` WHERE status = 'active' ";
        $charges = mysqli_query($conn, $sql)->fetch_assoc();
        
    $_POST["amount"] = $final_amt = $amount - ($amount * $charges['total_charge'])/100;
    
    $info = $db->insert($_POST, $tbname);
    
    $fund_request_id = $_SESSION["recentinsertedid"];
  
    $charges = $db->select("charges", "*", array("status"=>'active'));
    while($charge = $charges->fetch_assoc()){
        
        // Insering data in wallet_transactions
        $single_charge = ($amount * $charge['value'])/100;
        $description = "Rs " .$single_charge." ".$charge['name']. "  Charge ( ".$charge['value']."%) is deducted from your requested amount ( ".$amount." )";
        $admin_charge_data = array("wallet_type"=>"bank", "transaction_type"=>"Debit", "amount"=>$single_charge,"description"=>$description,"source"=>"bank","user_id"=>$user_id);
        
        $info = $db->insert($admin_charge_data, "wallet_transactions");
        
        //Inserting data in charges_transaction
        $charge_description = "Rs ".$single_charge." ".$charge['name']." Charge added ";
        $charges_transaction_data = array("amount"=>$single_charge,"description"=>$charge_description,"user_id"=>$user_id,"charges_id"=>$charge['id'], "fund_requests_id"=>$fund_request_id, "role"=>$role);
        
        $info = $db->insert($charges_transaction_data, "charges_transaction");
        
    }

    if (isset($fund_request_id)) {
        $recentinsertedid = $fund_request_id;
        
        $sql = "SELECT * FROM `wallets` WHERE user_id = $user_id ";
        $main_wallet = mysqli_query($conn, $sql)->fetch_assoc();
        
        $final_amount = $main_wallet['main_wallet'] - $amount;
        // var_dump($final_amount);die;
        $id = $main_wallet['id'];
        
        $sql = "UPDATE wallets SET main_wallet = '$final_amount' WHERE id = $id";
        mysqli_query($conn, $sql);
        
        // Inserting  receiver data in wallet transaction
        $tbname = "wallet_transactions";
        $description ="Rs " .$final_amt." Requested To Admin";
        $data = array("wallet_type"=>"main_wallet", "transaction_type"=>"Debit", "amount"=>$final_amt,"description"=>$description,"source"=>"bank","user_id"=>$user_id);
        $info = $db->insert($data, $tbname);
        
        $charges = $db->select("charges", "*", array("status"=>'active'));
        while($charge = $charges->fetch_assoc()){
            
            $single_charge = ($amount * $charge['value'])/100;
            $description = "Rs " .$single_charge." ".$charge['name']. " Charge is deducted from your requested amount";
            $admin_charge_data = array("wallet_type"=>"main_wallet", "transaction_type"=>"Debit", "amount"=>$single_charge,"description"=>$description,"source"=>"bank","user_id"=>$user_id);
            
            $info = $db->insert($admin_charge_data, "wallet_transactions");
        }
        
        
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


<?php
session_start();
include '../../Config/Connection.php';
include '../../Config/DB.php';
$db = new DB($conn);
$curr_date = date("Y-m-d");

/*Note : After completing transaction process it is recommended to make an enquiry call with PayU to validate the response received and then save the response to DB or display it on UI*/
$user_id = $_POST['udf5'];
$postdata = $_POST;
$msg = '';
// $salt = $_SESSION['salt']; //Salt already saved in session during initial request.

// Test Salt
$salt="eCwWELxi";

// Live Salt
// $salt="O34zgwSx";

/* Response received from Payment Gateway at this page.
Process response parameters to generate Hash signature and compare with Hash sent by payment gateway 
to verify response content. Response may contain additional charges parameter so depending on that 
two order of strings are used in this kit.

Hash string without Additional Charges -
hash = sha512(SALT|status||||||udf5|||||email|firstname|productinfo|amount|txnid|key)

With additional charges - 
hash = sha512(additionalCharges|SALT|status||||||udf5|||||email|firstname|productinfo|amount|txnid|key)

*/
if (isset($postdata ['key'])) {
	$key				=   $postdata['key'];
	$txnid 				= 	$postdata['txnid'];
    $amount      		= 	$postdata['amount'];
	$productInfo  		= 	$postdata['productinfo'];
	$firstname    		= 	$postdata['firstname'];
	$email        		=	$postdata['email'];
	$udf5				=   $postdata['udf5'];	
	$status				= 	$postdata['status'];
	$resphash			= 	$postdata['hash'];
	//Calculate response hash to verify	
	$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
	$keyArray 	  		= 	explode("|",$keyString);
	$reverseKeyArray 	= 	array_reverse($keyArray);
	$reverseKeyString	=	implode("|",$reverseKeyArray);
	$CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString)); //hash without additionalcharges
	
	//check for presence of additionalcharges parameter in response.
	$additionalCharges  = 	"";
	
	If (isset($postdata["additionalCharges"])) {
       $additionalCharges=$postdata["additionalCharges"];
	   //hash with additionalcharges
	   $CalcHashString 	= 	strtolower(hash('sha512', $additionalCharges.'|'.$salt.'|'.$status.'|'.$reverseKeyString));
	}
	//Comapre status and hash. Hash verification is mandatory.
	if ($status == 'success'  && $resphash == $CalcHashString) {
		$msg = "Transaction Successful, Hash Verified...<br />";
		//Do success order processing here...
		//Additional step - Use verify payment api to double check payment.
		if(verifyPayment($key,$salt,$txnid,$status))
			$msg = "Transaction Successful, Hash Verified...Payment Verified...";
			
			$gst = $amount * 18/100;
    		$total = $amount - $gst;
    		
    		 
    		if($_POST['status'] == 'success'){
    		    $status = "TXN_SUCCESS";
    		}
    		
    		$sql = "INSERT INTO `payments`(`paymentId`, `amount`, `status`, `created_date`,`user_id`, `gst`, `total`, `type`) VALUES ('$txnid',$amount,'$status','".$_POST['addedon']."',$user_id,$gst,$total,'credit')";
    		$insert_payment = mysqli_query($conn, $sql);
    		
    	
    // 		Giving Cashback
    		$got_amount = $amount;
    		$sql = "select * from wallets where user_id = $user_id";
    		$wallets = mysqli_query($conn, $sql);
    		
            // $wallets = $db->select("wallets", "*", array("user_id"=>$user_id));
            
            if($wallets->num_rows > 0){
                $wallet = $wallets->fetch_assoc();
                $walet_id = $wallet['id'];
                $cashback_wallet = $wallet['cashback_wallet'];
                $final_cashback_wallet = $cashback_wallet + $got_amount;
            
                $casgback_upadet = $db->update(array("cashback_wallet"=>$final_cashback_wallet), "wallets", $walet_id);
            }else{
                
                $casgback_upadet = $db->insert(array("cashback_wallet"=>$got_amount, "user_id"=>$user_id));
            }
            
            // Inserting data in wallet transaction
            // For getting Cashback
            
            $description = "You got Rs ".$got_amount. " Welcome cashback in your cashback wallet.";
            $wallet_transaction_data = array("wallet_type"=>"cashback_wallet", "transaction_type"=>"Credit", "amount"=>$got_amount, "description"=>$description, "source"=>"wallet", "user_id"=>$user_id );
            $info = $db->insert($wallet_transaction_data, "wallet_transactions");
            
            
            //  Updating app_download in user table
                $user = $db->select("user", "*", array("id"=>$user_id))->fetch_assoc();
                
                $referralcode = $user['referralcode'];
                
                $direct_users = $db->select("user", "*", array("promocode"=>$referralcode));
                if($direct_users->num_rows > 0){
                    $direct_user = $direct_users->fetch_assoc();
                    $promocode = $direct_user['promocode'];
                    // Updating User total app and tmp_wallet
                   
                    $direct_user_id = $direct_user['id'];
                    $user_total_app = $direct_user['total_app'] + 1;
                    $tmp_wallet = $direct_user['tmp_wallet'] + $reward_settings['referral_coin'];
                    
                    $sql = "SELECT count(id) as total_user FROM `user` WHERE created_at = '$curr_date' AND referralcode = '$promocode' ";
                    $total_tmp_wallet = mysqli_query($conn, $sql)->fetch_assoc();
                   
                    if($total_tmp_wallet['total_user'] * $reward_settings['referral_coin'] > $reward_settings['per_day_capping']){
                        $tmp_wallet = $reward_settings['per_day_capping'];
                    }
                    
                    $sql = "UPDATE user SET total_app = $user_total_app, tmp_wallet = $tmp_wallet WHERE id = $direct_user_id";
                    mysqli_query($conn, $sql);
                    
                    
                }
                
                $users = $db->select("user", "*", array("business_promocode"=>$referralcode));
                if($users->num_rows > 0){
                    $user = $users->fetch_assoc();
                    
                    // Updating Affiliate total app
                    $affiliate_id = $user['id'];
                    $total_app = $user['total_app'] + 1;
                    $sql = "UPDATE user SET total_app = $total_app WHERE id = $affiliate_id";
                    mysqli_query($conn, $sql);
                    
                    // Updating sponser total app
                    $sponser_id = $user['sponser_id'];
                    $sponser = $db->select("user", "*", array("id"=>$sponser_id))->fetch_assoc();
                    $sponser_app = $sponser['total_app'] + 1;
                    $sql = "UPDATE user SET total_app = $sponser_app WHERE id = $sponser_id";
                    mysqli_query($conn, $sql);
                    
                    // Updating super_sponser total app
                    $super_sponser_id = $user['super_sponser_id'];
                    $super_sponser = $db->select("user", "*", array("id"=>$super_sponser_id))->fetch_assoc();
                    $super_sponser_app = $super_sponser['total_app'] + 1;
                    $sql = "UPDATE user SET total_app = $super_sponser_app WHERE id = $super_sponser_id";
                    mysqli_query($conn, $sql);
                    
                }
		else
			$msg = "Transaction Successful, Hash Verified...Payment Verification failed...";
	}
	else {
		//tampered or failed
		$msg = "Payment failed for Hash not verified...";
		
		if($_POST['status'] == 'failed'){
		    $status = "TXN_FAILED";
		}
		
		$gst = $amount * 18/100;
		$total = $amount - $gst;
    		
		$sql = "INSERT INTO `payments`(`paymentId`, `amount`, `status`, `created_date`,`user_id`, `gst`, `total`, `type`) VALUES ('$txnid',$amount,'$status','".$_POST['addedon']."',$user_id,$gst,$total,'credit')";
		$insert_payment = mysqli_query($conn, $sql);
	} 
}
else exit(0);


//This function is used to double check payment
function verifyPayment($key,$salt,$txnid,$status)
{
	$command = "verify_payment"; //mandatory parameter
	
	$hash_str = $key  . '|' . $command . '|' . $txnid . '|' . $salt ;
	$hash = strtolower(hash('sha512', $hash_str)); //generate hash for verify payment request

    $r = array('key' => $key , 'hash' =>$hash , 'var1' => $txnid, 'command' => $command);
	    
    $qs= http_build_query($r);
	//for production
    //$wsUrl = "https://info.payu.in/merchant/postservice.php?form=2";
   
	//for test
	$wsUrl = "https://test.payu.in/merchant/postservice.php?form=2";
	
	try 
	{		
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $wsUrl);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		$o = curl_exec($c);
		if (curl_errno($c)) {
			$sad = curl_error($c);
			throw new Exception($sad);
		}
		curl_close($c);
		
		/*
		Here is json response example -
		
		{"status":1,
		"msg":"1 out of 1 Transactions Fetched Successfully",
		"transaction_details":</strong>
		{	
			"Txn72738624":
			{
				"mihpayid":"403993715519726325",
				"request_id":"",
				"bank_ref_num":"670272",
				"amt":"6.17",
				"transaction_amount":"6.00",
				"txnid":"Txn72738624",
				"additional_charges":"0.17",
				"productinfo":"P01 P02",
				"firstname":"Viatechs",
				"bankcode":"CC",
				"udf1":null,
				"udf3":null,
				"udf4":null,
				"udf5":"PayUBiz_PHP7_Kit",
				"field2":"179782",
				"field9":" Verification of Secure Hash Failed: E700 -- Approved -- Transaction Successful -- Unable to be determined--E000",
				"error_code":"E000",
				"addedon":"2019-08-09 14:07:25",
				"payment_source":"payu",
				"card_type":"MAST",
				"error_Message":"NO ERROR",
				"net_amount_debit":6.17,
				"disc":"0.00",
				"mode":"CC",
				"PG_TYPE":"AXISPG",
				"card_no":"512345XXXXXX2346",
				"name_on_card":"Test Owenr",
				"udf2":null,
				"status":"success",
				"unmappedstatus":"captured",
				"Merchant_UTR":null,
				"Settled_At":"0000-00-00 00:00:00"
			}
		}
		}
		
		Decode the Json response and retrieve "transaction_details" 
		Then retrieve {txnid} part. This is dynamic as per txnid sent in var1.
		Then check for mihpayid and status.
		
		*/
		$response = json_decode($o,true);
		
		if(isset($response['status']))
		{
			// response is in Json format. Use the transaction_detailspart for status
			$response = $response['transaction_details'];
			$response = $response[$txnid];
			
			if($response['status'] == $status) //payment response status and verify status matched
				return true;
			else
				return false;
		}
		else {
			return false;
		}
	}
	catch (Exception $e){
		return false;	
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style type="text/css">

    body
    {
        background:#f2f2f2;
    }

    .payment
	{
		border:1px solid #f2f2f2;
		height:280px;
        border-radius:20px;
        background:#fff;
	}
   .payment_header
   {
	   background:rgba(255,102,0,1);
	   padding:20px;
       border-radius:20px 20px 0px 0px;
	   
   }
   
   .check
   {
	   margin:0px auto;
	   width:50px;
	   height:50px;
	   border-radius:100%;
	   background:#fff;
	   text-align:center;
   }
   
   .check i
   {
	   vertical-align:middle;
	   line-height:50px;
	   font-size:30px;
   }

    .content 
    {
        text-align:center;
    }

    .content  h1
    {
        font-size:25px;
        padding-top:25px;
    }

    .content a
    {
        width:200px;
        height:35px;
        color:#fff;
        border-radius:30px;
        padding:5px 10px;
        background:rgba(255,102,0,1);
        transition:all ease-in-out 0.3s;
    }

    .content a:hover
    {
        text-decoration:none;
        background:#000;
    }
    body{
        background-image: linear-gradient(to right top, #f14132, #ec0051, #d80073, #b10095, #6e00b3);
    }
   
</style>
<body>
<div class="container">
   <div class="row">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check">
                   <?php 
                   if($_POST['status'] == "success"){
                      
                       $message = "Enjoy with us!";
                       ?>
                       <i class="fa fa-check" aria-hidden="true"></i>
                       <?php
                   }else{
                       $message = "Try again later...";
                       ?>
                       <i class="fa fa-times" aria-hidden="true"></i>
                       <?php
                   }
                   ?>
                   
               </div>
            </div>
            <div class="content">
               <h1>Payment <?php echo ucwords($_POST['status']); ?> !</h1>
               <p>Amount - <?php echo $amount ?><br>
                  Transaction Id - <?php echo $txnid ?><br>
                  <span style="text-align:center"><?php echo $message; ?></span>
               </p>
               
            </div>
            
         </div>
      </div>
   </div>
</div>
</body>
</html>
	
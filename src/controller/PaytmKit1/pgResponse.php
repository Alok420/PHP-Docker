<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<?php
session_start();
include '../../Config/Connection.php';
include '../../Config/DB.php';

$db = new DB($conn);
$reward_settings = $db->select("reward_settings", "*", array(), $sort)->fetch_assoc();

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your applications MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
// 	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
// 		echo "<b>Transaction status is success</b>" . "<br/>";
		$user_id = $_SESSION['user_id'];
// 		unset($_SESSION['user_id']);
		$gst = $_POST['TXNAMOUNT'] * 18/100;
		$total = $_POST['TXNAMOUNT'] - $gst;
		
		$sql = "INSERT INTO `payments`(`paymentId`, `amount`, `status`, `created_date`,`user_id`, `gst`, `total`, `type`) VALUES ('".$_POST['TXNID']."',".$_POST['TXNAMOUNT'].",'".$_POST['STATUS']."','".$_POST['TXNDATE']."',$user_id,$gst,$total,'credit')";
		$insert_payment = mysqli_query($conn, $sql);
		
		$recentinsertid = $conn->insert_id;
		
		$sql = "select user_id from payments where paymentId = ".$_POST['TXNID']." and created_date = '".$_POST['TXNDATE']."'";
		
		$user_id = mysqli_query($conn, $sql)->fetch_assoc()['user_id'];
		
// 		$user_id = $db->select("payments", "user_id", array("id"=>$recentinsertid))->fetch_assoc()['user_id'];
		
		
// 		Giving Cashback
		$got_amount = $_POST['TXNAMOUNT'];
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
		?>
		
		<div class="container">
           <div class="row">
              <div class="col-md-6 mx-auto mt-5">
                 <div class="payment">
                    <div class="payment_header">
                       <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
                    </div>
                    <div class="content">
                       <h1>Payment Success !</h1>
                       <p>Amount - <?php echo $_POST['TXNAMOUNT'] ?><br>
                          Transaction Id - <?php echo $_POST['TXNID'] ?><br>
                          <span style="text-align:center">Enjoy With Us</span>
                       </p>
                       <!--<a href="#">Go to Home</a>-->
                    </div>
                    
                 </div>
              </div>
           </div>
        </div>
		<?php
	}
	else {
// 		echo "<b>Transaction status is failure</b>" . "<br/>";
		
		$user_id = $_SESSION['user_id'];
		$gst = $_POST['TXNAMOUNT'] * 18/100;
		$total = $_POST['TXNAMOUNT'] - $gst;
		
		$payment_data = array("paymentId"=>$_POST['TXNID'], "amount"=>$_POST['TXNAMOUNT'], "status"=>$_POST['STATUS'], "created_date"=>$_POST['TXNDATE'], "user_id"=>$user_id,"gst"=>$gst,"total"=>$total,"type"=>"credit");
		$db->insert($payment_data, "payments");
		
		?>
		<div class="container">
           <div class="row">
              <div class="col-md-6 mx-auto mt-5">
                 <div class="payment">
                    <div class="payment_header">
                       <div class="check"><i class="fa fa-times" aria-hidden="true"></i></div>
                    </div>
                    <div class="content">
                       <h1>Payment Failed !</h1>
                       <p>Amount - <?php echo $_POST['TXNAMOUNT'] ?><br>
                          Transaction Id - <?php echo $_POST['TXNID'] ?>
                       </p>
                       <!--<a href="#">Go to Home</a>-->
                    </div>
                    
                 </div>
              </div>
           </div>
        </div>
		
		<?php
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 
		foreach($_POST as $paramName => $paramValue) {
				// echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>
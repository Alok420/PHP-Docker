<?php
session_start();
include '../../Config/ConnectionObjectOriented.php';
include '../../Config/DB.php';

$db = new DB($conn);

$_POST['amount'] = 500;
$user_id = $_POST['user_id'] ? $_POST['user_id'] : 2305;

$user_detail = $db->select("user", "name", array("id"=>$user_id))->fetch_assoc();
$_POST['firstname'] = $user_detail['name'];


//Unique merchant key provided by PayU along with salt. Salt is used for Hash signature 
//calculation within application and must not be posted or transfered over internet. //-->

// Test Keys
$key="gtKFFx";
$salt="eCwWELxi";

// Live keys
// $key="iiKKs1";
// $salt="O34zgwSx";

$action = 'https://test.payu.in/_payment';

$html='';

if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0){
	/* Request Hash
	----------------
	For hash calculation, you need to generate a string using certain parameters 
	and apply the sha512 algorithm on this string. Please note that you have to 
	use pipe (|) character as delimeter. 
	The parameter order is mentioned below:
	
	sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
	
	Description of each parameter available on html page as well as in PDF.
	
	Case 1: If all the udf parameters (udf1-udf5) are posted by the merchant. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
	
	Case 2: If only some of the udf parameters are posted and others are not. For example, if udf2 and udf4 are posted and udf1, udf3, udf5 are not. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email||udf2||udf4|||||||SALT)

	Case 3: If NONE of the udf parameters (udf1-udf5) are posted. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email|||||||||||SALT)
	
	In present kit and available PayU plugins UDF5 is used. So the order is -	
	hash=sha512(key|txnid|amount|productinfo|firstname|email|||||udf5||||||SALT)
	
	*/
	//generate hash with mandatory parameters and udf5
	$hash=hash('sha512', $key.'|'.$_POST['txnid'].'|'.$_POST['amount'].'|'.$_POST['productinfo'].'|'.$_POST['firstname'].'|'.$_POST['email'].'|||||'.$_POST['udf5'].'||||||'.$salt);
		

	$html = '<form action="'.$action.'" id="payment_form_submit" method="post">
			<input type="hidden" id="udf5" name="udf5" value="'.$_POST['udf5'].'" />
			<input type="hidden" id="surl" name="surl" value="'.getCallbackUrl().'" />
			<input type="hidden" id="furl" name="furl" value="'.getCallbackUrl().'" />
			<input type="hidden" id="curl" name="curl" value="'.getCallbackUrl().'" />
			<input type="hidden" id="key" name="key" value="'.$key.'" />
			<input type="hidden" id="txnid" name="txnid" value="'.$_POST['txnid'].'" />
			<input type="hidden" id="amount" name="amount" value="'.$_POST['amount'].'" />
			<input type="hidden" id="productinfo" name="productinfo" value="'.$_POST['productinfo'].'" />
			<input type="hidden" id="firstname" name="firstname" value="'.$_POST['firstname'].'" />
			
			<input type="hidden" id="hash" name="hash" value="'.$hash.'" />
			</form>
			<script type="text/javascript"><!--
				document.getElementById("payment_form_submit").submit();	
			//-->
			</script>';
	
}
 
//This function is for dynamically generating callback url to be postd to payment gateway. Payment response will be
//posted back to this url. 
function getCallbackUrl()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$uri = str_replace('/index.php','/',$_SERVER['REQUEST_URI']);
	return $protocol . $_SERVER['HTTP_HOST'] . $uri . 'response.php';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Phone Raksha Payment</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<style type="text/css">
	.main {
		margin-left:30px;
		font-family:Verdana, Geneva, sans-serif, serif;
	}
	.text {
		float:left;
		width:180px;
	}
	.dv {
		margin-bottom:5px;		
	}	
</style>
<body>
	<div class="main">
		<div>
			<img src="images/logo.png" />
		</div>
		<div>
			<h3>User Details</h3>
		</div>		
		<!-- Form Block //-->
		<span style="float:left; display:inline-block;">
			<!--// Form with required parameters to be posted to Payment Gateway. For details of each required 
			parameters refer Integration PDF. //-->
			<form action="" id="payment_form" method="post">
			
			<!-- Contains information of integration type. Consult to PayU for more details.//-->
			<input type="hidden" id="udf5" name="udf5" value="<?php echo $user_id; ?>" />					
    
			<div class="dv">
				<span class="text"><label>Transaction/Order ID:</label></span>
				<span>
				<!-- Required - Unique transaction id or order id to identify and match 
				payment with local invoicing. Datatype is Varchar with a limit of 25 char. //-->
				<input type="text" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo  "PRPAY" . rand(10000,99999999)?>" /></span>
			</div>
		
			<div class="dv">
				<span class="text"><label>Amount:</label></span>
				<span>
				<!-- Required - Transaction amount of float type. //-->
				<input type="text" id="amount" name="amount" placeholder="Amount" value="<?php echo $_POST['amount']; ?>" /></span>    
			</div>
    
			<div class="dv">
				<span class="text"><label>Product Info:</label></span>
				<span>
				<!-- Required - Purchased product/item description or SKUs for future reference. 
				Datatype is Varchar with 100 char limit. //-->
				<input type="text" id="productinfo" name="productinfo" placeholder="Product Info" value="P01,P02" /></span>
			</div>
    
			<div class="dv">
				<span class="text"><label>First Name:</label></span>
				<span>
				<!-- Required - Should contain first name of the consumer. Datatype is Varchar with 60 char limit. //-->
				<input type="text" id="firstname" name="firstname" placeholder="First Name" value="<?php echo $_POST['firstname']; ?>" /></span>
			</div>
			

		
		
			<div><input type="button" id="btnsubmit" name="btnsubmit" value="Pay" onclick="frmsubmit(); return true;" /></div>
			</form>
		</span>
		
		
		<?php if($html) echo $html; //submit request to PayUBiz  ?>
		
		
	</div> <!-- End of Main Div //-->
	
	<!-- Below script makes final submission of form to Payment Gateway. This script is for present Demo/Test request 
	form only. In case of live integration, other methods may be used for request form submission. Salt is confidential
	so should not be passed over internet.//-->
	<script type="text/javascript">		
		<!--
		function frmsubmit()
		{
			document.getElementById("payment_form").submit();	
			return true;
		}
		//-->
	</script>
	
</body>
</html>
	
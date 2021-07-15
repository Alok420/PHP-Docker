<?php 
session_start();

$from = "shubhambbdic@gmail.com";
ini_set('sendmail_from', $from);

include 'Config/Connection.php';
include 'Config/DB.php';
include 'Config/Mail.php';
include 'vendor/autoload.php';

$mail = new Mail();


$db = new DB($conn);

if(isset($_REQUEST['submit'])){
    
    $email = $_REQUEST['email'];
    $sql = "Select * from user where email = '$email' ";
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    if(count($result) > 0){
        $email = $result['email'];
        $id = $result['id'];
        
        $to = $email;
        $subject = "Reset Password";
        $password = rand(1000, 99999);
        $txt = "Your password is reset. Your new password - ".$password . ". Use this password and then set your password as desired from your panel. Thank You!!! phoneraksha.com/dashboard/login.php";
        $msg = wordwrap($txt,70);
        $headers = "From: info@phoneraksha.com";
        
        $sent_mail = $mail->sendMail($to, $subject, $txt);
        // $mail = mail($to,$subject,$txt,$headers);
        // var_dump($sent_mail);die;
        if($sent_mail){
            $data = array("password"=>$password);
            $table = "user";
            $info = $db->update($data, $table, $id);
            
            ?>
            <script>
                alert("Your Password is reset kindly check your mail");
                window.location.replace("login.php");
            </script>
            <?php
            
        }else{
             ?>
            <script>
                alert("Some problem accur please try again");
                window.location.replace("forget_password.php");
            </script>
            <?php
        }
        
    }else{
        ?>
        <script>
            alert("No record found with this email");
            window.location.replace("forget_password.php");
        </script>
        <?php
    }
}else{

?>

 <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/login.css">
<!------ Include the above in your HEAD tag ---------->
<style>
    body{
        background-image: linear-gradient(to right top, #f14132, #ec0051, #d80073, #b10095, #6e00b3);
    }
</style>
<div class="wrapper fadeInDown">
    <div id="formContent" style="padding:20px">
        <!-- Tabs Titles -->

        <!-- Icon -->
        <div class="fadeIn first">
            <!--<img src="" id="icon" alt="User Icon" />-->
        </div>
       
        <form action="forget_password.php" method="post">

            <input type="email" id="email" class="fadeIn second" name="email" placeholder="Enter Your registered email id">
           
            <input type="submit" name="submit" class="fadeIn fourth" value="Submit">
        </form>
    </div>
    
</div>
<?php } ?>
<?php 
include_once 'common/session_conn_db.php';

if(isset($_SESSION['loginid'])){
    $db->sendTo(BASE_URL."admin/index.php");
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div id="formContent" style="padding:10px;">
        <!-- Tabs Titles -->

        <!-- Icon -->
        <div class="fadeIn first">
            <!--<img src="" id="icon" alt="User Icon" />-->
        </div>
        <div class="section-heading">
            <div>
            <img src="<?php echo BASE_URL ?>img/web_settings/<?php echo $logo; ?>" alt=" Site Logo" style="height:100px;width: auto;">
            </div>
            <h5>Great to see you back!</h5>
            <?php
            if (isset($_GET["status_message"])) {
                echo '<h5 style="color:red;">' . $_GET["status_message"] . '</h5>';
            }
            ?>
        </div>
        <form action="<?php echo BASE_URL; ?>controller/update.php" method="post" enctype="multipart/form-data">
            <input type="text"  class="fadeIn fourth" name="name" placeholder="Create Password?" accept="image/*">
            <input type="file"  class="fadeIn fourth" name="image" placeholder="Create Password?" accept="image/*">
            <input type="file"  class="fadeIn fourth" name="image2" placeholder="Create Password?" accept="image/*">
            <input type="hidden" name="tbname" value="file_test_table"><br>
            <input type="hidden" name="id" value="3"><br>
            <input type="checkbox" onclick="showPassword()" id="show_passowrd"><label for="show_passowrd"> &nbsp;Show Password</label>
            <br>
            <input type="submit" class="fadeIn fourth" value="Register">
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
            <!--<a class="underlineHover" href="register.php" style="float:left">Register</a>-->
            <a class="underlineHover" href="forget_password.php" style="float:center">Forget Password</a>
        </div>
       
    </div>
    
    <script>
    function showPassword() {
      var x = document.getElementById("password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
    </script>
</div>
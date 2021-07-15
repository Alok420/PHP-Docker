<?php
$sql = "DESCRIBE my_table";
 
$env = file('Config/database.php');

$DATABASE_CONFIGURED = $env[0];
$DATABASE_CONFIGURED = explode("=",$DATABASE_CONFIGURED);

$is_configured = $DATABASE_CONFIGURED[1];
if($is_configured == 0){
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
            <h5>Configure Database</h5>
        </div>
        <form action="Config/build.php" method="post">
            <input type="text"  class="fadeIn second" name="user_name" placeholder="User Name. eg - root">
            <input type="text"  class="fadeIn second" name="dbname" placeholder="Database name">
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="Database Password">
            <input type="hidden" name="tbname" value="user"><br>
            <input type="checkbox" onclick="showPassword()" id="show_passowrd"><label for="show_passowrd"> &nbsp;Show Password</label>
            <br>
            <input type="submit" class="fadeIn fourth" value="Submit">
        </form>
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
    <?php

}else{
    ?>
    <script>
        window.location.replace("login.php");
    </script>
    <?php
}

?>


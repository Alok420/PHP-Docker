<?php
include_once 'common/session_conn_db.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/login.css">
<!------ Include the above in your HEAD tag ---------->
<style>
    body {
        background: #0F2027;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #2C5364, #203A43, #0F2027);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #2C5364, #203A43, #0F2027);
        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

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
            <h5>Register here</h5>
            <?php
            if (isset($_GET["status"]) && $_GET["status"] == "success" && $_GET["message"]=="Data saved") {
                $db->sendTo("login.php");
            } else {
                echo '<h5 style="color:red;">' . $_GET["status_message"] . '</h5>';
            }
            ?>
        </div>
        <form action="controller/insert.php" method="post">
            <input type="text" id="name" class="fadeIn second" name="name" placeholder="Your Name?">
            <input type="email" id="email" class="fadeIn second" name="email" placeholder="Your Email?">
            <input type="text" id="contact" class="fadeIn third" name="contact" placeholder="Your 10 digit Contact?" pattern="[6-9]{1}[0-9]{9}" maxlength="10" size="10">
            <input type="password" id="password" class="fadeIn fourth" name="password" placeholder="Create Password?">
            <input type="hidden" name="tbname" value="user"><br>
            <input type="checkbox" onclick="showPassword()" id="show_passowrd"><label for="show_passowrd"> &nbsp;Show Password</label>
            <br>
            <input type="submit" class="fadeIn fourth" value="Register">
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
            <!--<a class="underlineHover" href="register.php" style="float:left">Register</a>-->
            <a class="underlineHover" href="login.php" style="float:center">Login</a>
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
<?php include './common/session_db.php'; ?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <?php include './common/head.php'; ?>
        <?php include '../Config/common_script.php'; ?>
    </head>

    <body>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->

        <div class="left-sidebar-pro">
            <!-- nav_siderbar php -->
            <?php include './common/nav_sidebar.php'; ?>
            <!-- nav_siderbar End php -->
        </div>
        <!-- Start Welcome area -->
        <div class="all-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="logo-pro">
                            <a href="index.php"><img class="main-logo" src="img/logo/logo.png" alt="" style="height: 60px; width: 100px;" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-advance-area">

                <!-- haeder_menu  start-->
                <?php include './common/header_menu.php'; ?>
                <!-- haeder_menu  end -->
                <!-- Mobile Menu start -->
                <div class="mobile-menu-area">
                    <?php include './common/mobile_menu_area.php'; ?>
                </div>
                <!-- Mobile Menu end -->            
            </div>
            <div class="product-status mg-tb-15">
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <DIV class="container-fluid">
                            <h4 class="text-center">Personal profile</h4>
                            <?php
                            
                            $db->myProfileAdvance("user", array("id:Id" => "text", "name:Name" => "text","contact:Contact"=>"label", "email:Email" => "label","image:Image" => "file"), array("id" => USER["id"]));
                                
                            ?>

                            <h2 class="text-center">Change Password</h2>
                            <?php
                             $db->myProfile("user", "id,email,password", array("id" => USER["id"])); 
                            ?>
                            
                        </DIV>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        <?php include './common/footer_script.php'; ?>
    </body>

</html>
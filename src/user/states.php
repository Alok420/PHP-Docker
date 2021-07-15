<?php 
include './common/session_db.php'; 

?>
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
                            <a href="index.php">Phone Raksha</a>
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
                            
                            <br>
                            <br>
                            <div class="sparkline-list">
                            <h3 class="service_title" style="text-align: center;">Add State</h3>
                            <h5 class="text-center text-success"><?php echo isset($_REQUEST["message"])?$_REQUEST["message"]:""; ?></h5>
                            
                                <form action="../controller/insert2.php" class="p-5 bg-white" method="POST" enctype="multipart/form-data">
                                    <div class="row form-group">
                                        <div class="row form-group col-md-12">
                                
                                            <div class="col-md-6">
                                                <label class="text-black" for="name">Name*</label> 
                                                <input type="text" id="name" name ="name" class="form-control" placeholder="Enter state name" required>
                                            </div>

                                            </div>
                                            <input type="hidden" name="api_key" value="<?php echo $user["api_key"]; ?>">
                                            <input type="hidden" name="tbname" value="user">
                                            <div class="col-md-6">
                                                <input type="submit" value="Add" style="float:right; margin-top: 10px;" class="btn btn-primary py-2 px-4 text-white">
                                            </div>
                                          
                                </form>                                
                            </div>
                             <div class="sparkline-list">
                                <h3 style="margin-top: 5px; padding: 5px;" align="center">All state's List</h3> 
                                <br>
                                <br>
                                <?php
                                $sort = "name asc";
                                $db->showInTable("states", "*", array(), "all", $externallinks = "", array(), $sort);
                                ?>
                            </div>
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
<?php include './common/session_db.php';
$curr_date = date("Y-m-d");
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
                        <a href="index.php"><img class="main-logo" src="img/logo/logo.png" alt="" style="height: 60px; width: 100px" /></a>
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
        <div class="section-admin container-fluid" style="margin-top: 80px;">
            <div class="row admin">
                <div class="col-md-12">
                    <div class="row">
                        <?php
                        $books = $db->select("books");
                        while ($book = $books->fetch_assoc()) {
                        ?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                                <div class="admin-content analysis-progrebar-ctn res-mg-t-15" style="margin-top:">
                                    <img src="../img/books/<?php echo $book['image'] ?>" style="height:100px;">
                                    <h5 align="center"><?php echo "Name:" . $book['name']; ?> <?php echo "Qty:" . $book['quantity'] . " pcs"; ?></h5>
                                    <div class="row vertical-center-box vertical-center-box-tablet">

                                        <div class="col-xs-9 cus-gh-hd-pro" style="text-align: left;margin-top: 10px;">

                                            <?php
                                            $is_borrowed = $db->select('borrow_book', 'id', array('user_id' => $user['id'], 'operatoroc' => 'and', 'book_id' => $book['id']));
                                            if ($is_borrowed->num_rows > 0) {
                                            ?>
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $book['id']; ?>">Read</button>
                                            <?php
                                            } else {
                                            ?>
                                                <a href="../controller/borrow_book.php?id=<?php echo $book['id']; ?>&type=book" class="btn btn-primary">Borrow</a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal<?php echo $book['id']; ?>" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-center">

                                            <h4 class="modal-title"><?php echo $book['name']; ?></h4>
                                            <p align="center"> (<?php echo $book['author']; ?>)</p>
                                        </div>
                                        <div class="modal-body" style="overflow-x: auto;">
                                            <p><?php echo $book['description'] ?>.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php
                        }
                        ?>



                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- <div class="" style="margin-top: 150px;">
            <div class="container-fluid" >
                <div class="row"> -->
    <!-- <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="product-sales-chart">
                            <div class="portlet-title">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="caption pro-sl-hd">
                                            <span class="caption-subject text-uppercase"><b>Product</b></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="actions graph-rp">
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-grey active">
                                                    <input type="radio" name="options" class="toggle" id="option1" checked="">Today</label>
                                                <label class="btn btn-grey">
                                                    <input type="radio" name="options" class="toggle" id="option2">Week</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-inline cus-product-sl-rp">
                                <li>
                                    <h5><i class="fa fa-circle" style="color: #24caa1;"></i>Pant</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle" style="color: #00b5c2;"></i>Shirt</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle" style="color: #ff7f5a;"></i>Saree</h5>
                                </li>
                            </ul>
                            <div id="morris-area-chart" style="height: 356px;"></div>
                        </div>
                    </div> -->
    <!--  <div class="" style="display: flex; width: 100% !important">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <a class="white-box analytics-info-cs mg-b-10 res-mg-t-30" href="payment.php">
                            
                            <h3 class="box-title">Payment</h3>
                            <h2 class="text-right no-margin"> -->
    <?php
    // echo $db->select("payment", "count(id) as total")->fetch_assoc()["total"];
    ?>
    <!-- </h2>
                                        <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-blue"></div>
                                </div>
                        </a>
                        </div>
                        <br>
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <a class="white-box analytics-info-cs mg-b-10 res-mg-t-30" href="pending_order_view.php">
                            <h3 class="box-title">Pending Order</h3>
                            <h2 class="text-right no-margin"> -->
    <?php
    // echo $db->select("order_request", "count(id) as total", array("status" => "pending"))->fetch_assoc()["total"];
    ?>
    <!-- </h2> <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-red"></div>
                                </div>
                        </a>
                    </div>
                    <br>
                    <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <a class="white-box analytics-info-cs mg-b-10" href="completed_order_view.php">
                            <h3 class="box-title">Completed Order</h3>
                            <h2 class="text-right no-margin"> -->
    <?php
    // echo $db->select("order_request", "count(id) as total", array("status" => "active"))->fetch_assoc()["total"];
    ?>
    <!-- </h2> <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-green"></div>
                                </div>
                        </a>
                    </div>
                    <br>
                    </div>
                </div>
            </div>
        </div> -->


    </div>

    <?php include './common/footer_script.php'; ?>
</body>

</html>
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
                        $books = $conn->query("select * from books where id in(select book_id from borrow_book where user_id=" . $user["id"] . ")");
                        if ($books->num_rows > 0) {
                            while ($book = $books->fetch_assoc()) {
                        ?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                                    <div class="admin-content analysis-progrebar-ctn res-mg-t-15" style="margin-top:">
                                        <img src="../img/books/<?php echo $book['image'] ?>" style="height:100px;">
                                        <h5 align="center"><?php echo $book['name']; ?></h5>
                                        <div class="row vertical-center-box vertical-center-box-tablet">
                                            <div class="col-xs-9 cus-gh-hd-pro" style="text-align: left;margin-top: 10px;">

                                                <?php
                                                $is_borrowed = $db->select('borrow_book', 'id', array('user_id' => $user['id'], 'operatoroc' => 'and', 'book_id' => $book['id']));
                                                $borrowed = $is_borrowed->fetch_assoc();
                                                ?>
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $book['id']; ?>">Read</button>
                                                <a href="../controller/borrow_book.php?id=<?php echo $book['id']; ?>&type=return&bb_id=<?php echo $borrowed["id"]; ?>" class="btn btn-primary">Return</a>

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
                        } else {
                            echo "<h3 style='text-align:center;'>No rented book available</h3>";
                        }
                        ?>



                    </div>
                </div>

            </div>

        </div>
    </div>




    </div>

    <?php include './common/footer_script.php'; ?>
</body>

</html>
<?php

?>
<style>
    .comment-scrollbar, .timeline-scrollbar, .messages-scrollbar, .project-list-scrollbar {
    height: 580px !important;
}
</style>
<nav id="sidebar" class="">
    <div class="sidebar-header">
        <a href="./index.php">
            <img src="../img/web_settings/logo.png" style="height: 60px;">
            </a>
    </div>
    <div class="left-custom-menu-adp-wrap comment-scrollbar">
        <nav class="sidebar-nav left-sidebar-menu-pro">
            <ul class="metismenu" id="menu1">
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Users</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="create_user.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">New User</span>
                            </a>
                        </li>
                        <li>
                            <a   href="user.php">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">User list</span></a>
                        </li>
                      
                    </ul>
                </li>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Books</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="add_book.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Book</span>
                            </a>
                        </li>
                        <li>
                            <a   href="book_list.php">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Book list</span></a>
                        </li>
                        <li>
                            <a   href="borrowed_books.php">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Borrowed Books</span></a>
                        </li>
                      
                    </ul>
                </li>
            </ul> 
         
        </nav>
    </div>
</nav>
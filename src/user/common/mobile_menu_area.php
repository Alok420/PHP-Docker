<script>
    // document.addEventListener("DOMContentLoaded", function(event) {
    // document.getElementById("mobile-menu").removeAttribute("style");
    // document.getElementById("id").removeAttribute("style")
// });
</script>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="mobile-menu" id="mobile-menu">
                <nav id="dropdown" >
                  <ul class="metismenu" id="menu1" style="display:none">
            <?php if($user['role'] == 'admin'){  ?>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Affiliates</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="create_user.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">New Affiliates</span>
                            </a>
                        </li>
                        <li>
                            <a   href="user.php?id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates Details</span></a>
                        </li>
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Members</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="user.php?role=super_manager"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Super Managers</span>
                            </a>
                        </li>
                        <li>
                            <a   href="user.php?role=manager">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Managers</span></a>
                        </li>
                        
                        <li>
                            <a   href="user.php?role=affiliate">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates</span></a>
                        </li>
                        <li>
                            <a   href="user.php?role=user">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Total Users</span></a>
                        </li>
                      
                    </ul>
                </li>
            
                 <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Settings</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="create_letter.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Web Setting</span>
                            </a>
                        </li>
                        <li>
                            <a   href="app_page_links.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">App Page links</span>
                            </a>
                        </li>
                        <li>
                            <a   href="kyc.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">KYC</span>
                            </a>
                        </li>
                        <li>
                            <a   href="charges.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Charges</span>
                            </a>
                        </li>
                        <li>
                            <a   href="states.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add State</span>
                            </a>
                        </li>
                        <li>
                            <a   href="cities.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add City</span>
                            </a>
                        </li>
                        <li>
                            <a   href="slide.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Slides</span>
                            </a>
                        </li>
                        
                    </ul>
                   
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Quotes</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                       <li>
                            <a   href="motivational_quotes.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Quotes</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>

                
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Swarn Coin</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                       <li>
                            <a   href="reward_setting.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Setting</span>
                            </a>
                        </li>

                        
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Income Chart</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                       <li>
                            <a   href="income_chart.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Income Chart Setting</span>
                            </a>
                        </li>

                        
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Reward Setting</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                       <li>
                            <a   href="reward_chart.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">User Reward Chart</span>
                            </a>
                        </li>
                        
                        <li>
                            <a   href="member_reward_chart.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Member Reward Chart</span>
                            </a>
                        </li>

                        
                    </ul>
                </li>
              
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Fund Request</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="fund_request.php?role=user"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">User's Request</span>
                            </a>
                        </li>
                        <li>
                            <a   href="fund_request.php?role=member"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Members's Request</span>
                            </a>
                        </li>
                        
                        <li>
                            <a   href="charges_transaction.php?role=user"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">User's Charges History</span>
                            </a>
                        </li>
                        
                        <li>
                            <a   href="charges_transaction.php?role=member"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Members's Charges History</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <!--<li> <a class="has-arrow" href="#" aria-expanded="false">-->
                <!--        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Letters</span></a>-->
                <!--    <ul class="submenu-angle" aria-expanded="false">-->

                <!--        <li>-->
                <!--            <a   href="create_letter.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Create Letter</span>-->
                <!--            </a>-->
                <!--        </li>-->
                        
                <!--    </ul>-->
                <!--</li>-->
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Email</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="compose.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Compose</span>
                            </a>
                        </li>
                        <li>
                            <a   href="inbox.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Inbox</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Notification</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a href="notifications.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Notification</span>
                            </a>
                        </li>
                       
                    </ul>
                </li>
                
                <li> <a  href="payments.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">App Purchased</span></a>
                    
                </li>
                
                <li> <a  href="lucky_quiz.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Lucky Quiz</span></a>
                    
                </li>
                
                <li> <a  href="app_version.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">App Version</span></a>
                    
                </li>
                
                <li> <a onclick="return confirm('Do you really want to logout?');" href="../controller/logout.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Logout</span></a>
                    
                </li>
                
                <?php } ?>
                
                <?php if($user['role'] == 'super_manager'){  ?>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Affiliates</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="create_user.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">New Affiliates</span>
                            </a>
                        </li>
                        <li>
                            <a   href="user.php?id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates Details</span></a>
                        </li>
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Members</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="user.php?role=manager&sponser_id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Managers</span></a>
                        </li>
                        
                        <li>
                            <a   href="user.php?role=affiliate&super_sponser_id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates</span></a>
                        </li>
                        <!--<li>-->
                        <!--    <a   href="user.php?role=user">-->
                        <!--        <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> -->
                        <!--        <span class="mini-sub-pro">Total Users</span></a>-->
                        <!--</li>-->
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Rewards</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="my_rewards.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">My Rewards</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
            
                <li> <a href="download_kyc.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">KYC</span></a>
                    
                </li>
                
                
                 <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Bank Details</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="bank_accounts.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Account</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                 <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Payment Detail</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="payment_requests.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Request Money</span>
                            </a>
                        </li>
                        <li>
                            <a   href="payment_history.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Transaction History</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Email</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="compose.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Compose</span>
                            </a>
                        </li>
                        <li>
                            <a   href="inbox.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Inbox</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a  href="offer_letter.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Offer Letter</span></a>
                    
                </li>
                <li> <a onclick="return confirm('Do you really want to logout?');" href="../controller/logout.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Logout</span></a>
                    
                </li>
                
                <?php } ?>
                
                <?php if($user['role'] == 'manager'){  ?>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Affiliates</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="create_user.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">New Affiliates</span>
                            </a>
                        </li>
                        <li>
                            <a   href="user.php?id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates Details</span></a>
                        </li>
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Members</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="user.php?role=affiliate&sponser_id=<?php echo $user['id'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Affiliates</span></a>
                        </li>
                        <!--<li>-->
                        <!--    <a   href="user.php?role=user">-->
                        <!--        <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> -->
                        <!--        <span class="mini-sub-pro">Total Users</span></a>-->
                        <!--</li>-->
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Rewards</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="my_rewards.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">My Rewards</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                 <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Bank Details</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="bank_accounts.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Account</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <li> <a href="download_kyc.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">KYC</span></a>
                    
                </li>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Payment Detail</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="payment_requests.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Request Money</span>
                            </a>
                        </li>
                        <li>
                            <a   href="payment_history.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Transaction History</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Email</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="compose.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Compose</span>
                            </a>
                        </li>
                        <li>
                            <a   href="inbox.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Inbox</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a onclick="return confirm('Do you really want to logout?');" href="../controller/logout.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Logout</span></a>
                    
                </li>
                
                <li> <a  href="offer_letter.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Offer Letter</span></a>
                    
                </li>
                
                <?php } ?>
                
                
               <?php if($user['role'] == 'affiliate'){ ?> 
               
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">My Team</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="user.php?role=user&referralcode=<?php echo $user['business_promocode'] ?>">
                                <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> 
                                <span class="mini-sub-pro">Team List</span></a>
                        </li>
                        <!--<li>-->
                        <!--    <a   href="user.php?role=user">-->
                        <!--        <i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"> </i> -->
                        <!--        <span class="mini-sub-pro">Total Users</span></a>-->
                        <!--</li>-->
                      
                    </ul>
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Rewards</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="my_rewards.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">My Rewards</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a  href="applink.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">App Link</span></a>
                </li>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Payment Detail</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="payment_requests.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Request Money</span>
                            </a>
                        </li>
                        <li>
                            <a   href="payment_history.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Transaction History</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Bank Details</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="bank_accounts.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Account</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <li> <a href="download_kyc.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">KYC</span></a>
                    
                </li>
                
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Email</span></a>
                    <ul class="submenu-angle" aria-expanded="false">

                        <li>
                            <a   href="compose.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Compose</span>
                            </a>
                        </li>
                        <li>
                            <a   href="inbox.php"><i class="fa fa-list-ol sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Inbox</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li> <a  href="offer_letter.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Offer Letter</span></a>
                    
                </li>
                
                <li> <a onclick="return confirm('Do you really want to logout?');" href="../controller/logout.php" aria-expanded="false">
                        <i class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span class="mini-click-non">Logout</span></a>
                    
                </li>
                <?php } ?>
                
              </ul>  
                </nav>
            </div>
        </div>
    </div>
</div>
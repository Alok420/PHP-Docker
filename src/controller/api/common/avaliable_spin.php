<?php 

// Making connection
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
    $user_api_key = $_POST["api_key"];
    $loginid = $_POST["loginid"];
    $users = $db->select("user", "*", array("id" => $loginid));
    $user = $users->fetch_assoc();
    $db_user_api_key = $user["api_key"];
    if ($db_user_api_key == $user_api_key) {
        
    } else {
        echo json_encode(array("error" => "Invalid API Key"));
        die();
    }
} else {
    echo json_encode(array("error" => "Invalid API Key Or Logged in user id"));
    die();
}

$curr_date = date("Y-m-d H:i:s");
$match_date =  date("Y-m-d H:i:s", strtotime('+3 hours'));

$data = array();

$sort = "id desc";
$reward_settings = $db->select("reward_settings", "*", array(), $sort)->fetch_assoc();

if(isset($_REQUEST['promocode']) && $_REQUEST['promocode'] !== ''){
    $promocode = $_REQUEST['promocode'];
    $user_id = $_REQUEST['user_id'];
    
   
    $sql = "SELECT tmp_wallet FROM `user` WHERE id = $user_id ";
    $users = mysqli_query($conn, $sql)->fetch_assoc();
    
    $wallets = $db->select("wallets", "*", array("user_id"=>$user_id))->fetch_assoc();
    
    if($wallets['swarnacoin_wallet'] > 0){
        
        $data['is_lucky'] = true;
        
        $select_lucky_investors = "SELECT * FROM lucky_investors WHERE (status = 'not_played' and user_id = $user_id )";
        
        $lucky_investors = mysqli_query($conn, $select_lucky_investors);
        
        if($lucky_investors->num_rows > 0){
           $lucky_investor = $lucky_investors->fetch_assoc();
           if($lucky_investor['date'] == date("Y-m-d")){
               $data['lucky_status'] = 'invested';
           }else{
               $data['lucky_status'] = 'play';
               $data['date'] = $lucky_investor['date'];
           }
            
        }else{
            $data['lucky_status'] = 'invest';
        }
        
        // $data['is_rich'] = true;
    }else{
        
        $data['is_lucky'] = false;
        $data['lucky_message'] = $reward_settings['lucky_message'];
        // $data['is_rich'] = false;
    }
    
    
    if($users['tmp_wallet'] > 0){
        
        $sql = "SELECT * FROM daily_spin WHERE user_id = $user_id order by id desc";
        $daily_spins = mysqli_query($conn, $sql);
        
        if($daily_spins->num_rows > 0){
            $daily_spin = $daily_spins->fetch_assoc();
            
            // $effectiveDate = strtotime("+40 minutes", strtotime($idate)); 2021-03-22 13:27:42
            
            // $spin_time =  strtotime("+2 minutes", strtotime($daily_spin['spin_time']));
            $spin_time =  strtotime("+3 hours", strtotime($daily_spin['spin_time']));
            $spin_time = date("Y-m-d H:i:s", $spin_time);
            
            if($curr_date >= $spin_time){
                
                $data['is_diamond'] = true;
            }else{
                $data['is_diamond'] = false;
                $data['diamond_message'] = $reward_settings['diamond_time_message'];
            }
        }else{
            $data['is_diamond'] = true;
        }
        
        
    }else{
        $data['is_diamond'] = false;
        $data['diamond_message'] = $reward_settings['diamond_message'];
        
    }
    $data['is_rich'] = false;
    $data['rich_message'] = $reward_settings['rich_message'];
    
    echo json_encode($data);
    die;
}

?>
<?php

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
$tbname = $_REQUEST["tbname"];
$query = "select * from $tbname";
$result = $conn->query($query);
$data = array();
$i = 0;

$total_app_download = $db->select("user", "*", array('id'=>$_POST["loginid"]))->fetch_assoc();
$total_app = $total_app_download['total_app'];        
while ($row = $result->fetch_assoc()) {
    
    // Only for reward chart
    if($_REQUEST['tbname'] == 'reward_chart'){
        
        if($total_app >= $row['min_app_download'] && $total_app <= $row['max_app_download']){
            $row['is_achieve'] = true;
            
            // Inserting data in reward achievers table
            $reward_achiever_data = array("role"=>"user", "reward_chart_id"=>$row['id'], "user_id"=>$_POST["loginid"]);
            
            $reward_achiever = $db->select("reward_achievers", "*", array("reward_chart_id"=>$row['id']));
            
            if($reward_achiever->num_rows == 0){
                
                $db->insert($reward_achiever_data, "reward_achievers");
               
            }
        }else{
            $row['is_achieve'] = false;
        }
        
    }
    $data[$i] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

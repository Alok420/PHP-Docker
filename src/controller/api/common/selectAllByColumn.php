<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
// if (isset($_POST["api_key"]) && isset($_POST["loginid"])) {
//     $user_api_key = $_POST["api_key"];
//     $loginid = $_POST["loginid"];
//     $users = $db->select("user", "*", array("id" => $loginid));
//     $user = $users->fetch_assoc();
//     $db_user_api_key = $user["api_key"];
//     if ($db_user_api_key == $user_api_key) {
        
//     } else {
//         echo json_encode(array("error" => "Invalid API Key"));
//         die();
//     }
// } else {
//     echo json_encode(array("error" => "Invalid API Key Or Logged in user id"));
//     die();
// }
if (isset($_REQUEST["colval"])) {
    $data = array();
    $column = $_REQUEST['column'];
    if($_REQUEST['tbname'] == 'wallets'){
        $sort = "id desc";
        $rate = $db->select("reward_settings", "*", array(), $sort)->fetch_assoc();
        // $data['rate'] = $rate;
    }
   
    $query = "select * from " . $_REQUEST['tbname'] . " where $column='" . $_REQUEST['colval']."' order by id desc";
    $result = $conn->query($query);
    
    $i= 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i] = $row;
        $data[$i]['rate'] = $rate;
        $i++;
    }
    $string = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $string;
}
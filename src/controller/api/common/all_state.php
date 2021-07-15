<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);

$tbname = $_REQUEST["tbname"];
$query = "select * from $tbname";

$result = $conn->query($query);
$data = array();
$i = 1;
$data[0] = array("id"=>0, "name"=> "Select State");      
while ($row = $result->fetch_assoc()) {
    
    $data[$i] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

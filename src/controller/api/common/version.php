<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$role = $_POST["colval"];
$query = "select * from version where role = '".$role."'";
$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    $data[$i] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

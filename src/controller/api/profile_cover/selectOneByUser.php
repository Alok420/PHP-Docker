<?php
session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
if (isset($_REQUEST["user_id"])) {
    $query = "select * from " . $_REQUEST['tbname'] . " where user_id=" . $_REQUEST['user_id'] . " and status='1'";
    $result = $conn->query($query);
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i] = $row;
        $i++;
    }
    $string = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $string;
}
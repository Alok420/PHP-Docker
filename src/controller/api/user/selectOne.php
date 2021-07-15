<?php

session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
if (isset($_REQUEST["id"])) {
    $query = "select * from " . $_REQUEST['tbname'] . " where id=" . $_REQUEST['id'] . " and blocked='0'";
    $result = $conn->query($query);
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $sql = "select * from user_profile_cover where user_id=" . $row["id"] . " and " . " status=" . "1";
        $datacover = $conn->query($sql);
        while ($onecover = $datacover->fetch_assoc()) {
            $row["profile"]=$$onecover["profile"];
            $row["profile_type"]=$onecover["type"];
        }
        $data[$i] = $row;
        $i++;
    }
    $string = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $string;
}
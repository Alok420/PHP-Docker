<?php

session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
//$co = new connection();
//$host = isset($_POST["host"]) ? $_POST["host"] : "localhost";
//$dbusername = isset($_POST["dbusername"]) ? $_POST["dbusername"] : "root";
//$pass = isset($_POST["dbpassword"]) ? $_POST["dbpassword"] : "";
//$dbname = isset($_POST["dbname"]) ? $_POST["dbname"] : "barcode";
//$conn = $co->connect($host, $dbusername, $pass);
//$co->attach_db($conn, $dbname);
$db = new DB($conn);
if (isset($_REQUEST["id"])) {
    if (!empty($_REQUEST["id"])) {
        $query = "select * from " . $_REQUEST['tbname'] . " where id=" . $_REQUEST['id'];
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
}
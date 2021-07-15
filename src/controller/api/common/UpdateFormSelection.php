<?php

include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
if (isset($_GET["id"]) && isset($_GET["tbname"])) {
    $id = $_GET["id"];
    $table = $_GET["tbname"];
    $column="*";
    if ($_GET["column"]) {
        $column = $_GET["column"];
    } else {
        $column = "*";
    }

    $where = array("id" => $id);
    $data = $db->select($table, $column, $where);
    $one = $data->fetch_assoc();
    echo json_encode($one);
}


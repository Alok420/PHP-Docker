<?php

include '../Config/Connection.php';
include '../Config/DB.php';
$db = new DB($conn);
$returnpath = $_SERVER["HTTP_REFERER"] . "?info=Data deleted";
if (isset($_REQUEST["id"]) && isset($_REQUEST["table_name"])) {
    $info = $db->delete($_REQUEST["id"], $_REQUEST["table_name"]);
    if ($info[0] == 1) {
        ?>
        <script>
            alert("Record Deleted successfully");
            window.history.back();
        </script>
        <?php
        // $db->sendBack($_SERVER);
    } else {
        echo $info[0];
        echo $info[1];
        echo $info[2];
    }
} else {
    echo "Id or table name is not set";
}


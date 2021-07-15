<?php

include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$info = array();
if (isset($_REQUEST["post_id"]) && isset($_REQUEST["tbname"])) {
    $sql = "delete from post_like where post_id=" . $_REQUEST["post_id"] . " and user_id=" . $_REQUEST["user_id"];
    if ($conn->query($sql)) {
        $info["status"] = "success";
        $info["message"] = "Like removed";
        echo json_encode($info);
    } else {
        $info["status"] = "failed";
        $info["message"] = "Like not removed";
        echo json_encode($info);
    }
} else {
    $info["status"] = "failed";
    $info["message"] = "user id or post id is not set";
    echo json_encode($info);
}

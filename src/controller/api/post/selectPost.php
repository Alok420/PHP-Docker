<?php

session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$query = "select * from post order by id desc";
$result = $conn->query($query);
$data = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
    //-----------------------------------total like count---------------------------------------------
    $likedata = $conn->query("select count(*) as totallike from post_like where post_id=" . $row["id"]);
    $total = $likedata->fetch_assoc();
    $row["totallike"] = $total["totallike"];
    //--------------------------------------loggedin user has liked or not-------------------------------
    $islikeddata = $conn->query("select id from post_like where post_id=" . $row["id"] . " and user_id=" . $_POST["user_id"]);
    if ($islikeddata->num_rows > 0) {
        $islikedrow = $islikeddata->fetch_assoc();
        $row["isliked"] = "yes";
    }
    else{
        $row["isliked"] = "no";
    }
    $data[$i] = $row;
    $i++;
}
$string = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $string;

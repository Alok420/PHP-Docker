<?php
session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$headers = getallheaders();
$user_id = $headers["user_id"];
$data = $db->select("user_profile_cover", "*", array("id" => $user_id));
$one = $data->fetch_assoc();
$type = $one["type"];
$query = "update user_profile_cover set status=0 where id<>$user_id and type='$type'";
$conn->query($query);
$info = $db->fileUploadWithTable($_FILES, "user_profile_cover", $user_id, "../img/user", "5m", "jpg,png");
echo $info[0];
echo $info[1];

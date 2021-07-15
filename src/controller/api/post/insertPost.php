<?php session_start();
include '../../../../Config/Connection.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$headers = getallheaders();
$post_id = $headers["post_id"];
$info = $db->fileUploadWithTable($_FILES, "post", $post_id, "../img/post", "105m", "jpg,png,mp4,gif");
echo $info[0];
echo $info[1];

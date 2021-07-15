<?php

session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$location = "../../../../iraw/mg/post";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];
if ($tbname == "lorry_receipt") {
    $location = "../../../../raw/lorry_images";
} else if ($tbname == "post") {
    $location = "../../../../img/post";
}
if (isset($_POST["id"])) {
    $recentinsertedid = $_POST["id"];
}
unset($_POST["tbname"]);
unset($_POST["id"]);
unset($_POST["loginid"]);
unset($_POST["api_key"]);

$info = $db->update($_POST, $tbname, $recentinsertedid);
//var_dump($_POST);
if ($info[0] == 1) {
    if (count($_FILES) > 0) {

        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "jpg,png");
        $return = array();
        $return["status"] = "success";
        $return["message"] = "Data and image saved";
        $return["recentinsertedid"] = $recentinsertedid;
        echo json_encode($return);
    } else {
        $info = array();
        $info["status"] = "success";
        $info["message"] = "Data  saved";
        $info["recentinsertedid"] = $recentinsertedid;
        echo json_encode($info);
    }
} else if ($info[0] == 0) {

    $info["status"] = "failed";
    $info["message"] = "Data not saved";
    echo json_encode($info);
}
    
//     }
//     else {
//         $info["status"] = "failed";
//         $info["message"] = "Not valid user (API NOT MATCHED)";
//     }
// } else {
//     $info["status"] = "failed";
//     $info["message"] = "Not valid user (API NOT MATCHED)";
// }

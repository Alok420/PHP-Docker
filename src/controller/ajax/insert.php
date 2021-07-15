<?php

session_start();

include '../../Config/Connection.php';
include '../../Config/DB.php';
$db = new DB($conn);
$location = "../../img/post";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];
if ($tbname == "projects") {
    $location = "../../images/projects/";
} else if ($tbname == "project_approval") {
    $location = "../../images/approval/";
}
unset($_POST["tbname"]);
$info = $db->insert($_POST, $tbname);
if (isset($_SESSION["recentinsertedid"])) {
    $recentinsertedid = $_SESSION["recentinsertedid"];
}
if ($info[0] == 1) {
    if (count($_FILES) > 0) {
        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "jpg,png,pdf");
        if ($return[0] == 1) {
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
            echo json_encode($return);
        } else {
            $return["status"] = "failed";
            $return["message"] = "Image not saved";
            $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
            echo json_encode($return);
        }
    } else {
        $info["status"] = "success";
        $info["message"] = "Data  saved";
        $info["recentinsertedid"] = $_SESSION["recentinsertedid"];
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

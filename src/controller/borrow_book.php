<?php
session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
$db = new DB($conn);
if (isset($_SESSION["loginid"])) {
    $user_id = $_SESSION["loginid"];
    if (isset($_REQUEST["id"]) && $_REQUEST["type"] == "book") {
        $id = $_REQUEST["id"];
        $db->update(array("quantity" => $db->select("books", "quantity", array("id" => $id))->fetch_assoc()["quantity"] - 1), "books", $id);
        $db->insert(array("book_id" => $id, "user_id" => $user_id), "borrow_book");
         $db->sendBack($_SERVER,"?info=Book rented");
    } else if (isset($_REQUEST["id"]) && $_REQUEST["type"] == "return") {
        $id = $_REQUEST["id"];
        $bb_id = $_REQUEST["bb_id"];
        $db->update(array("quantity" => $db->select("books", "quantity", array("id" => $id))->fetch_assoc()["quantity"] + 1), "books", $id);
        $db->delete($bb_id, "borrow_book");
        $db->sendBack($_SERVER,"?info=Book returned");
    }
   
} else {
    echo "Login First";
}

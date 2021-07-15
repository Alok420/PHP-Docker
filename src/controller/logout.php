<?php
session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
$db = new DB($conn);
session_destroy();
$db->sendTo("../admin/index.php");

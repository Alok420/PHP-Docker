<?php 
// Making connection
session_start();
include '../../../Config/Connection.php';
include '../../../Config/DB.php';
$db = new DB($conn);
// $sql = "SELECT * from motivational_quotes where id=1";
$sql = "SELECT * from motivational_quotes ORDER BY RAND() LIMIT 1";
$motivation = mysqli_query($conn, $sql)->fetch_assoc();
echo json_encode($motivation);
    die;
?>
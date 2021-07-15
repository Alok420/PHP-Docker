<?php
session_start();
include '../Config/Connection.php';
include '../Config/DB.php';
include '../Config/Migration.php';
$db = new DB($conn);

if (isset($_POST["dbname"])) {
  $connection = new connection();
  $host = $_POST["host"];
  $dbname = $_POST["dbname"];
  $username = $_POST["user_name"];
  $password = $_POST["password"];
  $conn = $connection->build($host, $dbname, $username, $password);
}

$configure = new Configuration($conn);
$type = isset($_GET["type"]) ? $_GET["type"] : "creation";
$info2 = $configure->configure($type, isset($_GET["operation"]) ? $_GET["operation"] : "create");

if (isset($_GET["type"]) && $_GET["type"] == "creation") {
  echo 'Selected database is : ' . $db->getSelectedDB();
  echo "<br>Table found in database : " . count($db->getAllTableNameFromDB("wamaship"));
  echo "<br>Configured table : " . count($info2[1]);
} else if (isset($_GET["type"]) && $_GET["type"] == "relation") {
  echo 'Selected database is : ' . $db->getSelectedDB();
  echo "<br>Relation found in database : " . $db->getRelation()->num_rows;
  echo "<br>Configured relation : " . count($info2[1]);
}
 echo "<br>";
$info = $info2[0];
for ($i = 0; $i < count($info); $i++) {
  if ($info[$i] == "0")
    echo '<div style="color:red;">';
  else if ($info[$i] == "1")
    echo '<div style="color:black;">';

  echo $i . " : " . $info[$i] . "<br>";
}


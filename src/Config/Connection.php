<?php
date_default_timezone_set('Asia/Kolkata');
$dbname = "wamaship";
$username = "root";
$password = "example";
$host = "db";
interface connection_declare {

    public function create_database($dbname, $operation, $conn);

    public function connect($host, $username = "root", $pass = "");
}
 
class connection implements connection_declare {

    public function build($host,$db, $username = "root", $pass = "", $operation = "create") {
        $conn = $this->connect($host, $username, $pass);
        $info = $this->create_database($db, $operation, $conn);
        if ($info == "created" || $info == "exist") {
            $this->attach_db($conn, $db);
        } else {
            return $info;
        }

        return $conn;
    }

    public function connect($host = "localhost", $username = "root", $pass = "") {
        $conn = new mysqli($host, $username, $pass);
        if ($connect_error = $conn->error) {
            die("not connected. Error - . $connect_error") ;
        }
        return $conn;
    }

    public function attach_db($conn, $dbname) {
        mysqli_select_db($conn, $dbname);
    }

    public function create_database($dbname, $operation = "", $conn) {
        $all = $conn->query("SELECT SCHEMA_NAME  FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
        if ($all->num_rows > 0) {
            if ($operation == "drop") {
                if ($conn->query("DROP DATABASE $dbname")) {
                    return "dropped";
                } else {
                    return $conn->error;
                }
            } else if ($operation == "create") {
                return "exist";
            } else {
                $this->attach_db($conn, $dbname);
                return "database attached";
            }
        } else {
            echo $conn->error;
            if ($operation == "create") {
                if ($conn->query("CREATE SCHEMA IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8")) {
                    return "created";
                } else {
                    return $conn->error;
                }
            } else if ($operation == "drop") {
                return "no database found";
            }
        }
    }

}
$connection = new connection();
$conn = $connection->connect($host, $username, $password);
$connection->attach_db($conn, $dbname);

?>
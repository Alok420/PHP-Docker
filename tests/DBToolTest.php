<?php

use PHPUnit\Framework\TestCase;

include("connection.php");
include("db.php");
class DBToolTest extends TestCase
{
    public function testSelect()
    {
        $dbname = "wamaship";
        $username = "root";
        $password = "example";
        $host = "db";
        $connection = new Connection;
        $conn = $connection->connect($host, $username, $password);
        $connection->attach_db($conn, $dbname);
        $db = new DB($conn);
        $user = $db->select("user");
        var_dump($user);
        $this->assertIsArray($user->fetch_assoc());
    }
}

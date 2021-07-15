<?php
class Configuration
{
    public $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /*
    create_relate ="creation/relation"
    operation="change/drop" add :drop before table name  to drop table or add : newname to rename after table
    to drop the column set drop: before column and change operation =drop
     */

    public function tablesdata()
    {
        $tables = array(
            "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:200:default 'NA'", "contact" => "varchar:11:unique", "userid" => "varchar:100:unique NOT NULL default 'NA'", "email" => "varchar:50:unique NOT NULL default 'NA'", "password" => "text", "api_key" => "text", "role" => "varchar:50", "created_at" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "updated_at" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "image" => "varchar:100","blocked" => "int:1:default 0"),
            "books" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:200:default 'NA'", "author" => "varchar:100", "description" => "text","quantity" => "int:11","image" => "varchar:100"),
            "borrow_book" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "created_at" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
            
        );
        return $tables;
    }

    /*
    To create the relation
     * (parenttable:primary_key_id(optional)=>childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key without droping foreign key column  put drop: before child table
     * (parenttable:primary_key_id(optional)=>drop:childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key with droping foreign key column  put dropcol: before child table
     * (parenttable:primary_key_id(optional)=>dropcol:childtable:ondelete:onupdate:foreign_key_column_name(optional))
     */

    public function tableRelation()
    {
        $rtable = array(
            "user" => "borrow_book:cascade:cascade:user_id",
            "books" => "borrow_book:cascade:cascade:book_id",           
        );
        return $rtable;
    }

    public function configure($create_relate = "creation", $operation = "create")
    {
        $info2 = array();
        $db = new DB($this->conn);
        ini_set('max_execution_time', 300);
        if ($create_relate == "creation") {
            $info = $db->loadTables($this->tablesdata(), $operation);
            array_push($info2, $info);
            array_push($info2, $this->tablesdata());
        } else if ($create_relate == "relation") {
            $info = $db->relateTable($this->tableRelation());
            array_push($info2, $info);
            array_push($info2, $this->tableRelation());
        }
        return $info2;
    }

    public function loadPages()
    {
    }
}
<?php
$sort = "";
date_default_timezone_set("Asia/Calcutta");
if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];
} else {
    $sort = "id desc";
}

interface DBDeclare
{
    public function preparedQuery($sql, $params = null, $types = null);

    public function selectQuery($sql, $params = null, $types = null);

    public function insertQuery($data, $tbname, $types = null, $recentid = "yes");

    public function updateQuery($data, $tbname, $id, $column = "id", $types = null);

    public function deleteQuery($sql, $params = null, $types = null);

    public function getSelectedDB($conn = "");

    public function getAllTableNameFromDB($dbname = "");

    public function sendTo($path, $param = "");

    public function login($username, $password, $type);

    public function fileUploadWithTable($files, $table, $id = 0, $location = "./", $size = "11m", $type = "jpg,png");

    public function fileUpload($files, $location = "./", $size = "11m", $type = "jpg,png");

    public function showInTable($table, $column = "*", $where = "", $toollist = "all", $externallinks = '', $columntype = "");

    public function showInTableWithoutTool($table, $column = "*", $where = "");

    public function select($table, $column = "*", $where = "", $sort = "id asc");

    public function relateTable($tables);

    public function delete($id, $table);

    public function update($data, $table, $id);

    public function insert($data, $table);

    public function loadTables($tables, $operation);

    public function getIndianDate();

    public function getIndianDateTime();

    public function loginCheck($id, $type);

    public function jqToSqlDate($post, $key);

    public function apiKey($anystring);

    public function userId($name);

    public function userIdWithRange($name, $startrange, $endrange);

    public function sendBack($server);

    public function exist($tbname, $columnvalue);

    public function select_option($tbname, $columnname);

    public function checkApiKey($session_api_key);
    public function get_words_from_string($sentence, $count = 10);
}

class DB implements DBDeclare
{

    public $recentinsertedid;
    public $conn;
    public $returnarray = array();

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function exist($tbname, $columnvalue) {
        $exist = "yes";
        foreach ($columnvalue as $key => $value) {
            $data = $this->select($tbname, "*", array($key => $value));
            if ($data->num_rows > 0) {
                $exist = "yes";
                return $exist;
            } else {
                $exist = "no";
                return $exist;
            }
        }
    }
    function preparedQuery($sql, $params = null, $types = null) {
           
        $conn = $this->conn;
       
        $stmt = $conn->prepare($sql);
        if($params !== null && !is_array($params)){
            $params = array($params); //because $params must be an array()
        }
        if($types == null && $params !== null){
            foreach($params as $param) {        
                if(is_int($param)) {
                    $types .= 'i';              //integer
                } elseif (is_float($param)) {
                    $types .= 'd';              //double
                } elseif (is_string($param)) {
                    $types .= 's';              //string
                } else {
                    $types .= 'b';              //blob and unknown
                }
            }
        }
        
        if($types !== null && $params !== null){
            $stmt->bind_param($types, ...$params);
        }
        if($stmt->execute()){
            return $stmt;
        }else{
            return $stmt->execute();
        }
        
   
    }

    function selectQuery($sql, $params = null, $types = null) {
        
        $conn = $this->conn;    
        $result = $this->preparedQuery($sql, $params, $types);
        
        if($conn->error == ''){
            return $result->get_result();
        }else{
            $conn->error;
        }
      
    }

    function insertQuery($data, $table, $types = null, $recentid = "yes") {
        
        $this->returnarray = array();    
        $conn = $this->conn;
        $value = null;
        $i = 0;
        foreach($data as $column => $value){
           
            if (count($data) >= 1) {
                if ($i == 0) {
                    if ($column == "password") {
                        $pass = password_hash($value, PASSWORD_DEFAULT);
                        $value = $pass;
                    }
                    $table_columns = array();
                    $query = "SHOW COLUMNS from $table";
                    $result = mysqli_query($conn, $query);
                    while($row = $result->fetch_assoc()){
                        $table_columns[] = $row['Field'];
                    }
                    $is_exist = in_array($column, $table_columns);
                    if($is_exist){
                        $i++;
                        $sql = "INSERT INTO $table($column) values(?)";
                        
                        if(is_int($value)) {
                            $types .= 'i';              //integer
                        } elseif (is_float($value)) {
                            $types .= 'd';              //double
                        } elseif (is_string($value)) {
                            $types .= 's';              //string
                        } else {
                            $types .= 'b';              //blob and unknown
                        }
                        
                        $result = $this->preparedQuery($sql, $value, $types);
                        $id = $conn->insert_id;
                        if ($recentid == "yes") {
                            $_SESSION["recentinsertedid"] = $id;
                        }
                        if($conn->error == ''){
                            array_push($this->returnarray, 1);
                            array_push($this->returnarray, "<br>~info: $table inserted:-($sql)");
                        }else{
                            array_push($this->returnarray, 0);
                            array_push($this->returnarray, "<br>~info:not inserted there is some reason " . $conn->error);
                            
                        }
                    }
                   
                    
                }else {
                    if ($column == "password") {
                        $pass = password_hash($value, PASSWORD_DEFAULT);
                        $value = $pass;
                    }
                    $table_columns = array();
                    $query = "SHOW COLUMNS from $table";
                    $result = mysqli_query($conn, $query);
                    while($row = $result->fetch_assoc()){
                        $table_columns[] = $row['Field'];
                    }
                    $is_exist = in_array($column, $table_columns);
                    if($is_exist){
                        $sql = "update $table set $column = ? where id = ? ";
                        $types = null;
                        if(is_int($value)) {
                            $types .= 'i';              //integer
                        } elseif (is_float($value)) {
                            $types .= 'd';              //double
                        } elseif (is_string($value)) {
                            $types .= 's';              //string
                        } else {
                            $types .= 'b';              //blob and unknown
                        }
                        $types .= 'i';                  //for id
                        
                        $value = array($value,$id);
                        $result = $this->preparedQuery($sql, $value, $types);

                        if ($conn->error == '') {
                            array_push($this->returnarray, 1);
                            array_push($this->returnarray, "<br>~info: $table updated:-($sql)");
                        } else {
                            array_push($this->returnarray, 0);
                            array_push($this->returnarray, "<br>~info:not updated there is some reason " . $conn->error);
                        }
                    }
                    
                }
            }
            
        }
        return $this->returnarray;
      
    }

    function updateQuery($data, $table, $id, $col = "id", $types = null) {
        
        $this->returnarray = array();    
        $conn = $this->conn;
        $value = null;
        foreach($data as $column => $value){
           
            if (count($data) >= 1) {
            
                if ($column == "password") {
                    $pass = password_hash($value, PASSWORD_DEFAULT);
                    $value = $pass;
                }

                $table_columns = array();
                $query = "SHOW COLUMNS from $table";
                $result = mysqli_query($conn, $query);
                while($row = $result->fetch_assoc()){
                    $table_columns[] = $row['Field'];
                }
                $is_exist = in_array($column, $table_columns);
                if($is_exist){
                    $sql = "update $table set $column = ? where $col = ? ";
                    $types = null;
                    if(is_int($value)) {
                        $types .= 'i';              //integer
                    } elseif (is_float($value)) {
                        $types .= 'd';              //double
                    } elseif (is_string($value)) {
                        $types .= 's';              //string
                    } else {
                        $types .= 'b';              //blob and unknown
                    }
                    $types .= 'i';                  //for id
                    
                    $value = array($value,$id);

                    $result = $this->preparedQuery($sql, $value, $types);
                    
                    if ($result->error == '') {
                        array_push($this->returnarray, 1);
                        array_push($this->returnarray, "<br>~info: $table updated:-($sql) || Value = $value[0]");
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "<br>~info:not updated there is some reason " . $conn->error);
                    }

                }
                   

                
            }
        }
            
        
        return $this->returnarray;
      
    }

    function deleteQuery($sql, $params = null, $types = null) {
        
        $this->returnarray = array();
        $conn = $this->conn;

        $result = $this->preparedQuery($sql, $params, $types);
         
        if($result->affected_rows){

            array_push($this->returnarray, 1);
            array_push($this->returnarray, "<br>~info: Deleted :- ($sql) | id = $params");
            // return $result->get_result();
        }else{
            array_push($this->returnarray, 0);
            array_push($this->returnarray, "<br>~info:there is some problem in deletion " .$conn->error);
            
        }
        return $this->returnarray;
      
    }

    public function get_words_from_string($sentence, $count = 10)
    {
        preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
        return $matches[0];
    }
    public function apiKey($anystring)
    {
        $rand = rand(0, 100000);
        $rawhashword = $anystring . "" . $rand;
        $hashed = password_hash($rawhashword, PASSWORD_DEFAULT);
        return $hashed;
    }

    public function userId($name)
    {
        $rand = rand(0, 1000);
        $userid = str_replace(" ", "_", $name) . $rand;
        $userid = $userid . "_" . round(microtime(true) * 1000);
        return $userid;
    }

    public function userIdWithRange($name, $startrange, $endrange)
    {
        $rand = rand($startrange, $endrange);
        $userid = str_replace(" ", "_", $name) . $rand;
        return $userid;
    }

    public function loginCheck($id, $type)
    {
        if (isset($_SESSION["roleid"]) && isset($_SESSION["loginid"])) {
            if ($type == "role") {
                if ($id == $_SESSION["roleid"]) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == "user") {
                if ($id == $_SESSION["loginid"]) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getIndianDate()
    {
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y/m/d");
        return $date;
    }

    public function getIndianDateTime()
    {
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y/m/d H:i:s");
        return $date;
    }

    public function getIndianTime()
    {
        date_default_timezone_set("Asia/Calcutta");
        $date = date("H:i:s");
        return $date;
    }

    public function myProfile($table, $columns = "*", $where = "")
    {
        $list = $this->select($table, $columns, $where);
        while ($row = $list->fetch_assoc()) {
?>
            <div class="profile-container" style=" margin: auto;
		     position: relative;
		     box-shadow: 1px 1px 20px gray,-1px -1px 20px gray;
		     width: 80%;
		     border-radius: 10px;
		     vertical-align: middle;">
                <h1 style="text-align: center;">

                    <img style="margin: auto;" src="../images/<?php echo $table; ?>/<?php echo $row["image"] == "avatar-male.png" ?: $row["image"]; ?>" class="img-circle img-responsive" height="100" width="100">
                    <?php
                    if (isset($row["Organization_Name"])) {
                        echo $row["Organization_Name"];
                    } else if (isset($row["name"])) {
                        echo $row["name"];
                    } else if (isset($row["fname"])) {
                        echo $row["fname"];
                    }
                    ?>
                </h1>
                <form action="../controller/update.php" method="post" enctype="multipart/form-data">
                    <?php
                    echo '<table class="table table-bordered table-sm">';
                    foreach ($row as $key => $val) {
                        if ($key == "image") {
                            echo '<tr><td class="left-heading" style=" width: 50%; font-weight: 600;" >' . ucwords(str_replace("_", " ", $key)) . '</td><td class="right-value" id="right_' . $key . '"><input name="' . $key . '" style="border:none!important;" type="file" value="' . $val . '">' . $val . '</td></tr>';
                        } else if ($key == "id") {
                            echo '<tr><th>' . $key . '</th><td><div><input readonly style="border:none; cursor:none;" name="' . $key . '" type="text" value="' . $val . '">    </div></td><tr>';
                        } else {
                            echo '<tr><td class="left-heading" style=" width: 50%; font-weight: 600;" >' . ucwords(str_replace("_", " ", $key)) . '</td><td class="right-value" id="right_' . $key . '"><input name="' . $key . '" style="border:none!important;" type="text" value="' . $val . '"></td></tr>';
                        }
                    }
                    ?>
                    <input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
                    <input type="hidden" value="<?php echo $table; ?>" name="tbname">
                    <tr>
                        <td>
                            <button class="btn btn-warning btn-block" type="submit" id="update">Update</button>
                </form>
                </td>
                <td>

                    <form action="../controller/update.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
                        <input type="hidden" value="<?php echo $table; ?>" name="tbname">
                        <button class="btn btn-danger btn-block" id="delete" type="submit">Delete</button>
                    </form>
                </td>
                </tr>
            <?php
            echo "</table></div>";
        }
    }

    public function myProfileAdvance($table, $columns = "*", $where = "")
    {
        $list = $this->select($table, $columns, $where);
        while ($row = $list->fetch_assoc()) {
            ?>
                <div class="profile-container" style=" margin: auto;
               position: relative;
               box-shadow: 1px 1px 15px -5px gray,-1px -1px 15px -5px gray;
               width: 80%;
               /*border-radius: 10px;*/
               vertical-align: middle;">
                    <h1 style="text-align: center;">
                        <?php
                        if (isset($row["image"]) && $row["image"] != "") {
                        ?>
                            <img style="margin: auto;" src="../img/<?php echo $table . "/" . $row["image"]; ?>" class="img-circle img-responsive" height="100" width="100">
                        <?php
                        }
                        ?>
                        <?php
                        if (isset($row["Organization_Name"])) {
                            echo $row["Organization_Name"];
                        } else if (isset($row["name"])) {
                            echo $row["name"];
                        } else if (isset($row["job_title"])) {
                            echo $row["job_title"];
                        }
                        ?>
                    </h1>
                    <form action="../controller/update.php" method="post" enctype="multipart/form-data">
                        <?php
                        echo '<table class="table table-bordered table-sm"><tr style="border:none;">';
                        foreach ($columns as $key => $val) {
                            echo '<script>function handler(e){alert();$("td[name ="' . $key . '"]").val(e.target.value));</script>';

                            $for_column_key = $key;
                            $keyarr = explode(":", $key);
                            if (count($keyarr) > 1) {
                                $key = $keyarr[0];
                                $keymask = $keyarr[1];
                            } else {
                                $keymask = $key;
                            }
                            $val = $row[$key];
                            if (strpos($key, "_id") != false) {
                                $startpos = 0;
                                $endpos = strpos($key, "_id");
                                $tbname = substr($key, $startpos, ($endpos) - $startpos);
                                $tb_key = explode("_", $key);
                                $name_id = $val;
                                
                                if ($tb_key[0] == "user") {
                                    $users = $this->select($tb_key[0], "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                } else if ($tb_key[0] == "branches" || $tb_key[0] == "industry") {
                                    $users = $this->select($tb_key[0], "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                }
                                //                            if table has name with underscore-------------------
                                else if ($tb_key[0] == "client" && $tb_key[1] == "hr") {
                                    $users = $this->select("client_hr_profile", "id,company_name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["company_name"];
                                } else if ($tb_key[0] == "categories" && $tb_key["1"] == "id") {
                                    $users = $this->select("categories", "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                } else if ($tb_key[0] == "created" && $tb_key[1] == "by") {
                                    $users = $this->select("user", "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                } else if ($tb_key[0] == "ads") {

                                    $users = $this->select("ads", "id,name", array("id" => $val));
                                    if ($users->num_rows > 0) {
                                        $user = $users->fetch_assoc();
                                        $name_id = $user["name"];
                                    }
                                } else if ($tb_key[0] == "ad" && $tb_key[1] == "places") {
                                    $users = $this->select("ad_places", "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                } else if ($tb_key[0] == "article" && $tb_key[1] == "category") {
                                    $users = $this->select("article_category", "id,title", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["title"];
                                }

                                $val = $name_id;
                            }
                            if (is_array($columns)) {

                                $columnlavel2 = $columns[$for_column_key];
                                $colkey;
                                if (is_array($columnlavel2)) {
                                    foreach ($columnlavel2 as $key2 => $val2) {
                                        $colkey = $key2;
                                    }
                                    if ($colkey == "select") {
                                        echo '<th>' . $keymask . '</th><td><div><select onchange=onSelectChange(' . $row["id"] . ',"' . $key . '","' . $table . '",this) name="' . $key . '" class="form-control" style="width:100px;">';
                                        $optionarray = $columnlavel2[$colkey];
                                        for ($coli = 0; $coli < count($optionarray); $coli++) {
                                            $coliarr = explode(":", $optionarray[$coli]);
                                            if (count($coliarr) > 1) {
                                                echo '<option value="' . $coliarr[0] . '">' . $coliarr[1] . '</option>'; //for masking---------------------
                                            } else {
                                                echo '<option value="' . $optionarray[$coli] . '">' . $optionarray[$coli] . '</option>';
                                            }
                                        }
                                        echo '<option selected value="' . $val . '">' . $val . '</option>';
                                        echo '</select></td></tr>';
                                    }
                                } elseif ($columnlavel2 == "file") {
                                    echo '<tr><th>' . $keymask . '</th><td><div><input style="border:none;" name="' . $key . '" type="' . $columns[$for_column_key] . '" value="' . $val . '">    </div><a href="../img/' . $table . '/' . $val . '"><img src="../img/' . $table . '/' . $val . '" style="height:50px;width:50px;"></img> <i class="fa fa-download"></i></a></td><tr>';
                                } elseif ($columnlavel2 == "date") {
                                    echo '<tr><th>' . $keymask . '</th><td><div><input id="' . $key . '" name="' . $key . '" type="hidden" value="' . $val . '"><input style="border:none;" type="date" id="dt" onchange="{$(\'#' . $key . '\').val(this.value)}"/>    </div>' . $val . '</td><tr>';
                                } elseif ($columnlavel2 == "textarea") {
                                    echo '<tr><th>' . $keymask . '</th><td><div><textarea style="border:none;" name="' . $key . '"> ' . $val . '</textarea>   </div></td><tr>';
                                } else if ($key == "id") {
                                    echo '<tr><th>' . $keymask . '</th><td><div><input readonly style="border:none; cursor:none;" name="' . $key . '" type="' . $columns[$for_column_key] . '" value="' . $val . '">    </div></td><tr>';
                                } else {
                                    echo '<tr><th>' . $keymask . '</th><td><div><input style="border:none;" name="' . $key . '" type="' . $columns[$for_column_key] . '" value="' . $val . '">    </div></td><tr>';
                                }
                            } else {
                                if ($key == "image" || $key == "company_logo") {
                                    echo '<tr><td class="left-heading" style=" width: 30%; font-weight: 600;" >' . ucwords(str_replace("_", " ", $key)) . '</td><td class="right-value" id="right_' . $key . '"><input name="' . $key . '" style="border:none!important;" type="file" value="' . $val . '">' . $val . '</td></tr>';
                                } else {
                                    echo '<tr><td class="left-heading" style=" width: 30%; font-weight: 600;" >' . ucwords(str_replace("_", " ", $key)) . '</td><td class="right-value" id="right_' . $key . '"><input name="' . $key . '" style="border:none!important;" type="text" value="' . $val . '"></td></tr>';
                                }
                            }
                        }
                        ?>
                        <input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
                        <input type="hidden" value="<?php echo $_SESSION["api_key"]; ?>" name="api_key">
                        <input type="hidden" value="<?php echo $table; ?>" name="tbname">
                        <tr>
                            <td>
                                <button class="btn btn-warning btn-block" type="submit" id="update">Update</button>
                    </form>
                    </td>
                    <td>
                        <form onsubmit="return confirm('Do you want to delete?')" action="../controller/DeleteController.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
                            <input type="hidden" value="<?php echo $table; ?>" name="table_name">
                            <button class="btn btn-danger" id="delete" type="submit">Delete</button>
                        </form>
                    </td>
                    </tr>
                <?php
                echo "</table></div>";
            }
        }

        public function login($username, $password, $table)
        {
            $returnarray = array();
            $query = "select * from $table where userid='$username' or contact='$username'  or email='$username' and blocked='0'";
            $data = $this->conn->query($query);

            if ($data->num_rows > 0) {
                $onedata = $data->fetch_assoc();
                $hash = $onedata["password"];
                if (password_verify($password, $hash)) {
                    $candidates_id = $onedata["id"];
                    $roles_id = $onedata["role"];
                    $api_key = $onedata["api_key"];
                    $name = $onedata["name"];
                    $_SESSION["loginid"] = $candidates_id;
                    $_SESSION["role"] = $roles_id;
                    $_SESSION["api_key"] = $api_key;
                    $returnarray["status_number"] = 1;
                    $returnarray["userid"] = $candidates_id;
                    $returnarray["role"] = $roles_id;
                    $returnarray["api_key"] = $api_key;
                    $returnarray["name"] = $name;
                    return $returnarray;
                } else {
                    $returnarray["status_number"] = 0;
                    $returnarray["status_message"] = "Password not found";
                }
            } else {
                $returnarray["status_number"] = 0;
                $returnarray["status_message"] = "Username not found";
            }
            return $returnarray;
        }

        function fileUploadWithTable($files, $table, $id = 0, $location = "./", $size = "11m", $type = "jpg,png")
        {

            $returnarray = array();
            $sizearr = str_split($size);
            $sizeinnum = 0;
            $unit = "k";
            if ($id != 0) {
                $this->recentinsertedid = $id;
            } else {
                if (isset($_SESSION["recentinsertedid"])) {
                    $this->recentinsertedid = $_SESSION["recentinsertedid"];
                } else {
                    $this->recentinsertedid = 0;
                }
            }

            for ($i = 0; $i < count($sizearr); $i++) {
                if (ctype_digit($sizearr[$i])) {
                    $sizeinnum .= $sizearr[$i];
                } else {
                    $unit = $sizearr[$i];
                    break;
                }
            }
            if ($unit != "" || $unit != null || $unit != " ") {
                if ($unit == "b") {
                    $size = (int) $sizeinnum;
                } else if ($unit == "k") {
                    $size = (((int) $sizeinnum) * 1024);
                } else if ($unit == "m") {
                    $size = (((int) $sizeinnum) * 1024 * 1024);
                } else if ($unit == "g") {
                    $size = (((int) $sizeinnum) * 1024 * 1024 * 1024);
                }
            } else {
                $size = ((int) $sizeinnum);
            }
            $boolean = false;
            foreach ($files as $key1 => $file) {
                $filenamewextension = $file["name"];
                if (strpos($file["name"], "/") !== false) {
                    $filepart1 = explode("/", $file["name"]);
                    $filenamewextension = end($filepart1);
                } elseif (strpos($file["name"], "\\") !== false) {

                    $filepart1 = explode("\\", $file["name"]);
                    $filenamewextension = end($filepart1);
                } else {
                    $filenamewextension = $file["name"];
                }
                $filenamewextension = str_replace("-", "_", $filenamewextension);
                $milliseconds = round(microtime(true) * 1000);
                $filenamewextension = $milliseconds . $filenamewextension;
                $filepart = explode(".", $file["name"]);
                $extension = end($filepart);
                if ($file["size"] <= $size) {
                    $boolean = true;
                } else {
                    $boolean = false;
                    array_push($returnarray, 0);
                    array_push($returnarray, "<br>File size exceed limits: Limit given=$size byte and file size=" . $file["size"] . " byte");
                }
                if (strpos($type, $extension) !== false) {
                    $boolean = true;
                } else {
                    $boolean = false;
                    array_push($returnarray, 0);
                    array_push($returnarray, "<br>File type not matched: Type attached=$type and file type=" . $extension);
                }
                if ($location == "./") {
                    $name = "$location" . $milliseconds . $file["name"];
                } else {
                    $name = "$location/" . $milliseconds . $file["name"];
                }
                $name = str_replace("-", "_", $name);
                // var_dump($name);
                if ($boolean === true) {
                    $uploadstatus = move_uploaded_file($file["tmp_name"], $name);

                    if ($uploadstatus) {
                        $data = array($key1 => $filenamewextension);
                        //                    var_dump($data);
                        array_push($returnarray, 1);
                        array_push($returnarray, "<br>File uploaded file info: $name");
                        if ($this->recentinsertedid > 0) {
                            $message = $this->update($data, $table, $this->recentinsertedid);
                            array_push($returnarray, $message);
                        } else {
                            $this->insert($data, $table);
                        }
                    } else {
                        array_push($returnarray, 0);
                        array_push($returnarray, "<br>File not uploaded file info: $name");
                        array_push($returnarray, $uploadstatus);
                    }
                } else {
                    array_push($returnarray, 0);
                    array_push($returnarray, "<br>File not uploaded file info: $name");
                }
            }
            return $returnarray;
        }

        function fileUpload($files, $location = "./", $size = "11m", $type = "jpg,png")
        {
            $returnarray = array();
            $sizearr = str_split($size);
            $sizeinnum = 0;
            $unit = "k";
            for ($i = 0; $i < count($sizearr); $i++) {
                if (ctype_digit($sizearr[$i])) {
                    $sizeinnum .= $sizearr[$i];
                } else {
                    $unit = $sizearr[$i];
                    break;
                }
            }
            if ($unit != "" || $unit != null || $unit != " ") {
                if ($unit == "b") {
                    $size = (int) $sizeinnum;
                } else if ($unit == "k") {
                    $size = (((int) $sizeinnum) * 1024);
                } else if ($unit == "m") {
                    $size = (((int) $sizeinnum) * 1024 * 1024);
                } else if ($unit == "g") {
                    $size = (((int) $sizeinnum) * 1024 * 1024 * 1024);
                }
            } else {
                $size = ((int) $sizeinnum);
            }
            $boolean = false;
            foreach ($files as $key1 => $file) {
                $filepart = explode(".", $file["name"]);
                $extension = end($filepart);
                if ($file["size"] <= $size) {
                    $boolean = true;
                } else {
                    $boolean = false;
                    array_push($returnarray, 0);
                    array_push($returnarray, "File size exceed limits: Limit given=$size byte and file size=" . $file["size"] . " byte");
                }
                if (strpos($type, $extension) !== false) {
                    $boolean = true;
                } else {
                    $boolean = false;
                    array_push($returnarray, 0);
                    array_push($returnarray, "File type not matched: Type attached=$type and file type=" . $extension);
                }
                if ($location == "./") {
                    $name = "$location" . time() . $file["name"];
                } else {
                    $name = "$location/" . time() . $file["name"];
                }
                if ($boolean === true) {
                    $uploadstatus = move_uploaded_file($file["tmp_name"], $name);
                    if ($uploadstatus) {
                        array_push($returnarray, 1);
                        array_push($returnarray, "File uploaded file info: $name");
                        array_push($returnarray, $uploadstatus);
                    } else {
                        array_push($returnarray, 0);
                        array_push($returnarray, "File not uploaded file info: $name");
                        array_push($returnarray, $uploadstatus);
                    }
                } else {
                    array_push($returnarray, 0);
                    array_push($returnarray, "File not uploaded file info: $name");
                }
            }
            return $returnarray;
        }
        function uploadFile($files, $table, $id='', $location = "../",  $size = "20m", $type = "jpg,png,jpeg,jfif,webp") {
            
            $returnarray = array();
            $conn = $this->conn;
            $sizearr = str_split($size);
            $sizeinnum = 0;
            $unit = "k";
            for ($i = 0; $i < count($sizearr); $i++) {
                if (ctype_digit($sizearr[$i])) {
                    $sizeinnum .= $sizearr[$i];
                } else {
                    $unit = $sizearr[$i];
                    break;
                }
            }
            if ($unit != "" || $unit != NULL || $unit != " ") {
                if ($unit == "b") {
                    $size = (int) $sizeinnum;
                } else if ($unit == "k") {
                    $size = (((int) $sizeinnum) * 1024);
                } else if ($unit == "m") {
                    $size = (((int) $sizeinnum) * 1024 * 1024);
                } else if ($unit == "g") {
                    $size = (((int) $sizeinnum) * 1024 * 1024 * 1024);
                }
            } else {
                $size = ((int) $sizeinnum);
            }
            $boolean = FALSE;
            foreach ($files as $key1 => $file) {
                
                $filepart = explode(".", $file["name"]);
                $extension = end($filepart);
                if ($file["size"] <= $size) {
                    $boolean = TRUE;
                } else {
                    $boolean = FALSE;
                    $returnarray[] = 0;
                    $returnarray[] = "File size exceed limits: Limit given=$size byte and file size=" . $file["size"] . " byte";
                  
                }
                if (strpos($type, strtolower($extension)) !== false) {
                    $boolean = TRUE;
                } else {
                    $boolean = FALSE;
                    $returnarray[] = 0;
                    $returnarray[] = "File type not matched: Type attached=$type and file type=" . $extension;
                    
                }
                $file_name = time()."_".rand()."_". $file["name"];
                if ($location == "../") {
                    $name = $location."img/".$table."/" .$file_name ;
                } else {
                    $name = "$location/" . $file_name;
                }
                
                if ($boolean === TRUE) {
                    $uploadstatus = move_uploaded_file($file["tmp_name"], $name);
                    if ($uploadstatus) {
                        
                        $table_columns = array();
                        $query = "SHOW COLUMNS from $table";
                        $result = mysqli_query($conn, $query);
                        while($row = $result->fetch_assoc()){
                            $table_columns[] = $row['Field'];
                        }
                        $is_exist = in_array($key1, $table_columns);
                        if($is_exist){

                            if($id == ''){
                                $sql = "INSERT INTO $table($key1) values(?)";
                                
                                $types = "";
                                if(is_int($file_name)) {
                                    $types .= 'i';              //integer
                                } elseif (is_float($file_name)) {
                                    $types .= 'd';              //double
                                } elseif (is_string($file_name)) {
                                    $types .= 's';              //string
                                } else {
                                    $types .= 'b';              //blob and unknown
                                }
                            
                            }else{
                                $types = "";
                                $sql = "update $table set $key1 = ? where id = ? ";
                                $types = null;
                                if(is_int($file_name)) {
                                    $types .= 'i';              //integer
                                } elseif (is_float($file_name)) {
                                    $types .= 'd';              //double
                                } elseif (is_string($file_name)) {
                                    $types .= 's';              //string
                                } else {
                                    $types .= 'b';              //blob and unknown
                                }
                                $types .= 'i';                  //for id
                                
                                $value = array($file_name,$id);
                                $file_name = $value;
                            }
                            
                            $result = $this->preparedQuery($sql, $file_name, $types);
                            
                            if($result->error == ""){
                                $returnarray[] = 1;
                                $returnarray[] = "File uploaded file info: $name SQL - $sql Value = $file_name[0]";
                                $returnarray[] = $uploadstatus;

                            }else{
                                $returnarray[] = 0;
                                $returnarray[] = "File not uploaded file info: $name Error = $conn->error";
                                $returnarray[] = $uploadstatus;
                                
                            }
                        }
                        
                    } else {
                        $returnarray[] = 0;
                        $returnarray[] = "File not uploaded file info: $name";
                        $returnarray[] = $uploadstatus;
                       
                    }
                } else {
                    $returnarray[] = 0;
                    $returnarray[] = "File not uploaded file info: $name";
                   
                }
            }
            return $returnarray;
        }

        function showInTableWithoutTool($table, $column = "*", $where = "")
        {

            $this->returnarray = array();
            $columns = array();
            $list = $this->select($table, $column, $where);
            while ($row = $list->fetch_assoc()) {
                $tempcol = array();
                foreach ($row as $key => $val) {
                    array_push($tempcol, "$key");
                }
                if (count($tempcol) >= count($columns)) {
                    $columns = $tempcol;
                }
            }
                ?>
                <?php
                echo '<div id="search" class="table-responsive table-hover table-striped">' . '<caption><h1 style="text-align:center; background-color:rgba(5,5,5,.7); color:white; margin:0px; text-transform:capitalize;">' . $table . ' Records <span id="hideshow" style="font-size:20px;"></span></h1> </caption>';
                echo '<table class="table table-bordered table-sm table-bordered" >'
                    . '<thead><tr class="thead-light">';
                for ($i = 0; $i < count($columns); $i++) {
                    echo "<th>" . ucwords(str_replace("_", " ", $columns[$i])) . "</th>";
                }
                echo ' </tr></thead><tbody>';
                $j = 0;
                $list = $this->select($table, $column, $where);
                while ($row = $list->fetch_assoc()) {
                    $j++;
                    echo '<tr>';
                    foreach ($row as $key => $val) {
                        echo '<td>' . $val . '</td>';
                    }

                    //            echo "<td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord(" . $row["id"] . ",\"" . $table . "\")'  id='updatebtn'>Update</button></td>";
                    //            echo "<td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord(" . $row["id"] . ",\"" . $table . "\")' id='deletebtn'>Delete</button></td>";
                    echo '</tr>';
                }
                echo '</tbody></table></div></div>';
                echo '<script>
            function deleteRecord(id, table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                        location.reload();
                    } else if (this.readyState < 4) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                    }
                };
                xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                xhttp.send();
            }




        </script>';
                echo " <script>
            function updateRecord(id,column, table) {
                var a = $('#updateinfo').text();
                $('.modal-body').css(\"background-color\", \"red\");
                selectRecordById(id,column,table);
            }
            function selectRecordById(id,column,table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var obj = JSON.parse(xhttp.responseText);
                        var array = new Array();
                        var array2 = new Array();

                        array = Object.keys(obj);
                        array2 = Object.values(obj);
                         $('#updatemodel .modal-body').text(\"\");
                        for (var i = 0; i < Object.keys(obj).length; i++) {
                            $('#updatemodel .modal-body').append('<label>'+ array[i] +'</label><input type=\"text\" value=\"' + array2[i] + '\" id=\"' + array[i] + '\" name=\"' + array[i] + '\" class=\"form-control\">');
                        }
                        $('#updatemodel .modal-body').append('<button class=\"btn btn-default btnsub\" onclick=\"getData()\">Update</button>');
                    }
                };
                xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column\", true);
                xhttp.send();
            }
            function getData() {
                var data = \"\", keys = \"\";
                for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                    var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                    var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                    if (i == $('#updatemodel .modal-body input').length - 1) {
                        data += key + \"=\" + single;

                    } else {
                        data += key + \"=\" + single + \"&\";
                    }
                }
                $.get(\"../controller/UpdateData.php?tbname=$table&\"+ data, function (rdata, status) {
                    alert('updated :---'+rdata);
                    location.reload();
                });
            }
        </script>";
                echo '<div class="modal fade" id="updatemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update data</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

                echo '<div class="modal fade" id="deletemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteinfo">Deleting.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
            }

            function showInTable($table, $column = "*", $where = "", $toollist = "all", $externallinks = '', $columntype = array("key" => "value"), $sort = "id asc")
            {
                $this->returnarray = array();
                $columns = array();
                $list = $this->select($table, $column, $where, $sort);
                //          Search dropdown array in table---------------------------------------
                while ($row = $list->fetch_assoc()) {
                    $tempcol = array();
                    $searchDropDowns = array();
                    foreach ($row as $key => $val) {
                        array_push($tempcol, "$key");
                        array_push($searchDropDowns, "<option value='$key'>$key</option>");
                    }
                    if (count($tempcol) >= count($columns)) {
                        $columns = $tempcol;
                        $searchDropDown = $searchDropDowns;
                    }
                }
                ?>
                <?php
                echo '        
                <script>
                $(document).ready(function () {
                    $("#myInput").on("keyup", function () {
                        var value = $(this).val().toLowerCase();
                        $("#myTable tr").filter(function () {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                });
            </script>
            ';
                // echo '<div class="form-group form-inline" style="margin:0px; "><form action="searchedData.php"><label><strong> ' . $list->num_rows . ' Records in ' . ucwords(str_replace("_", "&nbsp;", $table)) . '&nbsp;</strong></lable> <span><select class="form-control" name="searchCol">';
                // for ($i = 0; $i < count($searchDropDown); $i++) {
                //     echo $searchDropDown[$i];
                // }
                // echo '</select></span><input class="form-control" id="myInput" type="text" name="searching_data" placeholder="Search..">&nbsp;<button type="submit"'
                // . '." class="btn btn-success btn-sm">Search</button><input type="hidden" name="tbname" value="' . $table . '"></form>&nbsp;<button type="button" class="btn btn-success printExcel btn-sm">Export</button></div>';
                echo '<div class="table-responsive table--no-card"><table id="order-table" class="table table-sm table-hover table-striped table-bordered">'
                    . '<thead><tr class="sticky-top" style="background-color:#4272d7;color:white;">';
                if ($toollist == "update") {
                ?>
                    <th></th>
                <?php
                } else if ($toollist == "delete") {
                ?>
                    <th></th>
                <?php
                } else if ($toollist == "all") {
                ?>
                    <th></th>
                    <th></th>
                <?php
                } else {
                ?>

                    <?php
                }
                //            this loop is for table heading --------------------------------------------
                for ($i = 0; $i < count($columns); $i++) {
                    if ($sort == "id asc") {
                        $sort = "id desc";
                    } else if ($sort == "id desc") {
                        $sort = "id asc";
                    }
                    if ($columns[$i] == "id") {
                        echo "<th></i><a href='?sort=$sort' style='text-decoration:none;color:white;'>&blacktriangledown;" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</a></th>";
                    } else {
                        echo "<th style='word-wrap: break-word;paddng:0px;pargin:0px;'>" . ucwords(str_replace("_", " ", $columns[$i])) . "</th>";
                    }
                }
                if ($externallinks == '' || $externallinks == 'Update Image') {
                    echo '</tr></thead><tbody>';
                } else {
                    echo '<th>Extra Links</th></tr></thead><tbody>';
                }
                //            end of table heading---------------------------

                $j = 0;
                $list = $this->select($table, $column, $where, $sort);

                while ($row = $list->fetch_assoc()) {

                    $j++;
                    echo '<tr>';
                    if ($toollist == "update") {
                        if (is_array($column)) {
                    ?>

                            <form method="POST" action="../controller/update.php" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to perform this action?');">

                                <td><button type="submit" class='btn btn-success btn-sm'> Save</button></td>
                            <?php
                        } else {
                            ?>
                                <td><button type="button" class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")' id='updatebtn'>Update</button></td>
                            <?php
                        }
                    } else if ($toollist == "delete") {
                            ?>
                            <td><a type="button" class='btn btn-outline-success btn-sm' onclick="return confirm('Delete this record?')" href="/controller/DeleteController.php?id=<?php echo $row['id'] ?>&table_name=<?php echo $table ?>">Delete</a></td>
                            <?php
                        } else if ($toollist == "all") {
                            if (is_array($column)) {
                                echo '<form method="POST" action="../controller/update.php" enctype="multipart/form-data">';
                            ?>
                                <td><button type="submit" class='btn btn-success btn-sm'> Save</button></td>
                            <?php
                            } else {
                            ?>
                                <td><button type="button" class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")' id='updatebtn'>Update</button></td>
                            <?php
                            }
                            ?>

                            <td><a type="button" class='btn btn-outline-success btn-sm' onclick="return confirm('Delete this record?')" href="/controller/DeleteController.php?id=<?php echo $row['id'] ?>&table_name=<?php echo $table ?>">Delete</a></td>

                        <?php
                        } else {
                        ?>

                        <?php
                        }
                        //          here we set the display name instead of showing id of referenced table-----------------------
                        foreach ($row as $key => $val) {
                            $val = str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($val));

                            $anchor = 0;

                            if ($key == 'role') {
                                if ($val == 'super_manager' || $val == 'manager') {
                                    $anchor = $row['id'];
                                } else {
                                    $anchor = 0;
                                }
                            }
                            if (strpos($key, "_date") > 0) {
                                $phpdate = strtotime($val);
                                $val = date('d-m-Y', $phpdate);
                            }
                            // if (strpos($key, "_at") > 0) {
                            //     $phpdate = strtotime($val);
                            //     $val = date('d-m-Y', $phpdate);
                            // }
                            if (strpos($key, "_id") > 0) {
                                $startpos = 0;
                                $endpos = strpos($key, "_id");
                                $tbname = substr($key, $startpos, ($endpos) - $startpos);
                                $tb_key = explode("_", $key);
                                $name_id = $val;
                                if ($tb_key[0] == "user" || $tb_key[0] == "ads") {
                                    $users = $this->select($tb_key[0], "id,name", array("id" => $val));
                                    while ($user = $users->fetch_assoc()) {
                                        $name_id = $user["name"];
                                    }
                                } else if ($tb_key[0] == "article") {
                                    $users = $this->select("article_category", "id,title", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["title"];
                                } else if ($tb_key[0] == "jobs") {
                                    $users = $this->select("jobs", "id,title", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["title"];
                                } else if ($tb_key[0] == "ad" && $tb_key[1] == "places") {
                                    $users = $this->select("ad_places", "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                } else if ($tb_key[0] == "created" && $tb_key[1] == "by") {
                                    $users = $this->select("user", "id,name", array("id" => $val));
                                    $user = $users->fetch_assoc();
                                    $name_id = $user["name"];
                                }
                                echo '<td><div><a title="Id and name" style="text-decoration:none;padding:2px;" href="detail.php?id=' . $val . '&tbname=' . $tbname . '">' . $val . " - " . $name_id . '</a></div></td>';
                                //--------------------end of displaying name instead of showing id------------------------------------------------
                            } elseif (array_key_exists($key, $columntype)) {
                                $filepath = $columntype[$key];
                                $ext = pathinfo($val, PATHINFO_EXTENSION);
                                echo '<td class="' . $key . '"><div>';
                                if ($ext == "jpg" || $ext == "png" || $ext == "gif" || $ext == "JPG" || $ext == "PNG" || $ext == "GIF") {
                                    echo '<a href="' . $filepath . $val . '"><img height="30" width="30" style"margin:10px; padding:5px;" src="' . $filepath . $val . '"> ' . $val . '</a>';
                                } else {
                                    echo '<a href="' . $filepath . $val . '">' . $val . '</a>';
                                }
                                if (is_array($column)) {
                                    echo '<input name="' . $key . '" type="' . $column[$key] . '" value="' . $val . '">';
                                }
                                echo '</div></td>';
                            } else {
                                if (is_array($column)) {
                                    $columnlavel2 = $column[$key];
                                    $colkey;
                                    if (is_array($columnlavel2)) {
                                        foreach ($columnlavel2 as $key2 => $val2) {
                                            $colkey = $key2;
                                        }
                                        if ($colkey == "select") {
                                            echo '<td class="' . $key . '"><select onchange=onSelectChange(' . $row["id"] . ',"' . $key . '","' . $table . '",this) name="' . $key . '" class="form-control" style="">';
                                            $optionarray = $columnlavel2[$colkey];
                                            for ($coli = 0; $coli < count($optionarray); $coli++) {
                                                echo '<option value="' . $optionarray[$coli] . '">' . $optionarray[$coli] . '</option>';
                                            }
                                            echo '<option selected value="' . $val . '">' . $val . '</option>';
                                            echo '</select></td>';
                                        }
                                    } else {
                                        if ($key == "id") {
                                            echo '<td class="' . $key . '" style="width:20px;"><input style="border:none;border-bottom:thin solid gray;width:100%;background:transparent;" name="' . $key . '" type="' . $column[$key] . '" value="' . $val . '">  </td>';
                                        } else {
                                            echo '<td class="' . $key . '"><input style="border:none;border-bottom:thin solid gray;width:100%;background:transparent;" name="' . $key . '" type="' . $column[$key] . '" value="' . $val . '">  </td>';
                                        }
                                    }
                                } else {
                                    if ($anchor) {
                                        $total_member = 0;
                                        $sql = " select count(id) as total from user where sponser_id = $anchor ";

                                        $total_member = $this->select("user", "count(id) as total", array("sponser_id" => $anchor))->fetch_assoc()['total'];

                                        echo '<td class="' . $key . '"><div><a href="members.php?id=' . $anchor . '">' . $val . ' (' . $total_member . ') </a></div></td>';
                                    } else {

                                        echo '<td class="' . $key . '"><div>' . $val . '</div></td>';
                                    }
                                }
                            }
                        }
                        echo '<input name="tbname" type="hidden" value="' . $table . '">';
                        ?>
                        <input type="hidden" value="<?php echo $_SESSION["api_key"]; ?>" name="api_key">
                        <?php
                        if ($externallinks == "User Detail") {
                            $link = "<td><div><a class='btn btn-success' href='profile.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Edit") {
                            $link = "<td><div><a class='btn btn-success' href='product_detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "User detail") {
                            $link = "<td><div><a class='btn btn-custon-three btn-success' href='user-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Job detail") {
                            $link = "<td><div><a class='btn btn-success' href='job-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Article detail") {
                            $link = "<td><div><a class='btn btn-success' href='article-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Client HR's Detail") {
                            $link = "<td><div><a class='btn btn-success' href='client_hr-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Ad place detail") {
                            $link = "<td><div><a class='btn btn-success' href='ad-place-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        if ($externallinks == "Ads detail") {
                            $link = "<td><div><a class='btn btn-success' href='ads-detail.php?id=" . $row['id'] . "'>$externallinks </a></div></td>";
                            echo $link;
                        }
                        ?>
                            </form>
                            </tr>
                        <?php
                    }
                    echo '</tbody></table></div></div>';
                    echo '<script>
                function deleteRecord(id, table) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("deleteinfo").innerHTML = "loading...";
                            location.reload();
                        } else if (this.readyState < 4) {
                            document.getElementById("deleteinfo").innerHTML = "loading...";
                        }
                    };
                    xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                    xhttp.send();
                }
                
    
    
    
            </script>';
                    echo " <script>
                function updateRecord(id,column,table) {
                    var a = $('#updateinfo').text();
                    $('.modal-body').css(\"background-color\", \"whitesmoke\");
                    selectRecordById(id,column,table);
                }
                function selectRecordById(id,column,table) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            var obj = JSON.parse(xhttp.responseText);
                            var array = new Array();
                            var array2 = new Array();
    
                            array = Object.keys(obj);
                            array2 = Object.values(obj);
                             $('#updatemodel .modal-body').text(\"\");
                            for (var i = 0; i < Object.keys(obj).length; i++) {
                                $('#updatemodel .modal-body').append('<label>'+ array[i].replace(/_/g, ' ').toUpperCase()  +'</label><input  type=\"text\" value=\"' + array2[i] + '\" name=\"' + array[i] + '\" id=\"' + array[i] + 'id\" class=\"form-control\">');
    
                            }
                            $('#updatemodel .modal-body').append('<input type=\"hidden\" name=\"tbname\" value=\"'+table+'\">');
                            $('#updatemodel .modal-body').append('<button class=\"btn btn-success btn-sm btnsub\" onclick=\"getData()\">Update</button>');
                        }
                    };
                    xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column, true);
                    xhttp.send();
                }
                function getData() {
                    var data = \"\", keys = \"\";
                    for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                        var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                        var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                        if (i == $('#updatemodel .modal-body input').length - 1) {
                            data += key + \"=\" + single;
    
                        } else {
                            data += key + \"=\" + single + \"&\";
                        }
                    }
                    $.get(\"../controller/UpdateData.php?\"+ data, function (rdata, status) {
                        alert('updated :---'+status);
                        location.reload();
                    });
                }
            </script>";
                    echo '<div class="modal fade" id="updatemodel" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update data</h4>
                        </div>
                        <div class="modal-body">
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>';

                    echo '<div class="modal fade" id="deletemodel" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                            <p id="deleteinfo">Some text in the modal.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>';
                }

                //-------------------------this function is not tested yet-------------
                function showInTableByQuery($query = "", $toollist = "all", $externallinks = '', $columntype = array("key" => "value"), $sort = "id asc")
                {

                    $this->returnarray = array();
                    $columns = array();
                    $list = $this->select($query, $column, $where, $sort);
                    $searchDropDown = array();

                    while ($row = $list->fetch_assoc()) {
                        $tempcol = array();
                        $searchDropDowns = array();
                        foreach ($row as $key => $val) {
                            array_push($tempcol, "$key");
                            array_push($searchDropDowns, "<option value='$key'>$key</option>");
                        }
                        if (count($tempcol) >= count($columns)) {
                            $columns = $tempcol;
                            $searchDropDown = $searchDropDowns;
                        }
                    }
                        ?>

                        <?php
                        echo '
            <script>
            $(document).ready(function () {

                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        ';
                        echo '<br><div class="form-group form-inline"><form action="searchedData.php"> <label>Search.....</lable> <span><select class="form-control" name="searchCol">  ';
                        for ($i = 0; $i < count($searchDropDown); $i++) {
                            echo $searchDropDown[$i];
                        }
                        echo '</select></span><input class="form-control" id="myInput" type="text" name="searching_data" placeholder="Search.."> <input type="submit" value="Srarch" class="btn btn-outline-success btn-sm"><input type="hidden" name="tbname" value="' . $table . '"></form></div><br>';
                        echo '<table id="myTable" class="table  ">'
                            . '<thead><tr class="" style="background-color:#4272d7; color:white;">';
                        if ($toollist == "update") {
                        ?>
                            <th></th>
                        <?php
                        } else if ($toollist == "delete") {
                        ?>
                            <th></th>
                        <?php
                        } else if ("all") {
                        ?>
                            <th></th>
                            <th></th>
                        <?php
                        } else {
                        ?>

                            <?php
                        }
                        for ($i = 0; $i < count($columns); $i++) {
                            if ($sort == "id asc") {
                                $sort = "id desc";
                            } else if ($sort == "id desc") {
                                $sort = "id asc";
                            }
                            if ($columns[$i] == "id") {
                                echo "<th></i><a href='?sort=$sort' style='text-decoration:none;color:white;text-decoration:underline;'><i class='fa fa-fw fa-sort'>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</a></th>";
                            } else {
                                echo "<th>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</th>";
                            }
                        }
                        echo ' </tr></thead><tbody>';
                        $j = 0;
                        $list = $this->select($table, $column, $where, $sort);
                        while ($row = $list->fetch_assoc()) {
                            $j++;
                            echo '<tr>';

                            if ($toollist == "update") {
                            ?>
                                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")' id='updatebtn'><i class="fas fa-edit"></i></button></td>
                            <?php
                            } else if ($toollist == "delete") {
                            ?>
                                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'><i class="far fa-trash-alt"></i></button></td>
                            <?php
                            } else if ("all") {
                            ?>
                                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")' id='updatebtn'><i class="fas fa-edit"></i></button></td>
                                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'><i class="far fa-trash-alt"></i></button></td>

                            <?php
                            } else {
                            ?>

                            <?php
                            }
                            foreach ($row as $key => $val) {
                                if (strpos($key, "_date") > 0) {
                                    $phpdate = strtotime($val);
                                    $val = date('d-m-Y', $phpdate);
                                }
                                if (strpos($key, "_id") > 0) {
                                    $startpos = 0;
                                    $endpos = strpos($key, "_id");
                                    $tbname = substr($key, $startpos, ($endpos) - $startpos);
                                    echo '<td><a style="text-decoration:underline;padding:2px;" href="detail.php?id=' . $val . '&tbname=' . $tbname . '">' . $val . '</a></td>';
                                } elseif (array_key_exists($key, $columntype)) {
                                    $filepath = $columntype[$key];
                                    $ext = pathinfo($val, PATHINFO_EXTENSION);
                                    if ($ext == "jpg" || $ext == "png" || $ext == "gif") {
                                        echo '<td><a href="' . $filepath . $val . '">' . $val . '<iframe height="30" width="30" style"margin:10px; padding:5px;" src="' . $filepath . $val . '"></iframe>' . $val . '</a></td>';
                                    } else {
                                        echo '<td><a href="' . $filepath . $val . '">' . $val . '</a></td>';
                                    }
                                } else {
                                    echo '<td>' . $val . '</td>';
                                }
                            }
                            if ($externallinks == "addTaskPermissions") {
                                $link = "<td><a href=addTaskPermissions.php?id=" . $row["id"] . ">Add task and permissions</a></td>";
                                //                $startpos = strpos($externallinks, "{");
                                //                $endpos = strpos($externallinks, "}");
                                //                $key = substr($externallinks, $startpos + 1, ($endpos - 1) - $startpos);
                                //                $key2 = '{' . $key . '}';
                                //                $key = $key . "=" . $row[$key];
                                //                $externallinks = str_replace($key2, $key, $externallinks);
                                echo $link;
                            } else if ($externallinks == "addRole") {
                                $link = "<td><a href=addRole.php?id=" . $row["id"] . ">Add role</a></td>";
                                echo $link;
                            } else if ($externallinks == "print_receipt") {
                                $link = "<td><a href=receipt.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                                echo $link;
                            } else if ($externallinks == "Receipt for tip") {
                                $link = "<td><a href=receipt_trip.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                                echo $link;
                            } else if ($externallinks == "Receipt for challan") {
                                $link = "<td><a href=receipt_challan.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                                echo $link;
                            }
                            ?>
                            </tr>
                        <?php
                        }
                        echo '</tbody></table></div></div>';
                        echo '<script>
            function deleteRecord(id, table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                        location.reload();
                    } else if (this.readyState < 4) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                    }
                };
                xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                xhttp.send();
            }




        </script>';
                        echo " <script>
            function updateRecord(id,column,table) {
                var a = $('#updateinfo').text();
                $('.modal-body').css(\"background-color\", \"whitesmoke\");
                selectRecordById(id,column,table);
            }
            function selectRecordById(id,column,table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var obj = JSON.parse(xhttp.responseText);
                        var array = new Array();
                        var array2 = new Array();

                        array = Object.keys(obj);
                        array2 = Object.values(obj);
                         $('#updatemodel .modal-body').text(\"\");
                        for (var i = 0; i < Object.keys(obj).length; i++) {
                            $('#updatemodel .modal-body').append('<br><label>'+ array[i] +'</label><input type=\"text\" id=\"some\" value=\"' + array2[i] + '\" id=\"' + array[i] + '\" id=\"' + array[i] + '\" name=\"' + array[i] + '\" class=\"form-control\">');
                        }
                        $('#updatemodel .modal-body').append('<br><input type=\"hidden\" name=\"tbname\" value=\"'+table+'\">');
                        $('#updatemodel .modal-body').append('<br><button class=\"btn btn-default btnsub\" onclick=\"getData()\">Update</button>');
                    }
                };
                xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column, true);
                xhttp.send();
            }
            function getData() {
                var data = \"\", keys = \"\";
                for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                    var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                    var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                    if (i == $('#updatemodel .modal-body input').length - 1) {
                        data += key + \"=\" + single;

                    } else {
                        data += key + \"=\" + single + \"&\";
                    }
                }
                $.get(\"../controller/UpdateData.php?\"+ data, function (rdata, status) {
                    alert('updated :---'+rdata);
                    location.reload();
                });
            }
        </script>";
                        echo '<div class="modal fade" id="updatemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update data</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

                        echo '<div class="modal fade" id="deletemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteinfo">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
                    }

                    function select($table, $columns = "*", $where = "", $sort = "id asc")
                    {
                        $this->returnarray = array();
                        if (is_array($columns)) {

                            $dummy_col = "";
                            $arraysize = count($columns);
                            $i = 0;
                            foreach ($columns as $key => $value) {
                                $i++;
                                //        masking for columns-----------------------------------
                                $keyarr = explode(":", $key);
                                if (count($keyarr) > 1) {
                                    $key = $keyarr[0];
                                }
                                //         end of column masking-----------------
                                if ($arraysize == $i) {
                                    $dummy_col .= $key . "";
                                } else {
                                    $dummy_col .= $key . ",";
                                }
                            }
                            $columns = $dummy_col;
                        }
                        if ($where != "" && count($where) > 0) {

                            $SQL = "select $columns from $table where ";
                            $i = 0;
                            $j = 0;
                            $operator = "=";
                            $operatoroc = "or";
                            foreach ($where as $column => $value) {
                                if ($column != "urlparam") {
                                    $i++;
                                    if (strpos($column, "conditiontype") !== false) {
                                        $operator = $value;
                                        if ($operator == "like") {
                                            $value = "%$value%";
                                        }
                                    } else if (strpos($column, "operatoroc") !== false) {
                                        $operatoroc = $value;
                                        if ($operator == "like") {
                                            $value = "%$value%";
                                        }
                                    } else {

                                        $j++;
                                        if ($j > 1) {
                                            $column = $operatoroc . " " . $column;
                                        }
                                        $SQL .= "$column $operator '$value' ";

                                        $operator = "=";
                                    }
                                }
                            }
                        } else {
                            $SQL = "select $columns from $table";
                        }
                        $SQL .= " order by $sort";
                        //    echo "$SQL";
                        if (!empty($columns)) {
                            $data = $this->conn->query($SQL);
                            return $data;
                        } else {
                            return $this->conn->error;
                        }
                    }

                    function query($query = "")
                    {
                        $this->returnarray = array();
                        if (!empty($query)) {
                            $data = $this->conn->query($query);
                            return $data;
                        } else {
                            return $this->conn->error;
                        }
                    }

                    function relateTable($tables)
                    {
                        $this->returnarray = array();

                        foreach ($tables as $key => $value) {
                            $childpart = explode(":", $value);

                            if ($childpart[0] != "drop" && $childpart[0] != "dropcol") {
                                if (count($childpart) > 3) {
                                    $foreign_column = $childpart[count($childpart) - 1];
                                } else {
                                    $foreign_column = "$key" . "_id";
                                }
                                if (count($childpart) > 0) {
                                    $on_delete = "on delete " . $childpart[1] or "";
                                    $on_update = "on update " . $childpart[2] or "";
                                    $SQL1 = "alter table $childpart[0] add $foreign_column int(10)";
                                    if ($this->conn->query($SQL1)) {
                                        array_push($this->returnarray, 1);

                                        array_push($this->returnarray, "~info:new column ($foreign_column) added. ");
                                    } else {
                                        array_push($this->returnarray, 0);

                                        array_push($this->returnarray, "~info:Foreign key column not added. $SQL1 " . $this->conn->error);
                                    }
                                    $SQL = "ALTER TABLE $childpart[0] ADD constraint $key" . "_" . "$childpart[0]" . "_" . "fkey_$foreign_column FOREIGN KEY ($foreign_column) REFERENCES $key(id) $on_delete $on_update";
                                    if ($this->conn->query($SQL)) {
                                        array_push($this->returnarray, 1);

                                        array_push($this->returnarray, "~info:Now $key is parrent and $childpart[0] is child ");
                                    } else {
                                        array_push($this->returnarray, 0);

                                        array_push($this->returnarray, "~info: Relation creation was unsuccessful.$SQL " . $this->conn->error);
                                    }
                                } else {
                                    array_push($this->returnarray, 0);

                                    array_push($this->returnarray, "~info:No delete and update rule found " . $this->conn->error);
                                }
                            } else if ($childpart[0] == "drop" || $childpart[0] == "dropcol") {
                                if (count($childpart) > 4) {
                                    $foreign_column = $childpart[count($childpart) - 1];
                                } else {
                                    $foreign_column = "$key" . "_id";
                                }
                                if (count($childpart) > 1) {
                                    if ($childpart[0] == "dropcol") {
                                        $SQL1 = "alter table $childpart[1] drop column $foreign_column";

                                        $SQL = "alter table $childpart[1] drop foreign key " . $key . "_" . $childpart[1] . "_" . "fkey_$foreign_column";
                                        if ($this->conn->query($SQL)) {
                                            array_push($this->returnarray, 1);
                                            array_push($this->returnarray, "~info: Constraint dropped ($key and $childpart[1] relationship is dropped.)");
                                            if ($this->conn->query($SQL1)) {
                                                array_push($this->returnarray, 1);

                                                array_push($this->returnarray, "~info:Column ($key" . "_id) is removed.");
                                            } else {
                                                array_push($this->returnarray, 0);

                                                array_push($this->returnarray, "~info:column not dropped $SQL1 " . $this->conn->error);
                                            }
                                        } else {
                                            array_push($this->returnarray, "~info: Constraint dropping was unsuccessful. $SQL " . $this->conn->error);
                                        }
                                    } else {
                                        $SQL = "alter table $childpart[1] drop foreign key " . $key . "_" . $childpart[1] . "_" . "fkey";
                                        if ($this->conn->query($SQL)) {
                                            array_push($this->returnarray, 1);
                                            array_push($this->returnarray, "~info: Constraint dropped ($key and $childpart[1] relationship is dropped.)");
                                        } else {
                                            array_push($this->returnarray, 0);
                                            array_push($this->returnarray, "~info: Constraint dropping was unsuccessful. $SQL " . $this->conn->error);
                                        }
                                    }
                                }
                            } else if ($operation == "change") {
                            }
                        }
                        return $this->returnarray;
                    }

                    function delete($id, $table)
                    {
                        $this->returnarray = array();

                        $SQL = "delete from $table where id=$id";
                        $m = $this->conn->query($SQL);
                        if ($m) {
                            array_push($this->returnarray, 1);
                            array_push($this->returnarray, "~info:A row id ($id) is deleted from $table");
                        } else {
                            array_push($this->returnarray, 0);
                            array_push($this->returnarray, "~info:there is some problem in deletion " . $this->conn->error);
                        }
                        return $this->returnarray;
                    }

                    function update($data, $table, $id)
                    {
                        //        $this->returnarray = array();

                        foreach ($data as $column => $value) {
                            $value = str_replace("'", "''", $value);
                            if (count($data) > 0) {
                                if ($column == "password") {
                                    $pass = password_hash($value, PASSWORD_DEFAULT);
                                    $value = $pass;
                                }
                                $SQL = "update $table set $column='$value' where id=$id";
                                //    echo $SQL;
                                $m = $this->conn->query($SQL);
                                if ($m) {
                                    array_push($this->returnarray, 1);
                                    array_push($this->returnarray, "~info: $table updated:-($SQL)");
                                } else {
                                    array_push($this->returnarray, 0);
                                    array_push($this->returnarray, "~info:not updated there is some reason " . $this->conn->error);
                                    array_push($this->returnarray, "~info: $table updated:-($SQL)");
                                }
                            }
                        }
                        return $this->returnarray;
                    }

                    function insert($data, $table, $recentid = "yes")
                    {
                        $this->returnarray = array();
                        $i = 0;
                        $id = 0;
                        foreach ($data as $column => $value) {
                            $value = str_replace("'", "''", $value);
                            $i++;
                            if (count($data) >= 1) {
                                if ($i == 1) {
                                    if ($column == "password") {
                                        $pass = password_hash($value, PASSWORD_DEFAULT);
                                        $value = $pass;
                                    }

                                    $SQL = "insert into $table($column) values('$value')";
                                    $m = $this->conn->query($SQL);
                                    $id = $this->conn->insert_id;
                                    if ($recentid == "yes") {
                                        $_SESSION["recentinsertedid"] = $id;
                                    } else {
                                    }
                                    if ($m) {
                                        array_push($this->returnarray, 1);
                                        array_push($this->returnarray, "~info: $table inserted:-($SQL)");
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "~info:not inserted there is some reason " . $this->conn->error);
                                    }
                                } else {
                                    if ($column == "password") {
                                        $pass = password_hash($value, PASSWORD_DEFAULT);
                                        $value = $pass;
                                    }

                                    $SQL = "update $table set $column='$value' where id=$id";
                                    $m = $this->conn->query($SQL);
                                    if ($m) {
                                        array_push($this->returnarray, 1);
                                        array_push($this->returnarray, "~info: $table updated:-($SQL)");
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "~info:not updated there is some reason " . $this->conn->error);
                                    }
                                }
                            }
                        }
                        return $this->returnarray;
                    }

                    function loadTables($tables, $operation)
                    {
                        $this->returnarray = array();
                        if (count($tables) > 0) {

                            foreach ($tables as $key => $value) {
                                //  -------------------------- column level code---------------------------
                                $i = 0;
                                $tables = explode(":", $key);
                                //          echo "<br>~info:$operation  detected-----------------------";
                                if (count($tables) > 1 && $operation == "change") {
                                    array_push($this->returnarray, "~info:Renaming table " . $tables[count($tables) - 2] . " to " . $tables[count($tables) - 1]);
                                    $SQL = "ALTER TABLE " . $tables[count($tables) - 2] . " RENAME " . $tables[count($tables) - 1];
                                    $m = $this->conn->query($SQL);
                                    if ($m) {
                                        array_push($this->returnarray, 1);
                                        array_push($this->returnarray, "table altered " . $SQL);
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "table not altered " . $SQL . " error: " . $this->conn->error);
                                    }
                                } else if (count($tables) > 1 && $operation == "drop") {
                                    if ($tables[0] == "drop") {
                                        $SQL = "drop table " . $tables[count($tables) - 1];
                                        $m = $this->conn->query($SQL);
                                        if ($m) {
                                            array_push($this->returnarray, 1);
                                            array_push($this->returnarray, "~info:" . $tables[count($tables) - 1] . " removed");
                                        } else {
                                            array_push($this->returnarray, 0);
                                            array_push($this->returnarray, "table not removed " . $SQL . " : error " . $this->conn->error);
                                        }
                                    }
                                }
                                //  -------------------------- column level code---------------------------
                                foreach ($value as $column => $type) {
                                    $datatype = "";
                                    $datatypesize = "";
                                    $columnconstraint = "";
                                    $columnconstraint2 = "";
                                    $i++;
                                    $typeinfo = explode(":", $type);
                                    if (count($typeinfo) == 1) {
                                        $datatype = $typeinfo[0];
                                    } else if (count($typeinfo) == 2) {
                                        $datatype = $typeinfo[0];
                                        $datatypesize = $typeinfo[1];
                                    } else if (count($typeinfo) == 3) {
                                        $datatype = $typeinfo[0];
                                        $datatypesize = $typeinfo[1];
                                        $columnconstraint = $typeinfo[2];
                                    } else if (count($typeinfo) == 4) {
                                        $datatype = $typeinfo[0];
                                        $datatypesize = $typeinfo[1];
                                        $columnconstraint = $typeinfo[2];
                                        $columnconstraint2 = $typeinfo[3];
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "You have passed multiple constraint that is not supported max 2 constraint : " . $SQL);
                                    }
                                    if (count($value) > 0) {
                                        if ($operation == "create") {
                                            //                            echo "<br>~info:create table selected-------------";
                                            if ($i == 1) {
                                                $SQL = "create table IF NOT EXISTS $key($column $datatype($datatypesize) $columnconstraint $columnconstraint2)";
                                                //                                echo "<br>$SQL<br>";
                                                if ($this->conn->query($SQL)) {
                                                    //                    array_push($this->returnarray, "New table ");
                                                    array_push($this->returnarray, 1);
                                                    array_push($this->returnarray, "table created " . $SQL);
                                                } else {
                                                    array_push($this->returnarray, 0);
                                                    array_push($this->returnarray, "Table not created because of " . $this->conn->error . " : " . $SQL);
                                                }
                                            } else {
                                                if (empty($datatypesize)) {
                                                    $SQL = "ALTER TABLE $key ADD $column $datatype $columnconstraint $columnconstraint2";
                                                } else {
                                                    $SQL = "ALTER TABLE $key ADD $column $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                                }
                                                //                                echo "<br>$SQL<br>";
                                                if ($this->conn->query($SQL)) {
                                                    array_push($this->returnarray, 1);
                                                    array_push($this->returnarray, "table altered " . $SQL);
                                                } else {
                                                    array_push($this->returnarray, 0);
                                                    array_push($this->returnarray, "Table not created because of " . $this->conn->error . " : " . $SQL);
                                                }
                                            }
                                        } else if ($operation == "change") {
                                            $cols = explode(":", $column);

                                            if (count($cols) > 1) {
                                                if (count($tables) > 0) {
                                                    $SQL = "ALTER TABLE " . (count($tables) - 1) . " change " . $cols[(count($cols) - 1)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                                    //                                    echo "$SQL";
                                                    $m = $this->conn->query($SQL);
                                                    if ($m) {

                                                        array_push($this->returnarray, 1);
                                                        array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                                    } else {
                                                        array_push($this->returnarray, 0);
                                                        array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                                    }
                                                } else {
                                                    $SQL = "ALTER TABLE " . $tables[(count($tables) - 1)] . " change " . $cols[(count($cols) - 2)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                                    //                                    echo "$SQL";
                                                    $m = $this->conn->query($SQL);
                                                    if ($m) {
                                                        array_push($this->returnarray, 1);
                                                        array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                                    } else {
                                                        array_push($this->returnarray, 0);
                                                        array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                                    }
                                                }
                                            } else if (count($cols) == 1) {
                                                //                                    $SQL = "ALTER TABLE " . $tables[(count($tables) - 1)] . " change " . $cols[(count($cols) - 1)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                                //                                    $m = $this->conn->query($SQL);
                                                //                                    if ($m) {
                                                //                                        array_push($this->returnarray, 1);
                                                //                                        array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                                //                                    } else {
                                                //                                        array_push($this->returnarray, 0);
                                                //                                        array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                                //                                    }
                                            }
                                        }
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "please pass some column: " . $SQL);
                                    }
                                }
                            }
                        }
                        return $this->returnarray;
                    }

                    public function jqToSqlDate($post, $key)
                    {
                        $form_date = $post[$key];
                        $date = DateTime::createFromFormat('m/d/Y', $form_date);
                        return $date->format("Y-m-d");
                    }

                    public function sendBack($server, $info = "?info=Record modified successfully")
                    {
                        $returnpath = "";
                        $url = $server["HTTP_REFERER"];
                        if (strpos($url, '?')) {
                            $returnpath = $url . "&" . str_replace("?", "", $info);
                        } else {
                            $returnpath = $url . $info;
                        }

                        echo '<script>window.location.href="' . $returnpath . '";</script>';
                    }

                    public function select_option($tbname, $columnname, $col = "*", $where = "")
                    {
                        if ($where != "") {
                            $data = $this->select($tbname, $col, $where);
                        } else {
                            $data = $this->select($tbname, $col);
                        }

                        while ($one = $data->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $one["id"]; ?>"><?php echo $one[$columnname]; ?></option>
                        <?php
                        }
                    }

                    public function select_option_withcolasval($tbname, $columnname)
                    {
                        $data = $this->select($tbname);

                        while ($one = $data->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $one[$columnname]; ?>"><?php echo $one[$columnname]; ?></option>
                <?php
                        }
                    }
                    public function getAllDataOfOneCol($tbname, $columnname, $value_field_name = "")
                    {
                        $data = $this->select($tbname, "id,$columnname");
                        $alldata = array();
                        while ($one = $data->fetch_assoc()) {
                            if ($value_field_name != "") {
                                array_push($alldata, $one["$value_field_name"] . ":" . $one["$columnname"]);
                            } else
                                array_push($alldata, $one["$columnname"]);
                        }
                        return $alldata;
                    }
                    public function sendTo($path, $param = "")
                    {
                        $returnpath = "";
                        if ($param == "") {

                            echo '<script>window.location.href="' . $path . '";</script>';
                        } else
                            echo '<script>window.location.href="' . $path . '?' . $param . '";</script>';
                    }

                    public function checkApiKey($session_api_key)
                    {
                        if ($this->select("user", "*", array("api_key" => $session_api_key))->num_rows > 0) {
                            return true;
                        } else {
                            return false;
                        }
                    }

                    public function getAllTableNameFromDB($dbname = "")
                    {
                        $tablelist = array();
                        $sql = "show tables from " . $this->getSelectedDB();
                        $tables = $this->conn->query($sql);
                        if ($dbname == "") {

                            while ($table = $tables->fetch_assoc()) {
                                array_push($tablelist, $table["Tables_in_sofrich"]);
                            }
                        } else {
                            $tables = $this->conn->query("show tables from $dbname");
                            while ($table = $tables->fetch_assoc()) {
                                // var_dump($table["Tables_in_"."$dbname"]);die;
                                array_push($tablelist, $table["Tables_in_" . "$dbname"]);
                            }
                        }
                        return $tablelist;
                    }

                    public function getSelectedDB($conn = "")
                    {
                        if ($conn = "") {
                            return $conn->query("SELECT DATABASE()")->fetch_array()[0];
                        } else {
                            return $this->conn->query("SELECT DATABASE()")->fetch_array()[0];
                        }
                    }

                    public function getRelation()
                    {
                        return $this->conn->query("SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '" . $this->getSelectedDB() . "'");
                    }
                    public function dd($anything)
                    {
                        var_dump($anything);
                        die;
                    }
                    public function unsetExtraColumn($data, $tbname){
                        $conn = $this->conn;
                        $this->returnarray = array();
                        $table_columns = array();
                        $query = "SHOW COLUMNS from $tbname";
                        $result = mysqli_query($conn, $query);
                        while($row = $result->fetch_assoc()){
                            $table_columns[] = $row['Field'];
                        }
                        $counter = 0;
                        $unset_column = array();
                        foreach($_POST as $column => $value){
                            $is_exist = in_array($column, $table_columns);
                            if(!$is_exist){
                                $counter++;
                                $unset_column[] = $column;
                                unset($_POST[$column]);
                                
                                if(isset($_POST["id"])){
                                    unset($_POST["id"]);
                                }
                               
                            }
                            
                        }
            
                        if($counter > 0){
                            return $response = array("status"=>"1", "count"=>$counter,"columns"=>$unset_column);
                        }else{
                            return $response = array("status"=>"0", "count"=>$counter);
                        }
                    }
                }

<?php
class DBHandler
{
    private $servername;
    private $username;
    private $password;
    private $dbname;

    public function dbConnect()
    {        
        try 
        {
            
            $this->servername = HOST;
            $this->username = USER_NAME;
            $this->password = PASSWORD;
            $this->dbname = DBNAME;
            $conString = 'mysql:host='.$this->servername.';dbname='.$this->dbname;
            $pdo = new PDO($conString, $this->username, $this->password);                
            return $pdo;
        }
        catch(PDOException $e) 
        {                 
            echo 'Connection did not work out!';exit;
        }
    }
    public function save($data,$table_name)
    {
        if(!empty($data) && $table_name!='')
        {
            $fields_array = array();
            $values_array = array();
            $set_array = array();
            foreach($data as $key=>$val)
            {
                $fields_array[] = $key;
                $values_array[] = $val;
                $set_array[] = '?';
            }
            $fields_array[] = 'sender_sub_dt_time';
            $values_array[] = date('Y-m-d H:i:s');
            $set_array[] = '?';
            $sql_string = 'INSERT INTO '.$table_name.' ('.implode(', ',$fields_array).') VALUES ('.implode(', ',$set_array).')';
            $stmt = $this->dbConnect()->prepare($sql_string);
            if($stmt->execute($values_array))
            {
                return true;
            }
            else
            {            
                return false;
            }
        }
		return false;
	}
}
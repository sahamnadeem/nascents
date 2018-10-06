<?php
/**
 * 
 */
debug_backtrace() || die ("Direct access not permitted");

class DataBase
{
    public $DBUser = 'root';
    public $DBPass = '';
    public $DBServer = 'localhost';
    public $DBName = 'api_v2';

    public function connect($sqlite=''){
        if ($sqlite) {
            echo "string";
        }else{
            try {  
                $strDSN = "mysql:host=$this->DBServer;dbname=$this->DBName";  
                $username = $this->DBUser;
                $pass = $this->DBPass;
                // $conn = new PDO($strDSN, $username, $pass);
                $conn = new PDO('sqlite:./data.sqlite');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo 'connected';
                return $conn;
            }catch (PDOException $e) {  
                echo 'Error: ' . $e->getMessage();   
            }
        }
    }
}
?>
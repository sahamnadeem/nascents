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

    public function connect(){
         try {  
            $strDSN = "mysql:host=$this->DBServer;dbname=$this->DBName";  
            $username = $this->DBUser;
            $pass = $this->DBPass;
            $conn = new PDO($strDSN, $username, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'connected';
            return $conn;
         }   
        catch (PDOException $e) {  
            echo 'Error: ' . $e->getMessage();   
        }  
    }
}
?>
<?php

/**
 * 
 */



class ClientMiddleware
{
	
	function __construct()
	{

	}

	public static function getclient($data){
		$client_id = $data['client_id'];
		$client_secret = $data['client_secret'];
		$db = new DataBase(); 
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM api_clients WHERE client_id='$client_id' AND client_secret='$client_secret'");
		$stmt->execute(); 
		$client = $stmt->fetch();
		if ($client) {
			return $client;
		}else{
			return false;
		}
	}
}
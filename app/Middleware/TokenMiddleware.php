<?php
/**
 * 
 */
//use Utils\RandomStringGenerator;
include('./libs/TokenGenerator.php');
debug_backtrace() || die ("Direct access not permitted");

class TokenMiddleware
{
	
	function __construct()
	{

	}

	public static function getToken($array, $client_id){
		$update = TokenMiddleware::chechToken($array['id'], $client_id);
		if ($update != False) {
			$generator = new TokenGenerator();
			$tokenLength = 128;
			$access_token = $generator->generate($tokenLength);
			$refresh_token = $generator->generate($tokenLength);
			$sql = "UPDATE access_tokens SET access_token='".$access_token."', refresh_token='".$refresh_token."' WHERE user_id=".$array['id']." AND client_id='".$client_id."'";
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				$_SESSION['client_id'] = $client_id;
				$response = array("access_token"=>"{$access_token}", "refresh_token"=>"{$refresh_token}","user" => $array['id']);
			    header('Content-Type: application/json');
			    http_response_code(200);
			    echo json_encode($response);
			}catch (PDOException $e) {

			}
		}else{
			$generator = new TokenGenerator();
			$tokenLength = 128;
			$access_token = $generator->generate($tokenLength);
			$refresh_token = $generator->generate($tokenLength);
			$sql = "INSERT INTO access_tokens (user_id,client_id,access_token, refresh_token,expires_in) VALUES 
			(".$array['id'].",'".$client_id."','".$access_token."','".$refresh_token."',12)";
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				$response = array("access_token"=>"{$access_token}", "refresh_token"=>"{$refresh_token}","user" => $array['id']);
			    header('Content-Type: application/json');
			    http_response_code(200);
			    echo json_encode($response);
			}catch (PDOException $e) {

			}
		}
	}

	private static function chechToken($id, $client_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM access_tokens WHERE user_id=$id AND client_id='$client_id'");
			$stmt->execute(); 
			$user = $stmt->fetch();
			if ($user){
				return $user;
			}else{
				return False;
			}
		}catch(PDOException $e){

		}
	}

	public static function getTokenHeader(){
		foreach (getallheaders() as $name => $value) 
		{
		    if ($name == "Authorization") {
		    	$valueid = substr($value,0,6);
		    	if ($valueid == "Bearer") {
		    		$Token['token'] = substr($value,7);
		    	}
		    }
		    if ($name == "client_id") {
		    	$Token['client_id'] = $value;
		    }
		}
		if (isset($Token)) {
			return $Token;
		}else{
			return false;
		}
	}

	public static function checkToken($Token){
		$token = $Token['token'];
		$client_id = $Token['client_id'];
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM access_tokens WHERE access_token='$token' AND client_id='$client_id'");
			//echo "SELECT * FROM access_tokens WHERE access_token='$token' AND client_id='$client_id'";
			$stmt->execute(); 
			$access = $stmt->fetch();
			$then = $access['created_at'];
			date_default_timezone_set("Asia/Karachi");
			$then = new DateTime($then);
			$now = new DateTime();
			$sinceThen = $then->diff($now);
			if ($sinceThen->d > 0) {
				$sinceThen->h = $sinceThen->h + ($sinceThen->d*24);
			}
			if ($access && $sinceThen->h <= $access['expires_in']){
				return $access;
			}else{
				return False;
			}
		}catch(PDOException $e){
			$e->getMessage();
		}
	}

	public static function refreshToken($token){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM access_tokens WHERE refresh_token=$token");
			$stmt->execute(); 
			$user = $stmt->fetch();
			$then = $access['created_at'];
			date_default_timezone_set("Asia/Karachi");
			$then = new DateTime($then);
			$now = new DateTime();
			$sinceThen = $then->diff($now);
			if ($sinceThen->d > 0) {
				$sinceThen->h = $sinceThen->h + ($sinceThen->d*24);
			}
			if ($user){
				return $user;
			}else{
				return False;
			}
		}catch(PDOException $e){

		}
	}

	public static function checkreset($user_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM password_reset WHERE user_id=$user_id");
			$stmt->execute(); 
			$user = $stmt->fetch();
			if ($user){
				return $user;
			}else{
				return null;
			}
		}catch(PDOException $e){
			return null;
		}
	}

	public static function reset_pass($user_id){
		$user = TokenMiddleware::checkreset($user_id);
		if ($user != null) {
			$generator = new TokenGenerator();
			$tokenLength = 128;
			$remember_token = $generator->generate($tokenLength);
			$sql = "UPDATE password_reset SET remember_token='$remember_token' WHERE user_id=$user_id";
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				$response = array("remember_token"=>"{$remember_token}");
			    header('Content-Type: application/json');
			    http_response_code(200);
				echo json_encode($response);
			}catch (PDOException $e) {
				
			}
		}else{
			$generator = new TokenGenerator();
			$tokenLength = 128;
			$remember_token = $generator->generate($tokenLength);
			$sql = "INSERT INTO password_reset 
			(user_id,remember_token,expires_in) 
			VALUES ($user_id,'$remember_token',2)";
			echo "$sql";
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				$response = array("remember_token"=>"{$remember_token}");
			    header('Content-Type: application/json');
			    http_response_code(200);
			    echo json_encode($response);
			}catch (PDOException $e) {

			}
		}
	}
}
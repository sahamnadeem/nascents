<?php

/**
 * 
 */


class Location
{
	
	function __construct()
	{
		
	}

	public static function createorupdate($user, $data){
		$location = Location::getlocation($user, $data);
		$str = "";
		$stro = "";
		$db = new DataBase(); 
		$conn = $db->connect();
		if($location == False){
			foreach ($data as $key => $value) {
				$str = $str.$key.", ";
				$stro = $stro.":".$key.", ";
			}
			$data['user_id'] = $user;
			$str = $str."user_id";
			$stro = $stro.":user_id";
			$sql = "INSERT INTO user_location ($str) VALUES ($stro)";
			$stmt= $conn->prepare($sql);
			$stmt->execute($data);
			$location = Location::getlocation($user, $data);
			return $location;
		}else{
			foreach ($data as $key => $value) {
				$str = $str.$key." = :".$key.", ";
			}
			$str = substr($str,0,-2);
			$sql = "UPDATE user_location SET $str WHERE user_id=$user";
			$stmt= $conn->prepare($sql);
			$stmt->execute($data);
			$location = Location::getlocation($user);
			return $location;
		}
	}

	public static function getlocation($user){
		$sql = "SELECT * FROM user_location WHERE user_id=$user";
		$str = "";
		$stro = "";
		$db = new DataBase(); 
		$conn = $db->connect();
		$stmt= $conn->prepare($sql);
		$stmt->execute(); 
		$location = $stmt->fetch();
		if ($location != null) {
			return $location;
		}else{
			return null;
		}
	}

	public static function createorupdateselloc($user, $data){
		$location = Location::getlocationbySel($user);
		$str = "";
		$stro = "";
		$db = new DataBase(); 
		$conn = $db->connect();
		if($location == null){
			foreach ($data as $key => $value) {
				$str = $str.$key.", ";
				$stro = $stro.":".$key.", ";
			}
			$str = substr($str,0,-2);
			$stro = substr($stro,0,-2);
			$sql = "INSERT INTO seller_location ($str) VALUES ($stro)";
			$stmt= $conn->prepare($sql);
			$stmt->execute($data);
			$location = Location::getlocationbySel($user);
			return $location;
		}else{
			foreach ($data as $key => $value) {
				$str = $str.$key." = :".$key.", ";
			}
			$str = substr($str,0,-2);
			$sql = "UPDATE seller_location SET $str WHERE seller_id=$user";
			$stmt= $conn->prepare($sql);
			$stmt->execute($data);
			$location = Location::getlocationbySel($user);
			return $location;
		}
	}

	public static function getlocationbySel($seller){
		$sql = "SELECT * FROM seller_location WHERE seller_id=$seller";
		$str = "";
		$stro = "";
		$db = new DataBase(); 
		$conn = $db->connect();
		$stmt= $conn->prepare($sql);
		$stmt->execute(); 
		$location = $stmt->fetch();
		if ($location != null) {
			return $location;
		}else{
			return null;
		}
	}
}
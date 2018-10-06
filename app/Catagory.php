<?php

/**
 * 
 */
class Catagory
{
	
	function __construct()
	{
		
	}

	public static function getbyid($id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM catagories WHERE id=$id");
			$stmt->execute(); 
			$seller = $stmt->fetch();
			if ($seller != null) {
				return $seller;
			}else{
				$Error = array('Error'=>'No Catagory found');
				header('Content-Type: application/json');
				http_response_code(404);
				echo json_encode($Error);
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}
	public static function get(){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter = 0;
		try{
			$stmt = $conn->prepare("SELECT * FROM catagories");
			$stmt->execute(); 
			while ($catagory = $stmt->fetch()){
				$catagories[$counter] = array(
				    'id'=> $catagory['id'],
				    'title'=>$catagory['title']
				    );
				$counter++;
			}
			if (isset($catagories) && $catagories != null) {
				return $catagories;
			}else{
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}

	public static function CreateSubCat($data, $user_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$seller = Seller::getmy($user_id);
		if ($seller['id']) {
			try{
				$stmt = $conn->prepare("INSERT INTO seller_cats (seller_id,cat_id,title) VALUES ($seller[id],:cat_id,:title)");
				try{
					$stmt->execute($data);
					return true;
				}catch(PDOException $e){
					return false;
				}
			}catch(PEOException $e){
				return false;
			}
		}
	}

	public static function GetSubCat($seller_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter=0;
		if ($seller_id) {
			try{
				$stmt = $conn->prepare("SELECT * FROM seller_cats WHERE seller_id=$seller_id");
				$stmt->execute(); 
				while ($cats = $stmt->fetch()) {
					$catagories[$counter] = array(
						"id"=>$cats['id'],
						"title"=>$cats['title'],
						"timestramps"=>$cats['created_at']
					);
					$counter++;
				}
				if(isset($catagories)){
					return $catagories;
				}else{
					return false;
				}
			}catch(PEOException $e){
				return false;
			}
		}
	}

	public static function Deletecat($cat_id, $user_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$seller = Seller::getmy($user_id);
		$seller_id = $seller['id'];
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_cats WHERE id=$cat_id AND seller_id=$seller_id");
			$stmt->execute();
			$data = $stmt->fetch();
			if ($data) {
				$stmt = $conn->prepare("DELETE FROM seller_cats WHERE id=$cat_id AND seller_id=$seller_id");
				$stmt->execute();
				return true;
			}else{
				return false;
			}
		}catch(PEOException $e){
			return false;
		}
	}
}
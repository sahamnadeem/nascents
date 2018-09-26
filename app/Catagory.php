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
				$stmt->execute($data);
				return true;
			}catch(PEOException $e){
				$Error = array('Error'=>'Catagory Already Exists');
				header('Content-Type: application/json');
				http_response_code(504);
				echo json_encode($Error);
			}
		}
	}
}
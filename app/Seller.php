<?php

/**
 * 
 */


class Seller 
{
	
	function __construct()
	{

	}

	public static function getmy($user){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_info WHERE user_id=$user");
			$stmt->execute(); 
			$seller = $stmt->fetch();
			if ($seller != null) {
				return $seller;
			}else{
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}
	public static function create($data, $user){
		if (isset($data['id'])) {
			$Error = array('Error'=>'Invalid Perameters where Given');
			header('Content-Type: application/json');
			http_response_code(404);
			echo json_encode($Error);
		}else{
				if ($data==null) {
					$Error = array('Null Value'=>'Nothing found to add');
				    header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($Error);
				    return False;
				}else{
					$sellero = Seller::getmy($user);
					if($sellero == false ){
						$str = "";
						$stro = "";
						$sql = "";
						foreach ($data as $key => $value) {
							$str = $str.$key.", ";
							$stro = $stro.":".$key.", ";
						}
						$data['user_id'] = $user;
						$str = $str."user_id";
						$stro = $stro.":user_id";
						$sql = "INSERT INTO seller_info ($str) VALUES ($stro)";
						$db = new DataBase(); 
						$conn = $db->connect();
						try{
							$stmt= $conn->prepare($sql);
							$stmt->execute($data);
							if ($stmt) {
								$stmt = $conn->prepare("SELECT * FROM seller_info WHERE user_id=$user");
								$stmt->execute(); 
								$seller = $stmt->fetch();
								if ($seller) {
									return $seller;
								}else{
									return null;
								}
							}
						}catch (PDOException $e) {
							if ($e->getCode() == 23000) {
								$Error = array('Error'=>'User already exists, Try again');
								header('Content-Type: application/json');
								http_response_code(409);
								echo json_encode($Error);
							}
						}
					}else{
						return null;
					}
			}
		}
	}
	public static function update($data){

	}
	public static function Locals($data){
		if ($data) {
			$sql = "SELECT * FROM seller_info si
			JOIN catagories ct
			ON si.catagory = ct.id WHERE ";
			foreach ($data as $key => $value) {
				if ($key == "catagory") {
					$sql = $sql."catagory = ".$data[$key];
				}
			}
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				while ($seller = $stmt->fetch()) {
					$sellers[] = $seller;
				}
				if (isset($sellers)) {
					return $sellers;
				}else{
					return null;
				}
			}catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					$Error = array('Error'=>'No Seller Found');
					header('Content-Type: application/json');
					http_response_code(409);
					echo json_encode($Error);
				}
			}
		}
	}
	public static function getbyid($seller_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_info WHERE id=$seller_id");
			$stmt->execute(); 
			$seller = $stmt->fetch();
			if ($seller != null) {
				return $seller;
			}else{
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}



	public static function recomendation($seller_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_info si
			JOIN catagories ct
			ON si.catagory = ct.id WHERE si.id = $seller_id");
			$stmt->execute(); 
			$seller = $stmt->fetch();
			if ($seller != null) {
				return $seller;
			}else{
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}


	public static function serviceBase($cat_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT seller_id FROM seller_cats WHERE cat_id=$cat_id");
			$stmt->execute(); 
			while ($seller = $stmt->fetch()) {
				$sellerdata[] = $seller;
			}
			if (isset($sellerdata)) {
				foreach ($sellerdata as $key => $value) {
					$gs[] = Seller::recomendation($sellerdata[$key]['seller_id']);
				}
				if (isset($gs)) {
					return $gs;
				}else{
					return False;
				}
			}else{
				return False;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}
}
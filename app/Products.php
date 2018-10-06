<?php

/**
 * 
 */
debug_backtrace() || die ("Direct access not permitted");


class Products
{
	
	function __construct()
	{
		
	}

	public static function create($user_id, $data){
		$db = new DataBase(); 
		$conn = $db->connect();
		$seller = Seller::getmy($user_id);
		try{
			$stmt = $conn->prepare("INSERT INTO seller_products (seller_id, title, status, image, cat_id, price, description, active) VALUES ($seller[id], '$data[title]', $data[status], '$data[image]', $data[catagory], $data[price], '$data[description]', $data[active])");
			$stmt->execute(); 
			return true;
		}catch(PEOException $e){
			echo $e->getMessage();
			return false;
		}
	}

	public static function getbyseller($user_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter=0;
		$seller = Seller::getmy($user_id);
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_products WHERE seller_id=$seller[id]");
			$stmt->execute();
			while ($data = $stmt->fetch()) {
				$Products[$counter] = array(
					"id" => $data['id'],
					"title" => $data['title'],
					"status" => $data['status'],
					"image" => $data['image'],
					"catagory" => $data['cat_id'],
					"price" => $data['price'],
					"description" => $data['description'],
					"active" => $data['active'],
					"created_at" => $data['created_at']
				); 
				$counter++;
			}
			if (isset($Products)) {
				return $Products;
			}else{
				return false;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
			return false;
		}
	}

	public static function getbysellercat($user_id, $cat_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter=0;
		$seller = Seller::getmy($user_id);
		try{
			$stmt = $conn->prepare("SELECT * FROM seller_products WHERE seller_id=$seller[id] AND cat_id=$cat_id");
			$stmt->execute();
			while ($data = $stmt->fetch()) {
				$Products[$counter] = array(
					"id" => $data['id'],
					"title" => $data['title'],
					"status" => $data['status'],
					"image" => $data['image'],
					"catagory" => $data['cat_id'],
					"price" => $data['price'],
					"description" => $data['description'],
					"active" => $data['active'],
					"created_at" => $data['created_at']
				); 
				$counter++;
			}
			if (isset($Products)) {
				return $Products;
			}else{
				return false;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
			return false;
		}
	}

	public static function deleteprod($user_id, $prod_id){
		$db = new DataBase(); 
		$conn = $db->connect();
		$seller = Seller::getmy($user_id);
		try{
			$stmt = $conn->prepare("DELETE FROM seller_products WHERE seller_id=$seller[id] AND id=$prod_id");
			$stmt->execute(); 
			return true;
		}catch(PEOException $e){
			echo $e->getMessage();
			return false;
		}
	}
}
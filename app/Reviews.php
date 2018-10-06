<?php

/**
 * 
 */
class Reviews
{
	
	function __construct()
	{

	}
	 public static function getReviewsByUserSeller($user, $seller){
	 	$db = new DataBase(); 
		$conn = $db->connect();
		$counter = 0;
		try{
			$stmt = $conn->prepare("SELECT * FROM reviews r WHERE seller_id=$seller AND user_id=$user");
			$stmt->execute();  
			while($review = $stmt->fetch(PDO::FETCH_ASSOC)) {
				if ($review != null && $review['active'] == True) {
					$review = array(
						"review" => $review['review'],
						"created_at" => $review['created_at']
					);
					$reviews[$counter] = $review;
					$counter++;
				}
			}
			if (isset($reviews)) {
				return $reviews;
			}else{
				return null;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}
	public static function insert($id, $seller_id, $text){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter = 0;
		try{
			$stmt = $conn->prepare("INSERT INTO reviews (user_id,seller_id,review) VALUES ($id, $seller_id, '$text')");
			$stmt->execute(); 
			return true;
		}catch(PDOException $e){
			return false;
		}
	}
}
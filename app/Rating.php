<?php

/**
 * 
 */

class Rating
{
	
	function __construct()
	{
		
	}
	public static function getRatingBySeller($seller){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter = 0;
		try{
			$stmt = $conn->prepare("SELECT r.created_at, r.user_id, r.rating FROM ratings r WHERE seller_id=$seller");
			$stmt->execute();  
			while($rating = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$user = User::selectbyId($rating['user_id']);
				$profile = User::get_profile($rating['user_id']);
				$reviews = Reviews::getReviewsByUserSeller($user['id'], $seller);
				if ($rating != null) {
					$rating = array(
						"rating" => $rating['rating'],
						"user" => array(
							"first_name" => $user['first_name'],
							"last_name" => $user['last_name'],
							"profile_pic" => $profile['picture']

						),
						"reviews" => $reviews,
						"created_at" => $rating['created_at']
					);
					$ratings[$counter] = $rating;
					$counter++;
				}
			}
			if (isset($ratings)) {
				return $ratings;
			}else{
				return null;
			}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}
}
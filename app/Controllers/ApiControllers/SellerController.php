<?php

/*
* 
*/

include('./app/Seller.php');
include('./app/Catagory.php');
include('./app/Image.php');
include('./app/Rating.php');
include('./app/Reviews.php');

class SellerController
{
	
	function __construct()
	{

	}

	public static function index(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$sellers = Seller::getmy($user['id']);
				    if ($sellers != false) {
				    	$Suser = User::selectbyId($sellers['user_id']);
						$catagory = Catagory::getbyid($sellers['catagory']);
						$images = Image::getImgByUser($sellers['id']);
						$ratings = Rating::getRatingBySeller($sellers['id']);
						$subcatagory = Catagory::GetSubCat($sellers['id']);
						if ($subcatagory == false) {
							$subcatagory = null;
						}
						if ($sellers['location']==1) {
							$location = Location::getlocation($user['id']);
						}else{
							$location = Location::getlocationbySel($sellers['id']);
						}
						if ($ratings != null) {
							$counter =0;
							foreach ($ratings as $key => $value) {
								$myrates = $myrates + $ratings[$key]['rating'];
								$counter++;
							}
							$myrates = $myrates/$counter;
						}else{
							$myrates = 0;
						}
						$sellers = array(
							"id" => $sellers['id'],
							"title" => $sellers['title'],
							"cover" => $sellers['cover'],
							"rating" => $myrates,
							"user" => array(
								"id" => $Suser['id'],
								"number" => $Suser['username'],
								"email" => $Suser['email'],
								"first_name" => $Suser['first_name'],
								"last_name" => $Suser['last_name']
							),
							"catagory" => array(
								"title" => $catagory['title']
							),
							"sub_cats"=> $subcatagory,
							"images" => $images,
							"reviews" => $ratings,
							"location" => array(
								"lat" => $location['lat'],
								"lng" => $location['lng']
							)
						);
				    	header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($sellers);
				    }else{
				    	$Error = array('Error'=>'No Seller Information found');
					    header('Content-Type: application/json');
					    http_response_code(504);
					    echo json_encode($Error);
				    }
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($Error);
				}
			}else{
				$Error = array('Error'=>'Authentication credentials where Wrong/Expired');
			    header('Content-Type: application/json');
			    http_response_code(504);
			    echo json_encode($Error);
			}
		}else{
			$Error = array('Error'=>'Authentication credentials where not provided');
		    header('Content-Type: application/json');
		    http_response_code(504);
		    echo json_encode($Error);
		}
	}
	public static function create(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$sellers = Seller::create($data, $user['id']);
					if ($sellers != null) {
						$Error = array('Seller'=> array(
							'id' => $sellers['id']
						));
					    header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($Error);
					}else {
						$Error = array("Error" => "Seller already Exist");
						header('Content-Type: application/json');
					    http_response_code(504);
					    echo json_encode($Error);
					}
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($Error);
				}
			}else{
				$Error = array('Error'=>'Authentication credentials where Wrong/Expired');
			    header('Content-Type: application/json');
			    http_response_code(504);
			    echo json_encode($Error);
			}
		}else{
			$Error = array('Error'=>'Authentication credentials where not provided');
		    header('Content-Type: application/json');
		    http_response_code(504);
		    echo json_encode($Error);
		}
	}

	public static function normalize($sellers, $data){
		foreach ($sellers as $key => $value) {
			if ($sellers[$key]['location'] == 1) {
				$location = Location::getlocation($sellers[$key]['user_id']);
			}else{
				$location = Location::getlocationbySel($sellers[$key]['id']);
			}
			$sellers[$key]['location'] = array(
				"lat"=>$location['lat'],
				"lng"=>$location['lng']
			);
			$distance =  SellerController::distance($data['lat'], $data['lng'], $sellers[$key]['location']['lat'], $sellers[$key]['location']['lng'], "K");
			$ratings = Rating::getRatingBySeller($sellers[$key][0]);
			$myrates=0;
			if ($ratings != null) {
				$counter2 =0;
				foreach ($ratings as $rkey => $value) {
					$myrates = $myrates + $ratings[$rkey]['rating'];
					$counter2++;
				}
				$myrates = $myrates/$counter2;
			}
			if ($distance <= $data['radious']) {
				$sellers[$key] = array(
				"id" => $sellers[$key][0],
				"title" => $sellers[$key][1],
				"cover" => $sellers[$key]['cover'],
				"user" => $sellers[$key]['user_id'],
				"catagory" => $sellers[$key]['title'],
				"total_rating" => $myrates,
				"cover" => $sellers[$key]['cover'],
				"created_at" => $sellers[$key]['created_at'],
				"updated_at" => $sellers[$key]['updated_at'],
				"location" => $sellers[$key]['location']
				);
				$localsell[] = $sellers[$key];
			}
		}
		return $localsell;
	}

	
	public static function location(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$sellers = Seller::Locals($data);
					$variable = Seller::serviceBase($data['catagory']);
					if ($sellers != null) {
						$localsell = SellerController::normalize($sellers, $data);
					}
					if ($variable) {
						$localsell2 = SellerController::normalize($variable, $data);
					}
					if (isset($localsell) && isset($localsell2)) {
						$resulty = array(
							"nearest"=>$localsell,
							"service based" => $localsell2
						);
						if (isset($resulty)) {
							header('Content-Type: application/json');
					    	http_response_code(200);
							echo json_encode($resulty);
						}else{
							$Error = array('Error'=>'No Nearest Seller Found');
						    header('Content-Type: application/json');
						    http_response_code(404);
						    echo json_encode($Error);
						}
					}elseif (!isset($localsell) && isset($localsell2)) {
						header('Content-Type: application/json');
					    http_response_code(200);
						echo json_encode($localsell2);
					}else{
						header('Content-Type: application/json');
					    http_response_code(200);
						echo json_encode($localsell);
					}
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($Error);
				}
			}else{
				$Error = array('Error'=>'Authentication credentials where Wrong/Expired');
			    header('Content-Type: application/json');
			    http_response_code(504);
			    echo json_encode($Error);
			}
		}else{
			$Error = array('Error'=>'Authentication credentials where not provided');
		    header('Content-Type: application/json');
		    http_response_code(504);
		    echo json_encode($Error);
		}	
	}
	public static function getbyID($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$sellers = Seller::getbyid($params['id']);
				    if ($sellers != false) {
				    	$Suser = User::selectbyId($sellers['user_id']);
						$catagory = Catagory::getbyid($sellers['catagory']);
						$images = Image::getImgByUser($sellers['id']);
						$ratings = Rating::getRatingBySeller($sellers['id']);
						$msg = Catagory::GetSubCat($sellers['id']);
						if ($msg == false) {
							$msg = null;
						}
						if ($sellers['location']==1) {
							$location = Location::getlocation($user['id']);
						}else{
							$location = Location::getlocationbySel($sellers['id']);
						}
						if ($ratings != null) {
							$myrates=null;
							$counter =0;
							foreach ($ratings as $key => $value) {
								$myrates = $myrates + $ratings[$key]['rating'];
								$counter++;
							}
							$myrates = $myrates/$counter;
						}else{
							$myrates = 0;
						}
						$sellers = array(
							"id" => $sellers['id'],
							"title" => $sellers['title'],
							"rating" => round($myrates, 2),
							"user" => array(
								"id" => $Suser['id'],
								"number" => $Suser['username'],
								"email" => $Suser['email'],
								"first_name" => $Suser['first_name'],
								"last_name" => $Suser['last_name']
							),
							"catagory" => array(
								"title" => $catagory['title']
							),
							"sub_cats"=> $msg,
							"images" => $images,
							"reviews" => $ratings,
							"location" => array(
								"lat" => $location['lat'],
								"lng" => $location['lng']
							)
						);
				    	header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($sellers);
				    }else{
				    	$Error = array('Error'=>'Seller Not Found');
					    header('Content-Type: application/json');
					    http_response_code(404);
					    echo json_encode($Error);
				    }
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($Error);
				}
			}else{
				$Error = array('Error'=>'Authentication credentials where Wrong/Expired');
			    header('Content-Type: application/json');
			    http_response_code(504);
			    echo json_encode($Error);
			}
		}else{
			$Error = array('Error'=>'Authentication credentials where not provided');
		    header('Content-Type: application/json');
		    http_response_code(504);
		    echo json_encode($Error);
		}
	}
	public static function update(){
		echo "string";
	}
	public static function delete(){
		echo "string";
	}

	public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
	  	return ($miles * 1.609344);
		}else if ($unit == "N") {
	    	return ($miles * 0.8684);
	  	} else {
	      	return $miles;
		}
	}
}
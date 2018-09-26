<?php

/**
 * 
 */
include('./app/Location.php');

class LocationController
{
	
	function __construct()
	{
		
	}

	public function userloc(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$loc = Location::createorupdate($user['id'],$data);
					$loc = array(
						"lat" => $loc['lat'],
						"lng" => $loc['lng']
					);
				    header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($loc);
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
	public function getuserloc(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$loc = Location::getlocation($user['id']);
					if ($loc != null) {
						$loc = array(
							"lat" => $loc['lat'],
							"lng" => $loc['lng']
						);
					    header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($loc);
					}else{
						$Error = array('Error'=>'No location data found');
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
	public function getuserlocById($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$loc = Location::getlocation($params['id']);
					if ($loc != null) {
						$loc = array(
							"lat" => $loc['lat'],
							"lng" => $loc['lng']
						);
					    header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($loc);
					}else{
						$Error = array('Error'=>'No location data found');
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

	public function sellerloc(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$loc = Location::createorupdateselloc($data['seller_id'],$data);
					$loc = array(
						"lat" => $loc['lat'],
						"lng" => $loc['lng']
					);
				    header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($loc);
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
}
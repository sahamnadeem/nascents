<?php

/**
 * 
 */
include('./app/Products.php');
debug_backtrace() || die ("Direct access not permitted");

class ProductController
{
	
	function __construct()
	{

	}

	public function index(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$msg = Products::getbyseller($user['id']);
					if ($msg != false) {
					    header('Content-Type: application/json');
					    http_response_code(200);
				    	echo json_encode($msg);
					}else{
						$Error = array('Error'=>'An error has been occured');
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
	public function create(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$msg = Products::create($user['id'], $data);
					if ($msg == true) {
						$Error = array('Success'=>'Product Successfully created');
					    header('Content-Type: application/json');
					    http_response_code(200);
				    	echo json_encode($Error);
					}else{
						$Error = array('Error'=>'An error has been occured');
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

	public function delete($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$msg = Products::deleteprod($user['id'], $params['id']);
					if ($msg == True) {
						$Error = array('Success'=>'Product Successfully Deleted');
					    header('Content-Type: application/json');
					    http_response_code(200);
				    	echo json_encode($Error);
					}else{
						$Error = array('Error'=>'An error has been occured');
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
	
	public function CatProd($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$msg = Products::getbysellercat($user['id'], $params['id']);
					if ($msg != false) {
					    header('Content-Type: application/json');
					    http_response_code(200);
				    	echo json_encode($msg);
					}else{
						$Error = array('Error'=>'An error has been occured');
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
}
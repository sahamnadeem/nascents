<?php

/**
 * 
 */

debug_backtrace() || die ("Direct access not permitted");

class UserController
{
	
	function __construct()
	{

	}

	public function users(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user['is_admin'] == True) {
					$users = User::selecall();
					header('Content-Type: application/json');
					http_response_code(200);
					echo json_encode($users);
				}else{
					$Error = array('Permission'=>'Not a Superuser, Only superuser is allowed to view user info');
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

	public function user($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user['is_admin'] == True) {
					$user = User::selectbyId($params['id']);
					if ($user != null) {
							$user = array("user" => array(
							"id" =>$user['id'],
							"username" =>$user['username'],
							"password" =>$user['password'],
							"first_name" =>$user['first_name'],
							"last_name" =>$user['last_name'],
							"is_admin" =>$user['is_admin'],
							"is_active" =>$user['is_active'],
							"email" =>$user['email']
						));
					}
					header('Content-Type: application/json');
					http_response_code(200);
					echo json_encode($user);
				}else{
					$Error = array('Permission'=>'Not a Superuser, Only superuser is allowed to view user info');
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

	public function update($params){
		$request = json_decode(file_get_contents('php://input'),True);
		$data = Data::validate($request);
		$user = User::update($data,$params);
	}

	public function delete($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user['is_admin'] == True) {
					User::datauser($params['id']);
					header('Content-Type: application/json');
					http_response_code(200);
					echo json_encode($data);
				}else{
					$Error = array('Permission'=>'Not a Superuser, Only superuser is allowed to view user info');
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
				if ($user['is_admin'] == True) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$user = User::getorcreate($data);
					if ($user != null) {
						$user = array("user" => array(
						"id" =>$user['id'],
						"username" =>$user['username'],
						"password" =>$user['password'],
						"first_name" =>$user['first_name'],
						"last_name" =>$user['last_name'],
						"is_admin" =>$user['is_admin'],
						"is_active" =>$user['is_active'],
						"email" =>$user['email']
						));
					}
					header('Content-Type: application/json');
					http_response_code(200);
					echo json_encode($user);
				}else{
					$Error = array('Permission'=>'Not a Superuser, Only superuser is allowed to view user info');
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
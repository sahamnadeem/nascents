<?php

/**
 * 
 */
debug_backtrace() || die ("Direct access not permitted");
include('./app/User.php');
include('./libs/Data.php');
include('./app/Middleware/TokenMiddleware.php');
include('./app/Middleware/ClientMiddleware.php');
include('./app/Middleware/InputHelper.php');

class LoginController
{
	
	function __construct()
	{

	}

	public function login($request){
		$request = json_decode(file_get_contents('php://input'),True);
		$data = Data::validate($request);
		if (isset($data['client_id']) && isset($data['client_secret'])) {
			$client['client_id'] = $data['client_id'];
			$client['client_secret'] = $data['client_secret'];
			unset($data['client_id']);
			unset($data['client_secret']);
			$client = ClientMiddleware::getclient($client);
			if ($client != false) {
				$user = User::get($data);
				if ($user == True) {
					$token = TokenMiddleware::getToken($user, $client['client_id']);
				}
			}else{
				$response = array("Error"=> "invalid Client");
				header('Content-Type: application/json');
				http_response_code(400);
				echo json_encode($response);
			}
		}else{
			$response = array("Error"=> "Empty Client info");
			header('Content-Type: application/json');
			http_response_code(400);
			echo json_encode($response);
		}
	}

	public static function register(){
		$request = json_decode(file_get_contents('php://input'),True);
		$data = Data::validate($request);
		if (isset($data['client_id']) && isset($data['client_secret'])) {
			$client['client_id'] = $data['client_id'];
			$client['client_secret'] = $data['client_secret'];
			unset($data['client_id']);
			unset($data['client_secret']);
			$client = ClientMiddleware::getclient($client);
			if ($client != false) {
				$user = User::getorcreate($data);
				if ($user == True) {
					$token = TokenMiddleware::getToken($user, $client['client_id']);
				}
			}
		}
	}
	
	public function user($request){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$prfile = User::get_profile($user['id']);
					$data = array(
					'id' => $user['id'],	
					'username' => $user['username'],
				    'password' => $user['password'],
				    'email' => $user['email'],
				    'first_name' => $user['first_name'],
				    'last_name' => $user['last_name'],
				    'is_active' => $user['is_active'],
				    'profile' => array(
				    	'picture' => $prfile['picture'],
				    	'video' => $prfile['video'],
				    	'gender' => $prfile['gender'],
				    	'dob' => $prfile['dob'],
				    	'nationality' => $prfile['nationality'],
				    	'qualification' => $prfile['qualification'],
				    	'career_level' => $prfile['career_level'],
				    ));
					header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($data);
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

	public function update()
	{
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$profile = array();
					$datam = array();
					foreach ($data as $key => $value) {
						foreach ($data['profile'] as $key => $val) {
							$profile[$key] = $data['profile'][$key];
						}
					}
					foreach ($data as $key => $value) {
						$datam[$key] = $data[$key];
					}
					unset($datam['profile']);
					$str = "";
					$userdata = User::update($data,$user);
					$profile = User::updateprofile($profile,$user['id']);
					if ($userdata != null && $profile != null) {
						$change = array_merge($userdata,$profile);
						foreach ($change as $key => $value) {
							$str = $str." ".$key;
						}
						$Error = array('Update'=> $str." has successfully been updated, User is now updated");
					    header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($Error);
					}elseif($userdata != null && $profile == null){
						foreach ($userdata as $key => $value) {
							$str = $str." ".$key;
						}
						$Error = array('Update'=> $str." has successfully been updated, User is now updated");
						header('Content-Type: application/json');
						http_response_code(200);
						echo json_encode($Error);
					}elseif($profile !=null && $userdata == null){
						foreach ($profile as $key => $value) {
							$str = $str." ".$key;
						}
						$Error = array('Update'=> $str." has successfully been updated, User is now updated");
						header('Content-Type: application/json');
						http_response_code(200);
						echo json_encode($Error);
					}else{
						$Error = array('Update'=> "No change detected");
					    header('Content-Type: application/json');
					    http_response_code(200);
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

	public function profile(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$data = array(
					'id' => $user['id'],	
					'username' => $user['username'],
				    'password' => $user['password'],
				    'email' => $user['email'],
				    'is_admin' => $user['is_admin'],
				    'first_name' => $user['first_name'],
				    'last_name' => $user['last_name'],
				    'is_active' => $user['is_active']
					);
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$user = User::create_profile($data, $user);
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
	public function reset(){
		$request = json_decode(file_get_contents('php://input'),True);
		$data = Data::validate($request);
		$user = User::getuserpass($data['username']);
		if($user){
			TokenMiddleware::reset_pass($user['id']);
		}
	}
	public function refresh(){
		$request = json_decode(file_get_contents('php://input'),True);
		$data = Data::validate($request);
		if (isset($data['refresh_token'])) {
			$token = TokenMiddleware::refreshToken($data['refresh_token']);
		}
	}
}
<?php
/**
* 
*/

class ReviewController
{
	public function index($params){

	}
	public function create($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$return = Reviews::insert($user['id'], $params['id'], $data['comment']);
					if ($return) {
						$Error = array('Success'=>'Review has been created successfully');
					    header('Content-Type: application/json');
					    http_response_code(200);
					    echo json_encode($Error);
					}else{
						$Error = array('Error'=>'Review has not been created');
					    header('Content-Type: application/json');
					    http_response_code(504);
					    echo json_encode($Error);
					}
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(404);
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

	}
	public function update(){

	}
}
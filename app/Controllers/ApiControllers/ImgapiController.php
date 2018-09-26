<?php

/**
 * 
 */
class ImgapiController
{
	
	function __construct()
	{

	}
	public function sellerimg(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$path = Image::uploaddb($data);
					if ($path == True) {
						$msg = array("success"=>"Images has been added to the list");
						header('Content-Type: application/json');
						http_response_code(504);
						echo json_encode($msg);
					}else{
						$msg = array("success"=>"No valid Images Found");
						header('Content-Type: application/json');
						http_response_code(504);
						echo json_encode($msg);
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

	public function deleteimg($params){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$path = Image::deleteimg($params['id']);
					$Error = array('Success '=>'Image has be successfully deleted');
				    header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($Error);
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
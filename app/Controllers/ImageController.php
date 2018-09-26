<?php

/**
 * 
 */
class ImageController
{
	
	function __construct()
	{

	}
	public function imgupload(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$path = Image::upload($_FILES);
					header('Content-Type: application/json');
					$result = array("image"=>$path);
					http_response_code(200);
					echo json_encode($result);
				}else{
					$Error = array('Error'=>'No valid user found');
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($Error);
				}
			}else{
				$Error = array('Error'=>'Authentication credentials where Wrong/Expired');
			    header('Content-Type: application/json');
			    http_response_code(503);
			    echo json_encode($Error);
			}
		}else{
			$Error = array('Error'=>'Authentication credentials where not provided');
		    header('Content-Type: application/json');
		    http_response_code(500);
		    echo json_encode($Error);
		}
	}

}
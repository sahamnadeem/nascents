<?php

/**
 * 
 */

debug_backtrace() || die ("Direct access not permitted");

class CatagoryController
{
	
	function __construct()
	{

	}

	public function index(){
        $cats = Catagory::get();
		header('Content-Type: application/json');
		http_response_code(200);
		echo json_encode($cats);
	}
	public function CreateSellerCats(){
		$token = TokenMiddleware::getTokenHeader();
		if ($token != False) {
			$check = TokenMiddleware::checkToken($token);
			if ($check != False) {
				$user = User::select($check);
				if ($user != null) {
					$request = json_decode(file_get_contents('php://input'),True);
					$data = Data::validate($request);
					$msg = Catagory::CreateSubCat($data, $user['id']);
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
	public function GetSellerCats(){

	}
}
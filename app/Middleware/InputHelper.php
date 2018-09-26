<?php
/**
 * 
 */
class InputHelper
{
	
	function __construct()
	{

	}
	public static function Serializer($post){
		if (isset($post)) {
			$request = $_POST;
			$data = Data::validate($request);
		}else{
			$request = json_decode(file_get_contents('php://input'),True);
			$data = Data::validate($request);
		}
	}
}
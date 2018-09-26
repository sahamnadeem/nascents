<?php

/**
 * 
 */
debug_backtrace() || die ("Direct access not permitted");


class Data
{
	function __construct()
	{

	}

	public static function validate($request){
		$data = array();
		foreach ($request as $key => $value) {
			$data[$key] = $value;
		}
		return $data;
	}
}
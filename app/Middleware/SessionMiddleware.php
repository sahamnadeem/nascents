<?php

/**
 * 
 */

class SessionMiddleware
{
	
	function __construct()
	{

	}
	public static function session($item){
		$_SESSION = $item;
	}

	public static function loginprevent(){
		if (isset($_SESSION['access_token'])) {
			header("location: /ceptraproj/dashboard/");
		}
	}

	public static function homeprevent(){
		if (!isset($_SESSION['access_token'])) {
			header("location: /ceptraproj/login/");
		}
	}

	public static function unset(){
		if (isset($_SESSION['access_token'])) {
			session_destroy();
			header("location: /ceptraproj/login/");
		}
	}
}
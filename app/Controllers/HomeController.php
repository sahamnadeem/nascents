<?php

/**
 * 
 */

class HomeController
{
	
	function __construct()
	{

	}

	public function index(){
		echo "string";	
	}
	public function dashboard(){
		SessionMiddleware::homeprevent();
		require $_SERVER['DOCUMENT_ROOT'] . '/ceptraproj/public/views/dashboard.inc.php';
	}
}
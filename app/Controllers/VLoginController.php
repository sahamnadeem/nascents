<?php

/**
 *
 */

include './app/Middleware/SessionMiddleware.php';

class VLoginController 
{
	
	function __construct()
	{

	}

	public function setregister(){
		if (isset($_SERVER) == 'POST') {
			$url = 'http://localhost:8056/ceptraproj/oauth/register/';
			$ch = curl_init($url);
			$payload = json_encode($_POST);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		if ($result) {
			$result = json_decode($result);
			SessionMiddleware::session($result);
		}
	}

	public function register(){
		require $_SERVER['DOCUMENT_ROOT'] . '/ceptraproj/public/views/register.inc.php';
	}

	public function setlogin(){
		if (!isset($_SESSION['access_token'])) {
			if (isset($_SERVER) == 'POST') {
				$url = 'http://localhost:8056/ceptraproj/oauth/';
				$ch = curl_init($url);
				$payload = json_encode($_POST);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
			}
			if ($result) {
				$result = json_decode($result, true);
				SessionMiddleware::session($result);
				header("location: /ceptraproj/dashboard/");
			}
		}else{
			SessionMiddleware::loginprevent();
		}
	}

	public function login(){
		SessionMiddleware::loginprevent();
		require $_SERVER['DOCUMENT_ROOT'] . '/ceptraproj/public/views/login.inc.php';
	}

	public function logout(){
		echo "string";
		SessionMiddleware::unset();
	}
}
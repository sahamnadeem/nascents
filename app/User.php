<?php
/**
 * 
 */

//require($_SERVER['DOCUMENT_ROOT'] . '/ceptraproj/app/connect.php');
debug_backtrace() || die ("Direct access not permitted");

class User
{
	
	function __construct()
	{

	}
	public static function getorcreate($array=[]){
		if (isset($array['id'])) {
			$Error = array('Error'=>'Invalid Perameters where Given');
			header('Content-Type: application/json');
			http_response_code(404);
			echo json_encode($Error);
		}else{
			if ($array==null) {
					$Error = array('Null Value'=>'Nothing found to add');
				    header('Content-Type: application/json');
				    http_response_code(200);
				    echo json_encode($Error);
				    return False;
			}else{
					$str = "";
					$stro = "";
					$sql = "";
					foreach ($array as $key => $value) {
						if ($key == "password") {
							$array[$key] = md5($value);
						}
						$str = $str.$key.", ";
						$stro = $stro.":".$key.", ";
					}
					$str = substr($str,0,-2);
					$stro = substr($stro,0,-2);
				$sql = "INSERT INTO user ($str) VALUES ($stro)";
				$db = new DataBase(); 
				$conn = $db->connect();
				try{
					$stmt= $conn->prepare($sql);
					$stmt->execute($array);
					if ($stmt) {
						$username = $array["username"];
						$password = $array["password"];
						$stmt = $conn->prepare("SELECT * FROM user WHERE username='$username' AND password='$password'");
						$stmt->execute(); 
						$user = $stmt->fetch();
						if ($user) {
							return $user;
						}
					}
				}catch (PDOException $e) {
					if ($e->getCode() == 23000) {
				      	$Error = array('Error'=>'User already exists, Try again');
				      	header('Content-Type: application/json');
				      	http_response_code(409);
				      	echo json_encode($Error);
				    }
				}
			}
		}
	}

	public static function get($array=[]){
		$array['password'] = md5($array['password']);
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM user WHERE username=:username AND password=:password");
			$stmt->execute($array); 
			$user = $stmt->fetch();
			if ($user) {
				if($user['is_active'] == true){
					return $user;
				}else{
					$Error = array('Error'=>'Login Not possible User Blocked');
					header('Content-Type: application/json');
					http_response_code(404);
					echo json_encode($Error);
					return False;
				}
			}else{
				$Error = array('Error'=>'Login credentials were not provided or incorrect');
		      	header('Content-Type: application/json');
		      	http_response_code(404);
		      	echo json_encode($Error);
		      	return False;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function select($array=[]){
		$id = $array['user_id'];
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM user WHERE id=$id");
			$stmt->execute(); 
			$user = $stmt->fetch();
			if ($user) {
				return $user;
			}else{
				$Error = array('Error'=>'Authentication credentials where not Wrong/Expired');
		      	header('Content-Type: application/json');
		      	http_response_code(404);
		      	echo json_encode($Error);
		      	return False;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function selecall(){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM user");
			$stmt->execute(); 
			$user = array();
			$counter = 0;
			while( $users = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				$user[$counter] = $users;
				$profile = User::get_profile($user[$counter]['id']);
				$user[$counter]['profile'] = array(
					'picture' => $profile['picture'],
				    'video' => $profile['video'],
				    'gender' => $profile['gender'],
				    'dob' => $profile['dob'],
				    'nationality' => $profile['nationality'],
				    'qualification' => $profile['qualification'],
				    'career_level' => $profile['career_level'],
				);
				$counter++;
			}
			if ($user) {
				return $user;
			}else{
				$Error = array('Error'=>'No User Found');
		      	header('Content-Type: application/json');
		      	http_response_code(404);
		      	echo json_encode($Error);
		      	return False;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	} 

	public static function selectbyId($id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM user WHERE id=$id");
			$stmt->execute(); 
			$user = $stmt->fetch();
			if ($user) {
				return $user;
			}else{
				$Error = array('Error'=>'No User Exist');
		      	header('Content-Type: application/json');
		      	http_response_code(404);
		      	echo json_encode($Error);
		      	return False;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function update($data, $params){
		if (isset($data['id'])) {
			$Error = array('Error'=>'Invalid Perameters where Given');
			header('Content-Type: application/json');
			http_response_code(404);
			echo json_encode($Error);
		}else{
			$change = User::checkforupdate($data, $params);
			if ($change==null) {
			    return False;
			}else{
				$str = "";
				$sql = "UPDATE user SET ";
				foreach ($change as $key => $value) {
					if ($key == "password") {
						$change[$key] = md5($value);
					}
					$str = $str.$key." ";
					$sql = $sql.$key."=:".$key.", ";
				}
				$sql = substr($sql,0,-2);
				$sql = $sql." WHERE id=".$params['id']."";
				$db = new DataBase(); 
				$conn = $db->connect();
				try{
					$stmt = $conn->prepare($sql);
					$stmt->execute($change); 
				    return $change;
				}catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
	}

	public static function checkforupdate($data, $params){
		$user = User::selectbyId($params['id']);
		$user = array(
			"username" =>$user['username'],
			"password" =>$user['password'],
			"first_name" =>$user['first_name'],
			"last_name" =>$user['last_name'],
			"is_admin" =>$user['is_admin'],
			"is_active" =>$user['is_active'],
			"email" =>$user['email']
		);
		$change = array();
		foreach ($user as $key => $value) {
			if ($user[$key] != $data[$key]) {
				$change[$key] = $data[$key];
			}
		}
		return $change;
	}

	public static function datauser($id){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("DELETE FROM user WHERE id=$id");
			$stmt->execute($change); 
			$Error = array('Dalate'=>"Successfully deleted User");
			  	header('Content-Type: application/json');
			 	 http_response_code(200);
			 	 echo json_encode($Error);
			 	 return True;
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function create_profile($data, $user){
		$str = "";
		$stro = "";
		foreach ($data as $key => $value) {
			$str = $str.$key.", ";
			$stro = $stro.":".$key.", ";
		}
		$str = $str."user_id";
		$stro = $stro.":user_id";
		$data['user_id'] = $user['id'];
		$sql = "INSERT INTO profile ($str) VALUES ($stro)";
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare($sql);
			$stmt->execute($data); 
			$Error = array('Success'=>"Successfully created profile");
			header('Content-Type: application/json');
			http_response_code(200);
			echo json_encode($Error);
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function get_profile($id){
		$sql = "SELECT * FROM profile WHERE user_id=$id";
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare($sql);
			$stmt->execute(); 
			$profile = $stmt->fetch();
			if ($profile != null) {
				return $profile;
			}else{
				return null;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function checkforupdateprof($data, $id){
		$user = User::get_profile($id);
		$user = array(
			'picture' => $user['picture'],
			'video' => $user['video'],
			'gender' => $user['gender'],
			'dob' => $user['dob'],
			'nationality' => $user['nationality'],
			'qualification' => $user['qualification'],
			'career_level' => $user['career_level'],
		);
		$change = array();
		foreach ($user as $key => $value) {
			if ($user[$key] != $data[$key]) {
				$change[$key] = $data[$key];
			}
		}
		return $change;
	}

	public static function updateprofile($data, $id){
		if (isset($data['id'])) {
			$Error = array('Error'=>'Invalid Perameters where Given');
			header('Content-Type: application/json');
			http_response_code(404);
			echo json_encode($Error);
		}else{
			$change = User::checkforupdateprof($data, $id);
			if ($change==null) {
			    return False;
			}else{
				$str = "";
				$sql = "UPDATE profile SET ";
				foreach ($change as $key => $value) {
					if ($key == "password") {
						$change[$key] = md5($value);
					}
					$str = $str.$key." ";
					$sql = $sql.$key."=:".$key.", ";
				}
				$sql = substr($sql,0,-2);
				$sql = $sql." WHERE user_id=$id";
				$db = new DataBase(); 
				$conn = $db->connect();
				try{
					$stmt = $conn->prepare($sql);
					$stmt->execute($change); 
				    return $change;
				}catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}

	}

	public static function getuserpass($username){
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt = $conn->prepare("SELECT * FROM user WHERE username=$username");
			$stmt->execute(); 
			$user = $stmt->fetch();
			if ($user) {
				if($user['is_active'] == true){
					return $user;
				}else{
					$Error = array('Error'=>'Login Not possible User Blocked');
					header('Content-Type: application/json');
					http_response_code(404);
					echo json_encode($Error);
					return False;
				}
			}else{
				$Error = array('Error'=>'User Not Found');
		      	header('Content-Type: application/json');
		      	http_response_code(404);
		      	echo json_encode($Error);
		      	return False;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}
/*
*/
<?php

/**
 * 
 */
class Image
{
	
	function __construct()
	{

	}

	public static function getImgByUser($seller){
		$db = new DataBase(); 
		$conn = $db->connect();
		$counter = 0;
		try{
			$stmt = $conn->prepare("SELECT i.image, i.active, i.created_at, i.id FROM images i WHERE seller_id=$seller");
			$stmt->execute(); 
				while( $image = $stmt->fetch(PDO::FETCH_ASSOC)) {
					if ($image['active'] == true) {
						$images[$counter] = $image;
						$counter++;
					}
				}
				if (isset($images) && $images != null) {
					return $images;
				}else{
					return null;
				}
		}catch(PEOException $e){
			echo $e->getMessage();
		}
	}

	public static function upload($file){
		$counter = 0;
		foreach ($file as $key => $value) {
			$target_dir = './public/images/';
			$ini = 'rest.captrainterface.com/api/public/images/';
			$savable = $ini.basename($file[$key]["name"]);
			$target_file = $target_dir . basename($file[$key]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$error = array("error" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
			    header('Content-Type: application/json');
			    http_response_code(504);
			    echo json_encode($error);
			    $uploadOk = 0;
			}
			if (move_uploaded_file($file[$key]["tmp_name"], $target_file)) {
			      	$response[$counter] = array($savable);
			    } else {
			        $error = array("error" =>"Sorry, there was an error uploading your file.");
				    header('Content-Type: application/json');
				    http_response_code(504);
				    echo json_encode($error);
			    }
			$counter++;
		}
		return $response;
	}

	public static function uploaddb($data){
		$str = "";
		if ($data) {
			foreach ($data['images'] as $key => $value) {
				$str = $str." ($data[seller_id], '".$data['images'][$key]."'), ";
			}
			$str = substr($str,0,-2);
			$sql = "INSERT INTO images (seller_id, image) VALUES".$str;
			$db = new DataBase(); 
			$conn = $db->connect();
			try{
				$stmt= $conn->prepare($sql);
				$stmt->execute();
				return True;
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}else{
			return false;
		}
	}

	public static function deleteimg($id){
		$sql = "DELETE FROM images WHERE id=$id";
		$db = new DataBase(); 
		$conn = $db->connect();
		try{
			$stmt= $conn->prepare($sql);
			$stmt->execute();
			return True;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
}
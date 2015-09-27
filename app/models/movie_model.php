<?php

class MovieModel extends BaseModel{
	private $conn; 
	
	public function __construct() {
		$this->conn = $this->getDB();
	}

	public function __destruct() {
		$this->conn = null;
	}
	
	public static function all(){
 		
   		$conn = DB::connection();
		$query = $conn->prepare('SELECT id,name,description,duration,image FROM movie;');
		$query->execute();
		return array('movies' => $query->fetchAll());
		
	}
	public static function find($id){
   		$conn = DB::connection();
		$query = $conn->prepare("SELECT name,description,duration,image FROM movie where id=$id;");
		$query->execute();
		
		return array('details' => $query->fetchAll());
	}
	
	function save($id){
		
	}
	function add($name, $description, $duration, $image){
		$name = strip_tags($name);
		$description =strip_tags($description);
		$duration = strip_tags($duration);

		if ($this->validate_name($name) == true &&
		    $this->validate_description($description) == true &&
		    $this->validate_duration($duration) == true &&
		    $this->validate_image($image) == true) {

			$img_file = fopen($image['tmp_name'], 'r');
			$img_data = fread($img_file,$image['size']);
			
			$query = $this->conn->prepare("INSERT INTO movie (name,description,duration,image) VALUES (:name, :description, :duration, :img_data)");
			$query->bindParam(':name', $name);
			$query->bindParam(':description', $description);
			$query->bindParam(':duration', $duration);
			$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
			$query->execute();                                                                                                                                             
		}
	}

	private function validate_duration($duration) {
		if (is_numeric($duration)) {
			if ($duration > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	private function validate_description($description) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($description) < 1200) {
			return true;
		} else {
			return false;
		}

	}
	private function validate_name($name) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($name) < 64) {
			return true;
		} else {
			return false;
		}
	}

	private function validate_image($image) {
		if ($image != null) {
			if ($image['size'] < 200000) {
				
				$img_type = exif_imagetype($image['tmp_name']);
				if ($img_type == 3 || $img_type == 2) {
					return true;
				} 
			} else {
				return false;
			}
		} else {
			return true;
		}
	}


	
	private function getDB() {
		return DB::connection();
	}
	
	private function remove($id){
		
	}
	
}

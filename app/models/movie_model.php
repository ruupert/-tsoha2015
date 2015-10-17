<?php

class MovieModel extends BaseModel{
	private $conn; 
	private $errors;
	
	public function __construct() {
		$this->conn = $this->getDB();
	}

	public function __destruct() {
		$this->conn = null;
	}
	
	public static function all($param){
   		$conn = DB::connection();

		switch ($param) {
			case 'name':
				$query = $conn->prepare('SELECT id, name FROM movie;');
				break;
			case 'allfields':
				$query = $conn->prepare('SELECT id,name,description,duration FROM movie;');
				break;
		}
		
		$query->execute();
		return array('movies' => $query->fetchAll());
		
	}

	public static function find($id){
   		$conn = DB::connection();
		$movie = $conn->prepare("SELECT name,description, duration, encode(image::bytea,'base64') as image FROM movie where id=$id;");
		$movie->execute();
		
  		$conn = DB::connection();
		$related = $conn->prepare("SELECT theater.name, timetable.start_at, timetable.end_at, timetable.id as timetable_id FROM movie, timetable, theater where movie.id=$id and timetable.movie_id=$id and timetable.theater_id=theater.id;");
		$related->execute();
		
		
		return array('details' => $movie->fetchAll(), 'related' => $related->fetchAll());
	}
	
	function save($id, $name, $description, $duration, $image){

		$name = strip_tags($name);
		$description =strip_tags($description);
		$duration = strip_tags($duration);

		if ($this->validate_name($name) == true &&
			$this->validate_description($description) == true &&
			$this->validate_duration($duration) == true &&
			$this->validate_image($image) == true) {

			try {
				$query = $this->conn->prepare("UPDATE movie SET name=:name, description=:description, duration=:duration, image=:img_data WHERE id=:id");
 				$img_file = fopen($image['tmp_name'], 'r');
				$img_data = fread($img_file,$image['size']);
				$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
				
			} catch (Exception $e) {
				
			        $query = $this->conn->prepare("UPDATE movie SET name=:name, description=:description, duration=:duration WHERE id=:id");
				
			}
			
			$query->bindParam(':id', $id);
			$query->bindParam(':name', $name);
			$query->bindParam(':description', $description);
			$query->bindParam(':duration', $duration);



			$query->execute();
			


			return true;
		} else {
			// ja tässä laitettais se errorrr message... ehkä.
			return false;
		}
		
		
	}
	function add($name, $description, $duration, $image){
		$name = strip_tags($name);
		$description =strip_tags($description);
		$duration = strip_tags($duration);

		if ($this->validate_name($name) == true &&
		    $this->validate_description($description) == true &&
		    $this->validate_duration($duration) == true &&
		    $this->validate_image($image) == true) {

			try {
				$img_file = fopen($image['tmp_name'], 'r');
				$img_data = fread($img_file,$image['size']);
				
				$query = $this->conn->prepare("INSERT INTO movie (name,description,duration,image) VALUES (:name, :description, :duration, :img_data)");
				$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
			} catch (Exception $e) {
				$query = $this->conn->prepare("INSERT INTO movie (name,description,duration,image) VALUES (:name, :description, :duration, null)");
				
			}
				$query->bindParam(':name', $name);
				$query->bindParam(':description', $description);
				$query->bindParam(':duration', $duration);
				$query->execute();
			
			
			
			return true;
		} else {
			// ja tässä laitettais se errorrr message... ehkä.
			return false;
		}
		
		
	}

	private function validate_duration($duration) {
		if (is_numeric($duration)) {
			if ($duration > 0) {

				
				
				return true;
			} else {
				$this->errors += "Elokuva on liian lyhyt... ";
				return false; 
			}
		} else {
			$this->errors += "Elokuvan kesto ei ole numero... ";
			return false;
		}
	}
	
	private function validate_description($description) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($description) < 1200) {
			return true;
		} else {
			$this->errors += "Elokuvan kuvaus on aiva liian pitkä (max 1200 merkkiä)... ";
			return false;
		}

	}
	private function validate_name($name) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($name) < 64) {
			return true;
		} else {
			$this->errors += "Elokuvan nimi on liian pitkä... (max 64 merkkiä)... ";
			return false;
			
		}
	}

	private function validate_image($image) {
		if ($image['tmp_name'] != null) {
			if ($image['size'] < 200000) {
				
				$img_type = exif_imagetype($image['tmp_name']);
				if ($img_type == 3 || $img_type == 2) {
					return true;
				} else {
					$this->errors += "Tiedostotyyppi ei ole joko jpg tai png... ";
					return false;
				}
			} else {
				$this->errors += "Tiedoston koko on aivan liian suuri (max. 200kt)";
				return false;
			}
		} else {
			return true;
		}
	}


	
	private function getDB() {
		return DB::connection();
	}
	
	public static function remove($id){
		$conn = DB::connection();
		$query = $conn->prepare("DELETE FROM movie WHERE id=$id");
		$query->execute();
		return true;
	}
	
}

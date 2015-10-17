<?php

class TheaterModel extends BaseModel{
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
				$query = $conn->prepare('SELECT id, name FROM theater;');
				break;
			case 'allfields':
				$query = $conn->prepare('SELECT id,name,description,seats FROM theater;');
				break;

		} 


		$query->execute();
		return array('theaters' => $query->fetchAll());

	}
	

	public static function find($id){
   		$conn = DB::connection();
		$theater = $conn->prepare("SELECT name,description,seats, encode(image::bytea,'base64') as image FROM theater where id=$id;");
		$theater->execute();

                
		$related = $conn->prepare("SELECT movie.name, timetable.start_at, timetable.end_at, timetable.id as timetable_id FROM movie, timetable, theater where theater.id=$id and timetable.theater_id=$id and timetable.movie_id=movie.id;");
		$related->execute();
		
		
		return array('details' => $theater->fetchAll(), 'related' => $related->fetchAll());
	}
	
	function save($id, $name, $description, $image){

		$name = strip_tags($name);
		$description =strip_tags($description);

		if ($this->validate_name($name) == true &&
			$this->validate_description($description) == true &&
		//	$this->validate_seats($seats) == true &&
			$this->validate_image($image) == true) {

			try {
				$query = $this->conn->prepare("UPDATE theater SET name=:name, description=:description, image=:img_data WHERE id=:id");
 				$img_file = fopen($image['tmp_name'], 'r');
				$img_data = fread($img_file,$image['size']);
				$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
				
			} catch (Exception $e) {
				
			        $query = $this->conn->prepare("UPDATE theater SET name=:name, description=:description WHERE id=:id");
				
			}
			
			$query->bindParam(':id', $id);
			$query->bindParam(':name', $name);
			$query->bindParam(':description', $description);



			$query->execute();
			


			return true;
		} else {
			// ja tässä laitettais se errorrr message... ehkä.
			return false;
		}
		
		
	}
	function add($name, $description, $seats, $image){
		$name = strip_tags($name);
		$description =strip_tags($description);
		$seats = strip_tags($seats);

		if ($this->validate_name($name) == true &&
		    $this->validate_description($description) == true &&
		    $this->validate_seats($seats) == true &&
		    $this->validate_image($image) == true) {

			try {
				$img_file = fopen($image['tmp_name'], 'r');
				$img_data = fread($img_file,$image['size']);
				
				$query = $this->conn->prepare("INSERT INTO theater (name,description,seats,image) VALUES (:name, :description, :seats, :img_data)");
				$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
			} catch (Exception $e) {
				$query = $this->conn->prepare("INSERT INTO theater (name,description,seats,image) VALUES (:name, :description, :seats, null)");
				
			}
				$query->bindParam(':name', $name);
				$query->bindParam(':description', $description);
				$query->bindParam(':seats', $seats);
				$query->execute();
			
			
			
			return true;
		} else {
			// ja tässä laitettais se errorrr message... ehkä.
			return false;
		}
		
		
	}

	private function validate_seats($seats) {
		if (is_numeric($seats)) {
			if ($seats > 0) {
				return true;
			} else {
				$this->errors += "Teatterissa liian vahan paikkoja... ";
				return false; 
			}
		} else {
			$this->errors += "Teatterin kesto ei ole numero... ";
			return false;
		}
	}
	
	private function validate_description($description) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($description) < 1200) {
			return true;
		} else {
			$this->errors += "Teatterin kuvaus on aiva liian pitkä (max 1200 merkkiä)... ";
			return false;
		}

	}
	private function validate_name($name) {
		// rajoitetaan tassa mita tietokantaan saa menna
		if (strlen($name) < 64) {
			return true;
		} else {
			$this->errors += "Teatterin nimi on liian pitkä... (max 64 merkkiä)... ";
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
	
	function remove($id){
		$conn = DB::connection();
		$query = $conn->prepare("DELETE FROM theater WHERE id=:id");
 		$query->bindParam(':id', $id);
		$query->execute();
		return true;
	}
	
}

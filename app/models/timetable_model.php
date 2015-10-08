<?php

class TimetableModel extends BaseModel{
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
			case 'allfields':
				$query = $conn->prepare('SELECT * FROM timetable;');
				break;
		}
		
		$query->execute();
		return array('movies' => $query->fetchAll());
		
	}

	public static function find($id){
   		$conn = DB::connection();
		$query = $conn->prepare("SELECT * FROM timetable where id=$id;");
		$query->execute();
		
		return array('details' => $query->fetchAll());
	}
	
	public static function save($id, $movie, $theater, $start_at){

 		if (self::validate_timetable($movie,$theater,$start_at) == true){
			
			$_SESSION['flash_message'] = json_encode('valid');
			
			try {
				$query = $this->conn->prepare("UPDATE timetable SET name=:name, description=:description, duration=:duration, image=:img_data WHERE id=:id");
 				$img_file = fopen($image['tmp_name'], 'r');
				$img_data = fread($img_file,$image['size']);
				$query->bindParam(':img_data', $img_data, PDO::PARAM_LOB);
				
			} catch (Exception $e) {
				
			        $query = $this->conn->prepare("UPDATE timetable SET name=:name, description=:description, duration=:duration WHERE id=:id");
				
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
	public static function add($movie,$theater,$start_at){
 		if (self::validate_timetable($movie,$theater,$start_at) == true){
			error();
   			$conn = DB::connection();
			
			$query = $this->conn->prepare("INSERT INTO timetable (movie_id,theater_id,start_at,end_at) VALUES (:name, :description, :duration, null)");
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

	public static function validate_timetable($movie_id, $theater_id, $start_at) {
		
		try {
			$start_at = new DateTime($start_at);
			$end_at = $start_at;
			
			$conn = DB::connection();
			
			$query = $conn->prepare("SELECT id FROM theater");
			$query->execute();
			$theaters = $query->fetch(PDO::FETCH_NUM);
			
			if (in_array($theater_id, $theaters)) {
				
				

				$query = $conn->prepare("SELECT id from movie");
				$query->execute();
				$movies = $query->fetch(PDO::FETCH_NUM);
				
				if (in_array($movie_id,$movies)) {
					$query = $conn->prepare("SELECT duration from movie where id=$movie_id");
					$query->execute();

					$duration_sec = $query->fetch(PDO::FETCH_ASSOC);
					$duration_sec = $duration_sec[0]['duration'] * 60;
					
					$movie = $movies[array_search($movie_id,$movies)];

					date_add($end_at, new DateInterval("PT".$duration_sec."S"));

						
					$query = $conn->prepare("SELECT id FROM timetable where theater_id=$theater_id 
                                                                                           AND start_at BETWEEN $start_at AND $end_at 
                                                                                            AND end_at BETWEEN $start_at AND $end_at");
					
					$query->execute();
					$overlapping_timetables = $query->fetchAll();

					
		
					return true;
					
					
					
				
				} else {return false;}
				
				
			} else  { return false;} 
		
		} catch (Exception $e) {

			// ei onnistu
			return false;
		}
			
			
		


		
	
		
	}
	
	
	private function getDB() {
		return DB::connection();
	}
	
	function remove($id){
		$conn = DB::connection();
		$query = $conn->prepare("DELETE FROM timetable WHERE id=:id");
		$query->bindParam(':id', $id);
		$query->execute();
		return true;
	}
	
}

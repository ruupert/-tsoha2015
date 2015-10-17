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
				$query = $conn->prepare('select timetable.*, movie.name as movie_name, theater.name as theater_name, timetable.start_at, timetable.end_at from timetable, movie, theater where timetable.movie_id=movie.id and timetable.theater_id=theater.id;');
				break;
		}
		
		$query->execute();
		return array('timetables' => $query->fetchAll());
		
	}

	public static function find($id){
   		$conn = DB::connection();
		$query = $conn->prepare("select timetable.*, movie.name as movie_name, encode(movie.image::bytea,'base64') as movie_image,  theater.name as theater_name, encode(theater.image::bytea,'base64') as theater_image, timetable.start_at, timetable.end_at from timetable, movie, theater where timetable.id=:timetable_id and timetable.movie_id=movie.id and timetable.theater_id=theater.id;");
		$query->bindParam(':timetable_id', $id);
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
		$log_file = '/home/users/ruupert/sites/ruupert.kapsi.fi/www/tsoha2015/log/timetable.log';
 		if (self::validate_timetable($movie,$theater,$start_at) == true){

   			$conn = DB::connection();

			system("echo 'valid timetable.. continuing' >> $log_file");
			
			$query = $conn->prepare("SELECT duration from movie where id=:movie_id");
			$query->bindParam(':movie_id', $movie);
			$query->execute();
			
			
			$duration_sec = $query->fetch(PDO::FETCH_ASSOC);
			$duration_sec = $duration_sec['duration'];
			$duration_sec = $duration_sec * 60;
			system("echo 'duration_sec: $duration_sec'  >> $log_file");

			$end_at = new DateTime($start_at);
			$start_at = new DateTime($start_at);
			$end_at->add(new DateInterval("PT".$duration_sec."S"));
			$end_at = $end_at->format("Y-m-d H:i");
			$start_at = $start_at->format("Y-m-d H:i");
			

			system("echo 'add variables values: $movie, $theater, $start_at, $end_at' >> $log_file");
			
			$query = $conn->prepare("INSERT INTO timetable (movie_id,theater_id,start_at,end_at) VALUES (:movie_id, :theater_id, :start_at, :end_at)");
			$query->bindParam(':movie_id', $movie);
			$query->bindParam(':theater_id', $theater);
			$query->bindParam(':start_at', $start_at);
			$query->bindParam(':end_at', $end_at);
			$query->execute();
			
			
			
			return true;
		} else {
			system("echo 'fakdap' >> $log_file");
			// ja tässä laitettais se errorrr message... ehkä.
			return false;
		}
		
		
	}

	public static function validate_timetable($movie_id, $theater_id, $start_at) {
		$log_file = '/home/users/ruupert/sites/ruupert.kapsi.fi/www/tsoha2015/log/timetable.log';
		system("echo '`date` entering validate_timetable with variables values: $movie_id, $theater_id, $start_at' >> $log_file");
		$end_at = new DateTime($start_at);
		$start_at = new DateTime($start_at);
		
		$conn = DB::connection();
		
		$query = $conn->prepare("SELECT id FROM theater where id=:theater_id");
		$query->bindParam(':theater_id', $theater_id);
		$query->execute();
		$theaters = $query->fetch(PDO::FETCH_ASSOC);
		
		if ($theaters['id'] == $theater_id) {
			system("echo 'theater id is valid' >> $log_file");
			
				
			
			$query = $conn->prepare("SELECT id from movie where id=:movie_id");
			$query->bindParam(':movie_id', $movie_id); 
			$query->execute();
			$movies = $query->fetch(PDO::FETCH_ASSOC);
			
			if ($movies['id'] == $movie_id) {
				system("echo 'movie id is valid' >> $log_file");
				
				$query = $conn->prepare("SELECT duration from movie where id=:movie_id");
				$query->bindParam(':movie_id', $movie_id);
				$query->execute();

				system("echo 'query seelct duration success' >> $log_file");
				
				
				$duration_sec = $query->fetch(PDO::FETCH_ASSOC);
				$duration_sec = $duration_sec['duration'];
				$duration_sec = $duration_sec * 60;
				system("echo 'duration_sec variable value: $duration_sec' >> $log_file");
				
				$end_at->add(new DateInterval("PT".$duration_sec."S"));
				$end_at = $end_at->format("Y-m-d H:i");
				$start_at = $start_at->format("Y-m-d H:i");
				system("echo 'end_at variable value: $end_at' >> $log_file");
				
				$query = $conn->prepare("SELECT count(id) FROM timetable where theater_id=$theater_id AND (start_at <= '$start_at' AND end_at >= '$end_at')");
				
				$query->execute();
				$overlapping_timetables = $query->fetch(PDO::FETCH_ASSOC);
				$res=$overlapping_timetables['count'];
				system("echo 'overlapping timetables: $res' >> $log_file");
				
				
				if ($overlapping_timetables['count'] == 0) {
					system("echo 'no overlapping timetables' >> $log_file");
					
					return true;
					
				} else {
					return false;
				}
				
				
				
			} else {
				return false;
			}
			
			
		} else  {
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

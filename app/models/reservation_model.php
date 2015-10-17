<?php

class ReservationModel extends BaseModel{
	private $conn; 
	private $errors;
	
	public function __construct() {
		$this->conn = $this->getDB();
	}

	public function __destruct() {
		$this->conn = null;
	}
	
	public static function all($param, $user){
   		$conn = DB::connection();

		switch ($param) {
			case 'allfields':
				$query = $conn->prepare("SELECT * FROM reservation");
				break;
			case 'userfields':
				$query = $conn->prepare("SELECT * FROM reservation WHERE user_id='$user'");
				break;
		}
		
		$query->execute();
		return array('reservations' => $query->fetchAll());
		
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
	public static function add($timetable_id, $seats, $user){
		// lol... pitais kai tehda enterpriceless kamaa eli lock sarake timetableen lisaks niin, etta kun tama rupee tekemaan jotain niin ensin lukitaan BEGIN; sossonsoo locked=true; COMMIT; 
		// ja sen jalkeen aletaan tarkistamaan, etta mahtuuko haluttu maara. Jos mahtuu, niin lisataan ja muutetaan locked=false.
		//
		// jos locked=true, niin odotetaan 0.x sekuntia ja yritetaan uudestaan. yrityksia voisi olla 10, jonka jalkeen luovutetaan.
		//
		// Edeltava on semi raskas tehda siihen aikaan nahden mita on jaljella joten unohdetaan paallekkaisyydet toistaiseks:
		if (self::validate_seats($timetable_id, $seats)==true) {
			// selvitetaan mika id kayttajanimella on.
			$conn = DB::connection();
			$query = $conn->prepare("SELECT id FROM users WHERE username='$user'");
			$query->execute();
			$u = $query->fetchAll(PDO::FETCH_NUM);
			
			$query = $conn->prepare("INSERT INTO reservation (user_id, timetable_id, quantity) VALUES (:uid, :tid, :qty)");
			$query->bindParam(":uid", $u[0][0]);  // juuh lissee kaljaa _b 
			$query->bindParam(":tid",$timetable_id);
			$query->bindParam(":qty",$seats);
			$query->execute();
			
			
			
		} else {
			// eipa enaa kaynytkaan.
		}
		
		
		
		
	}

	private static function validate_seats($id,$requested_seats) {
		$result = self::get_available_seats($id);
                $reserved_seats = $result['result'][0]['quantity'];
		$total_seats = $result['result'][0]['seats'];

		$available_seats = $total_seats - $reserved_seats;

		if ($available_seats > $requested_seats) {
			return true;
		} else {
			return false;
		}
		
		
	}
	
        public static function get_available_seats($id) {
		$conn = DB::connection();

		$query = $conn->prepare("select timetable.id,timetable.theater_id,theater.seats,coalesce((select sum(coalesce(reservation.quantity, 0)) as quantity from reservation where timetable_id=$id),0) as quantity from timetable, theater where timetable.id=$id and timetable.theater_id=theater.id ");
		$query->execute();
		return array('result' => $query->fetchAll(PDO::FETCH_ASSOC));

	}
	
	
	private function getDB() {
		return DB::connection();
	}
	
	function remove($id){
		$conn = DB::connection();
		$query = $conn->prepare("DELETE FROM reservation WHERE id=:id");
		$query->bindParam(':id', $id);
		$query->execute();
		return true;
	}
	
}

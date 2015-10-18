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
				$query = $conn->prepare("select reservation.id, user_id, timetable_id, timetable.movie_id, timetable.start_at, timetable.theater_id, movie.name, theater.name, users.name, users.username, users.lastname from reservation LEFT JOIN (select * from timetable) as timetable on (reservation.timetable_id=timetable.id) LEFT JOIN (select * from movie) as movie on (timetable.movie_id=movie.id)  LEFT JOIN (select * from theater) as theater on (timetable.theater_id=theater.id) LEFT JOIN (select * from users) as users on (users.id=reservation.user_id)");
				break;
			case 'userfields':
				$query = $conn->prepare("select reservation.id, user_id, timetable_id, timetable.movie_id, timetable.start_at, timetable.theater_id, movie.name, theater.name, users.name, users.username, users.lastname from reservation LEFT JOIN (select * from timetable) as timetable on (reservation.timetable_id=timetable.id) LEFT JOIN (select * from movie) as movie on (timetable.movie_id=movie.id)  LEFT JOIN (select * from theater) as theater on (timetable.theater_id=theater.id) LEFT JOIN (select * from users) as users on (users.id=reservation.user_id) where user_id='$user'");
				break;
		}
		
		$query->execute();
		return array('reservations' => $query->fetchAll());
		
	}

	public static function find($id){
   		$conn = DB::connection();
		$query = $conn->prepare("select reservation.id, reservation.quantity, user_id, timetable_id, timetable.movie_id, timetable.start_at, timetable.theater_id, movie.name, theater.name, users.name, users.username, users.lastname from reservation LEFT JOIN (select * from timetable) as timetable on (reservation.timetable_id=timetable.id) LEFT JOIN (select * from movie) as movie on (timetable.movie_id=movie.id)  LEFT JOIN (select * from theater) as theater on (timetable.theater_id=theater.id) LEFT JOIN (select * from users) as users on (users.id=reservation.user_id) where reservation.id=:reservation_id");
		$query->bindParam(':reservation_id', $id);
		$query->execute();
		
		return array('details' => $query->fetchAll());
	}
	
	public static function save($id, $seats, $user, $admin, $timetable_id){
		
		$conn = DB::connection();
		$result = self::find($id);
		$result = $result['details'][0];
		

		if (self::validate_seats($timetable_id, $seats, $result['quantity'])==true) {

			if ($admin==true) {
				$query = $conn->prepare("UPDATE reservation SET quantity=:qty where id=:id");
				
			} else {

				$query = $conn->prepare("SELECT id FROM users WHERE username='$user'");
				$query->execute();
				$u = $query->fetchAll(PDO::FETCH_NUM);
				$query = $conn->prepare("UPDATE reservation SET quantity=:qty where id=:id and user_id=:uid");
				$query->bindParam(":uid",$u[0][0]);

			}
			
			$query->bindParam(":id",$id);
			$query->bindParam(":qty",$seats);
			$query->execute();

			
			
		} else {
			// eipa enaa kaynytkaan.
		}
	}
	public static function add($timetable_id, $seats, $user){
		// lol... pitais kai tehda enterpriceless kamaa eli lock sarake timetableen lisaks niin, etta kun tama rupee tekemaan jotain niin ensin lukitaan BEGIN; sossonsoo locked=true; COMMIT; 
		// ja sen jalkeen aletaan tarkistamaan, etta mahtuuko haluttu maara. Jos mahtuu, niin lisataan ja muutetaan locked=false.
		//
		// jos locked=true, niin odotetaan 0.x sekuntia ja yritetaan uudestaan. yrityksia voisi olla 10, jonka jalkeen luovutetaan.
		//
		// Edeltava on semi raskas tehda siihen aikaan nahden mita on jaljella joten unohdetaan paallekkaisyydet toistaiseks:
		if (self::validate_seats($timetable_id, $seats, 0)==true) {
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

	private static function validate_seats($id,$requested_seats, $current_seats) {
		
		$result = self::get_available_seats($id);
                $reserved_seats = $result['result'][0]['quantity'];
		$total_seats = $result['result'][0]['seats'];

		$available_seats = $total_seats - $current_seats - $reserved_seats;

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

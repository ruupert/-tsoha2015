<?php

class UserModel extends BaseModel{
	private $conn;
	
	function __construct() {
		// hyvinpa toimii juuh... huhhuh.
		$this->conn = DB::connection();
	}

	function __destruct() {
		//$ = null;
	}
	
	public static function all(){
 		
 	//	$query $self->conn->prepare('SELECT id,name,description,duration,image FROM movie;');
	//	$query->execute();
	//	return array('movies' => $query->fetchAll());
		
	}
	public function find($id){
		
		$query = $this->conn->prepare("SELECT admin,created_at,username,pw_hash FROM users where username='$id';");
		$query->execute();
		
		return $query->fetch();
	}
	private function get($id){
		
	}
	private function save($id){
		
	}
	private function add(){
		
	}
	
	private function remove($id){
		
	}
	
}

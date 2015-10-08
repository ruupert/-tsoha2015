<?php

class UserModel extends BaseModel{
	private $conn;
	
	function __construct() {
		$this->conn = $this->getDB();
	}

	function __destruct() {
		$this->conn = null;
	}
	
	public static function all(){
 		
		
	}
	public static function find($id){
		
		$conn = DB::connection();
		$query = $conn->prepare("SELECT admin,created_at,username,pw_hash,login_hash,logged_in FROM users where username='$id';");
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

        private function getDB() {
		return DB::connection();
	}
	
	
}

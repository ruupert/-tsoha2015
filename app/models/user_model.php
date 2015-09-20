<?php

class MovieModel extends BaseModel{
	public static $conn; 
	
	public function __construct() {
		// hyvinpa toimii juuh... huhhuh.
		//$conn = DB::connection();
	}

	public function __destruct() {
		$conn = null;
	}
	
	public static function all(){
 		
   		$conn = DB::connection();
		$query = $conn->prepare('SELECT id,name,description,duration,image FROM movie;');
		$query->execute();
		return array('movies' => $query->fetchAll());
		
	}
	public function find($id){
   		$conn = DB::connection();
		$query = $conn->prepare("SELECT name,description,duration,image FROM movie where id=$id;");
		$query->execute();
		
		return array('details' => $query->fetchAll());
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

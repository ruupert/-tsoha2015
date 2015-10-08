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
		$query = $conn->prepare("SELECT admin,created_at,username,pw_hash,login_hash FROM users where username='$id';");
		$query->execute();
		
		return $query->fetch();
	}
	
        private static function set_login($username, $login_hash) {

		$conn = DB::connection();
		$query = $conn->prepare("UPDATE user SET login_hash=':login_hash' where username=':username'");
		$query->bindParam(':login_hash',$login_hash);
		$query->bindParam('$username',$username');
		$query->execute();

	}

	public static function unset_login($username) {
		$this->set_loggin($username, md5(time() . "aGSDFGSDFG324TK4LRK6GS2DFk32flsd" . $username . "fsdlk32kfdslk214"));

	}

	public static function check_credentials($username, $password, $login_hash) {

		$user = $this->find($username);
		
		if (md5($username . $password)==$user['pw_hash']) {
			$this->set_login($username, $login_hash);
			return true;
		} else {
			return false;
		}
	}
	
	private function remove($id){
		
	}

        private function getDB() {
		return DB::connection();
	}
	
	
}

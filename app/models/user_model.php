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
	
        public static function set_login($username, $login_hash) {

		$conn = DB::connection();
		$query = $conn->prepare("UPDATE users SET login_hash='$login_hash' where username='$username'");
		$query->execute();
		
		
	}

	public static function unset_login($username) {
		$this->set_loggin($username, md5(time() . "aGSDFGSDFG324TK4LRK6GS2DFk32flsd" . $username . "fsdlk32kfdslk214"));

	}

	public static function check_credentials($username, $password, $login_hash) {

		$user = UserModel::find($username);

			
		if (md5($user['created_at'] . $password)==$user['pw_hash']) {
			UserModel::set_login($username, $login_hash);
			$_SESSION['username']=$username;
			$_SESSION['login_hash']=$login_hash;
			if ($user['admin']=true) {
				$_SESSION['admin_user']=true;
			}
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

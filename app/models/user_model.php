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
		self::set_login($username, md5(time() . "aGSDFGSDFG324TK4LRK6GS2DFk32flsd" . $username . "fsdlk32kfdslk214"));

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
	
	public static function create($username, $password, $name, $lastname) {
		$message = "";
		$path = "";
		$log_file = '/home/users/ruupert/sites/ruupert.kapsi.fi/www/tsoha2015/log/user.log';
                $conn = DB::connection();
		$query = $conn->prepare("SELECT (SELECT count(username) FROM users where username='$username') as username_count, (SELECT count(username) FROM users) as total_users");
		$query->execute();


 		$result = $query->fetch();

		if ($result[0] < 1) {
			$admin_user = 0; 
			if ($result[1] < 1) {
				// jos siis kayttajia ei ole, niin ensimmaisesta kayttajasta tulee automaattisesti admin.
				$admin_user = 1;
			} 

			$query = $conn->prepare("INSERT INTO users (username, name, lastname, pw_hash, admin) VALUES ('$username', '$name', '$lastname', '$password', $admin_user::boolean)");
			$query->execute();
			
//			$message = "Ilmiomainen suksee! Kokeilepa kirjautua.";
//			$path = BASE_PATH . "/user/login";			
//			header('Location: ' . $path);
		} else {
//			$path = BASE_PATH . "/user/register";
//			header('Location: ' . $path);
//			$message = "Epakelpo kayttajatunnus"; // koska mikaan muu ei voi feilaa... tai no voi mutta optimismia vahan tassa vaiheessa. 
		}
		
		
		return array('flash_message' => $message, 'path' => $path);
	} 

	private function remove($id){
		
	}

	
        private function getDB() {
		return DB::connection();
	}
	
	
}

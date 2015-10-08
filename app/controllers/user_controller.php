<?php

class UserController extends BaseController{

	
	public static function index(){
	
   		View::make('movie-list.html', UserModel::all());
	}
	
	
	public static function login(){

		$form_path = BASE_PATH . "/logincheck";

		if (!isset($_SERVER['HTTP_REFERER'])) {
			$_SESSION['login_referer'] = '/';
		} else {

			$_SESSION['login_referer'] = $_SERVER['HTTP_REFERER'];
		}

		$form = new \PFBC\Form("form-elements");
		$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $form_path, "method" => "post"));
		$form->addElement(new \PFBC\Element\Textbox("Tunnus", "username", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Password("Salasana", "password", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array("onclick" => "history.go(-1);")));
		
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => 'Kirjaudu'));

		
	}
	
	public static function login_check(){
		
                $login_hash = md5(time() . $_POST['username']);
		$result = UserModel::check_credentials($_POST['username'], $_POST['password'],$login_hash);
		if ($result == true) {
 			$_SESSION['login_hash'] = $login_hash;
			header('Location: ' . $_SESSION['login_referer']);
			
		}  else {
			$_SESSION['login_hash'] = null;
			header('Location: /login');
			
		}
	
		
	}
	
	
	public static function logout(){
	// logout ottaa jatkossa $username:n argumenttina ja tarkistaa, että onko kirjautunut ennen toimintaa.	
		$_SESSION['admin_user']=null;
		$_SESSION['logged_in']=json_encode(false);
		$_SESSION['username']=null;

		header('Location: ' . BASE_PATH);
		
		
	}

	public static function register(){
   		View::make('form-layout.html');
		
	}
	
	public static function create(){

	}
	public static function update(){
		
	}
	public static function destroy(){
		
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä
		$_POST['username']='admin';
		$_POST['password']='admin1';
		echo self::login_check();
		
	
		
	}
}

<?php

class UserController extends BaseController{

	
	public static function index(){
	
   		View::make('movie-list.html', UserModel::all());
	}
	
	
	public static function login(){

		$_SESSION['login_referer']=$_SERVER['HTTP_REFERER'];

		$form = new \PFBC\Form("form-elements");
		$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => "./logincheck"));
		$form->addElement(new \PFBC\Element\Textbox("Tunnus", "username", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Password("Salasana", "password", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array("onclick" => "history.go(-1);")));
		
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => 'Kirjaudu'));

		
	}
	public static function login_check(){

		// juuh elikkäs...
		
		
		
		$result = UserModel::find($_POST['username'], $_SESSION['login_hash']);

		if (md5($result['created_at'].$_POST['password'])==$result['pw_hash']) {
			$_SESSION['logged_in']=json_encode(true);
			$_SESSION['username']=json_encode($result['username']);
			if ($result['admin'] == json_encode(true)) {
				// vain linkkien nakyvyytta varten. toimintojen yhteydessa tarkistus tehdaan kuitenkin erikseen.
				$_SESSION['admin_user']=json_encode(true);
				$login_hash = md5(time() . $result['username']);

				UserModel::set_logged_in($login_hash);
				
				$_SESSION['login_hash'] = $login_hash;
				
			}
 			// laitetaan formin sivulla keksiin referrer ja ohjataan kayttaja takas mista alunperin lahti.	
			// testiksi johonkin polkuun.
			
 			header('Location: ' . $_SESSION['login_referer']);
				
		} else {
 			header('Location: ' . BASE_PATH . '/login');
		}
	
       }
	public static function logout(){
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

		require_once "app/models/movie_model.php";
 		$model = new MovieModel();
		Kint::dump($model);
		
#		View::make('movie-list.html', MovieModel::all());
		
		
	}
}

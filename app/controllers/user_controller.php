<?php

class UserController extends BaseController{

	
	public static function index(){

		require_once "app/models/user_model.php";
 		$model = new UserModel();
		
	
   		View::make('movie-list.html', $model->all());
	}
	
	
	public static function login(){



		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => "./logincheck"
		));
		$form->addElement(new \PFBC\Element\Textbox("Tunnus", "username", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Password("Salasana", "password", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
		

		// Kint::dump(array('form' => $form->render($returnHTML = true)));

		
   		View::make('user-login.html', array('form' => $form->render($returnHTML = true)));

		
	}
	public static function login_check(){
		require_once "app/models/user_model.php";
		$model = new UserModel();
		$result = $model->find($_POST['username']);

		if (md5($result['created_at'].$_POST['password'])==$result['pw_hash']) {
			$_SESSION['logged']=true;
			if ($result['admin'] == true) {
				// vain linkkien nakyvyytta varten. toimintojen yhteydessa tarkistus tehdaan kuitenkin erikseen.
				$_SESSION['admin']=true;
			}
		// laitetaan formin sivulla keksiin referrer ja ohjataan kayttaja takas mista alunperin lahti.	
//		Redirect::to("/", "Paasit onnistuneesti kirjautumaan");	
		} else {
//			Redirect::to("/login", "Epäkelpo tunnus tai salasana");
		}
	
       }
	public static function logout(){
   		View::make('user-logout.html');
		
	}
	public static function register(){
   		View::make('user-register.html');
		
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

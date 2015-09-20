<?php

class UserController extends BaseController{

	
	public static function index(){

		require_once "app/models/user_model.php";
 		$model = new UserModel();
		
		
   		View::make('movie-list.html', $model->all());
	}
	
	
	public static function login(){

		$options = array("Option #1", "Option #2", "Option #3");
		
		$form = new \PFBC\Form("form-elements");
		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery")
		));
		$form->addElement(new \PFBC\Element\Textbox("Tunnus", "Textbox"));
		$form->addElement(new \PFBC\Element\Password("Salasana", "Password"));
		$form->addElement(new \PFBC\Element\Captcha());
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Cancel", "button", array(
			"onclick" => "history.go(-1);"
		)));
		

		// Kint::dump(array('form' => $form->render($returnHTML = true)));

		
   		View::make('user-login.html', array('form' => $form->render($returnHTML = true)));
		
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

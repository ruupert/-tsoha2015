<?php

class MovieController extends BaseController{

	
	public static function index(){
		
		View::make('movie-list.html', array_merge(MovieModel::all('allfields'), array('links' => parent::addnew_link('movie'))));
		
	}
	
	public static function show($id){

		View::make('movie-show.html',array('movie' => MovieModel::find($id),'links' => parent::generate_links()));
		
    		
	}	  
	
	public static function edit($id){
		if (parent::is_admin()==true) {
			$result = MovieModel::find($id);
			$result = $result['details'][0];
			
			
			
			
			$form = new \PFBC\Form("form-elements");
			
			$form->configure(array(
				"prevent" => array("bootstrap", "jQuery"), "action" => "./update"
			));
			$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1, "value" => $result['name'])));
			$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1, "value" => $result['description'])));
			$duration = $result['duration'];
			$form->addElement(new \PFBC\Element\HTML("<p>Kesto $duration (ei implementoitu, koska muutoksen validointi kaikkien teatterien naytoksien kanssa menee jootiks)</p>"));
			$form->addElement(new \PFBC\Element\HTML("<img src='data:image/png;base64," . $result['image'] . "'/>"));
			$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
			$form->addElement(new \PFBC\Element\Button);
			$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
				"onclick" => "history.go(-1);"
			)));
			
			
   			View::make('form-layout.html', array('form' => $form->render($returnHTML = true)));
		}
	}
	public static function add(){
		if (parent::is_admin()==true) {
			$form_action = BASE_PATH . "/movie/create";
			
			$form = new \PFBC\Form("form-elements");
			
			$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => $form_action, "method" => "post"
			));
			
			$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1)));
			$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1)));
			$form->addElement(new \PFBC\Element\Number("Kesto", "duration", array("required" => 1)));
			$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
			$form->addElement(new \PFBC\Element\Button);
			$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
				"onclick" => "history.go(-1);"
			)));
   			View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => 'Uusi elokuva'));
		}
	}
	
	public static function create(){

		if (parent::is_admin()==true) {
		
			$obj = new MovieModel();
			$obj->add($_POST['name'],$_POST['description'],$_POST['duration'],$_FILES['image']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
		} else {

		}
		
	}
	public static function update($id){
		
		if (parent::is_admin()==true) {
		
			$obj = new MovieModel();
			$obj->save($id,$_POST['name'],$_POST['description'],$_FILES['image']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			Redirect::to("/movie/$id/show");

		} else {

		}
	     
	}
	public static function destroy($id){
		if (parent::is_admin()==true) {
			
			MovieModel::remove($id);
			
		} 
		Redirect::to('/movie');
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

		
		
		
	}
}

<?php

class MovieController extends BaseController{

	public static function index(){

   		View::make('movie-list.html', MovieModel::all());
	}
	
	public static function show($id){

		parent::generate_links();
		View::make('movie-show.html',MovieModel::find($id));
		
    		
	}	  
	
	public static function edit($id){
		$result = MovieModel::find($id);
		$result = $result['details'][0];
		
		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => "./update"
		));
		$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1, "value" => $result['name'])));
		$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1, "value" => $result['description'])));
		$form->addElement(new \PFBC\Element\Number("Kesto", "duration", array("required" => 1, "value" => $result['duration'])));
		$form->addElement(new \PFBC\Element\HTML("<img src='" . $result['image'] . "'/>"));
		$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true)));
		
	}
	public static function add(){

		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => "./update"
		));
		$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Number("Kesto", "duration", array("required" => 1)));
		$form->addElement(new \PFBC\Element\HTML("<img src='" . $result['image'] . "'/>"));
		$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true)));
		
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

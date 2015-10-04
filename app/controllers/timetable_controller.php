<?php

class TimetableController extends BaseController{

	
	public static function index(){
		
		View::make('timetable-list.html', array_merge(TimetableModel::all('allfields'), array('links' => parent::addnew_link('timetable'))));
		
	}
	
	public static function show($id){


		View::make('timetable-show.html',array('timetable' => TimetableModel::find($id),'links' => parent::generate_links()));
		
    		
	}	  
	
	public static function edit($id){
		$result = TimetableModel::find($id);
		$result = $result['details'][0];


		
		
		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => "./update"
		));
		$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1, "value" => $result['name'])));
		$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1, "value" => $result['description'])));
		$form->addElement(new \PFBC\Element\Number("Kesto", "duration", array("required" => 1, "value" => $result['duration'])));
		$form->addElement(new \PFBC\Element\HTML("<img src='data:image/png;base64," . $result['image'] . "'/>"));
		$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
		
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true)));
		
	}
	public static function add(){
		$form_action = BASE_PATH . "/timetable/create";

		$movies = MovieModel::all('name');
		
//		$movie_options = array();
//		$movie_values = array();
		$movie_options = "";
		
		foreach ($movies['movies'] as $movie) {

			
			$id  = $movie['id'];
			$name = $movie['name'];
			$movie_options = $movie_options .  "<option value='$id'>$name</option>";

		}

		$theaters = TheaterModel::all('name');

		$theater_options = "";
		foreach ($theaters['theaters'] as $theater) {
			$id = $theater['id'];
			$name = $theater['name'];
			$theater_options = $theater_options . "<option value='$id'>$name</option>";

		}

		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => $form_action, "method" => "post"
		));

		$form->addElement(new \PFBC\Element\HTML("<select name='Movie' id='form-elements-element-0'>" . $movie_options . "</select>"));
		$form->addElement(new \PFBC\Element\HTML("<select name='Theater' id='form-elements-element-0'>" . $theater_options . "</select>"));

		$form->addElement(new \PFBC\Element\DateTime("Näytöksen alkuajankohta:", "DateTime"));	
		// myohemmin.. 
//		$form->addElement(new \PFBC\Element\Select("Elokuva:", "Movie", $movie_options, $movie_values));
//		$form->addElement(new \PFBC\Element\Select("Teatteri:", "Theater", $theater_options));

		
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => 'Uusi elokuva'));
		
	}
	
	public static function create(){

		if (parent::is_admin()==true) {
		
			$obj = new TimetableModel();
			$obj->add($_POST['Movie'],$_POST['Theater'],$_POST['DateTime']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
		} else {

		}
		
	}
	public static function update($id){
		
		if (parent::is_admin()==true) {
		
			$obj = new TimetableModel();
			$obj->save($id,$_POST['Movie'],$_POST['Theater'],$_POST['DateTime']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			Redirect::to("/movie/$id/show");

		} else {

		}
	     
	}
	public static function destroy($id){
		if (parent::is_admin()==true) {
			$obj = new TimetableModel();
			$obj->remove($id);
			Redirect::to('/movie');

		} else {
			Redirect::to('/movie');

		}
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

		
		
		
	}
}

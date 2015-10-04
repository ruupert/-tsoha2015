<?php

class TheaterController extends BaseController{

	
	public static function index(){
		
		View::make('theater-list.html', array_merge(TheaterModel::all('allfields'), array('links' => parent::addnew_link('theater'))));
		
	}
	
	public static function show($id){
		View::make('theater-show.html',array('theater' => TheaterModel::find($id),'links' => parent::generate_links()));
		
    		
	}	  
	
	public static function edit($id){
		$result = TheaterModel::find($id);
		$result = $result['details'][0];


		
		
		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => "./update"
		));
		$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1, "value" => $result['name'])));
		$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1, "value" => $result['description'])));
		$form->addElement(new \PFBC\Element\Number("Istumapaikkoja", "seats", array("required" => 1, "value" => $result['seats'])));
		$form->addElement(new \PFBC\Element\HTML("<img src='data:image/png;base64," . $result['image'] . "'/>"));
		$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
		
		
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true)));
		
	}
	public static function add(){
		$form_action = BASE_PATH . "/theater/create";
		
		$form = new \PFBC\Form("form-elements");

		$form->configure(array(
			"prevent" => array("bootstrap", "jQuery"), "action" => $form_action, "method" => "post"
		));

		$form->addElement(new \PFBC\Element\Textbox("Nimi", "name", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Textarea("Kuvaus", "description", array("required" => 1)));
		$form->addElement(new \PFBC\Element\Number("Istumapaikkoja", "seats", array("required" => 1)));
		$form->addElement(new \PFBC\Element\File("Kuvatiedosto (.png tai .jpg)", "image"));
		$form->addElement(new \PFBC\Element\Button);
		$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
			"onclick" => "history.go(-1);"
		)));
   		View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => 'Uusi teatteri'));
		
	}
	
	public static function create(){

		if (parent::is_admin()==true) {
		
			$obj = new TheaterModel();
			$obj->add($_POST['name'],$_POST['description'],$_POST['seats'],$_FILES['image']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
		} else {

		}
		
	}
	public static function update($id){
		
		if (parent::is_admin()==true) {
		
			$obj = new TheaterModel();
			$obj->save($id,$_POST['name'],$_POST['description'],$_POST['seats'],$_FILES['image']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			Redirect::to("/theater/$id/show");

		} else {

		}
	     
	}
	public static function destroy($id){
		if (parent::is_admin()==true) {
			$obj = new TheaterModel();
			$obj->remove($id);
			Redirect::to('/theater');

		} else {
			Redirect::to('/theater');

		}
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

		
		
		
	}
}

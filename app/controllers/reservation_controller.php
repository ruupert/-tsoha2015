<?php

class ReservationController extends BaseController{

	
	public static function index(){
		if (parent::check_logged_in()==true) {
			$user = parent::get_user_logged_in();
			$user_id = parent::get_user_id($user);
			if (parent::is_admin()) {
				Kint::dump(ReservationModel::all('allfields', parent::get_user_logged_in()));
				View::make('reservation-list.html', ReservationModel::all('allfields', parent::get_user_logged_in()));
				
			} else {
				Kint::dump(ReservationModel::all('userfields', $user_id));
				View::make('reservation-list.html', ReservationModel::all('userfields', $user_id));
				
			}
		}
	}
	
	public static function show($id){


		View::make('reservation-show.html',array('timetable' => ReservationModel::find($id),'links' => parent::generate_links()));
		
    		
	}	  
	
	public static function edit($id){
                if (parent::check_logged_in()==true) {
			$result = ReservationModel::find($id);
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
	}
	public static function add($id){
		if (parent::check_logged_in()==true) {
			// paska, koska hakee myos kuvat... 
			$timetable = ReservationModel::find($id);
			$movie_name = $timetable['details'][0]['movie_name'];
			$theater_name = $timetable['details'][0]['theater_name'];
			$start_at = $timetable['details'][0]['start_at'];
			
			$reservations = ReservationModel::get_available_seats($id);

			$reserved_seats = $reservations['result'][0]['quantity'];
			$total_seats = $reservations['result'][0]['seats'];
			
			$form_action = BASE_PATH . "/reservation/create";

			$form = new \PFBC\Form("form-elements");
			$form->addElement(new \PFBC\Element\Hidden("timetable_id", "$id"));
			$form->addElement(new \PFBC\Element\Number("Istumapaikkoja ", "seats", array("required" => 1)));
			$form->addElement(new \PFBC\Element\HTML("Varattu $reserved_seats / $total_seats"));
			$form->configure(array(
				"prevent" => array("bootstrap", "jQuery"), "action" => $form_action, "method" => "post"
			));
			

			
			
			$form->addElement(new \PFBC\Element\Button);
			$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
				"onclick" => "history.go(-1);"
		)));

			View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => "Varaus elokuvan $movie_name naytokseen $start_at teatterissa $theater_name"));
			
		}
	}	
	public static function create(){
		$_POST['user']=parent::get_user_logged_in();
		if (parent::check_logged_in()==true) {
			ReservationModel::add($_POST['timetable_id'],$_POST['seats'], $_POST['user']);
			

		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
		
	}
	public static function update($id){
		
		if (parent::is_admin()==true) {
		
			$obj = new ReservationModel();
			$obj->save($id,$_POST['Movie'],$_POST['Theater'],$_POST['DateTime']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			Redirect::to("/movie/$id/show");

		}
	     
	}
	public static function destroy($id){
		if (parent::is_admin()==true) {
			$obj = new ReservationModel();
			$obj->remove($id);

		}
		Redirect::to('/timetable');
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

                $start_at = new DateTime('08.10.2015 23:33');
		$end_at = $start_at;

		
	 	$duration = "'P" . 54 . "I'";
		
		
		$end_at->add($end_at, new DateInterval("$duration"));

		Kint::dump($duration);
		Kint::dump($start_at);
		Kint::dump($end_at);
		
		
	}
}

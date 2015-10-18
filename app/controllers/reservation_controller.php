<?php

class ReservationController extends BaseController{

	
	public static function index(){
		if (parent::check_logged_in()==true) {
			$user = parent::get_user_logged_in();
			$user_id = parent::get_user_id($user);
			if (parent::is_admin()) {
				View::make('reservation-list.html', ReservationModel::all('allfields', parent::get_user_logged_in()));
				
			} else {
				View::make('reservation-list.html', ReservationModel::all('userfields', $user_id));
				
			}
		}
	}
	
	public static function show($id){

		View::make('reservation-show.html',array('reservation' => ReservationModel::find($id),'links' => parent::generate_reservation_links()));
		
    		
	}	  
	
	public static function edit($id){
                if (parent::check_logged_in()==true) {
			$result = ReservationModel::find($id);
			$result = $result['details'][0];

			$movie_name = $result[6];
			$theater_name = $result[7];
			$start_at = $result['start_at'];
			$timetable_id = $result['timetable_id'];
			$reservations = ReservationModel::get_available_seats($result['timetable_id']);

			$reserved_seats = $result['quantity'];
			$total_reserved_seats = $reservations['result'][0]['quantity'];
			$total_seats = $reservations['result'][0]['seats'];
			$form_action = BASE_PATH . "/reservation/$id/update";

			$form = new \PFBC\Form("form-elements");
			$form->addElement(new \PFBC\Element\Hidden("timetable_id", "$timetable_id"));
			$form->addElement(new \PFBC\Element\Number("Istumapaikkoja ", "seats", array("required" => 1, "value" => $reserved_seats)));
			$form->addElement(new \PFBC\Element\HTML("Varattu $total_reserved_seats / $total_seats"));
			$form->configure(array(
				"prevent" => array("bootstrap", "jQuery"), "action" => $form_action, "method" => "post"
			));
			

			
			
			$form->addElement(new \PFBC\Element\Button);
			$form->addElement(new \PFBC\Element\Button("Takaisin", "button", array(
				"onclick" => "history.go(-1);"
		)));

			View::make('form-layout.html', array('form' => $form->render($returnHTML = true), 'page_title' => "Varauksen muokkaus elokuvan movie_name naytokseen $start_at teatterissa $theater_name"));


		}
	}
	public static function add($id){
		if (parent::check_logged_in()==true) {
			// paska, koska hakee myos kuvat... 
			$timetable = TimetableModel::find($id);
			$movie_name = $timetable['details'][0][6];
			$theater_name = $timetable['details'][0][7];
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

		if (parent::check_logged_in()==true) {
			ReservationModel::add($_POST['timetable_id'],$_POST['seats'], parent::get_user_logged_in());
			

		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
		
	}
	public static function update($id){
		
		if (parent::check_logged_in()==true) {
		
			$obj = new ReservationModel();
			$obj->save($id,$_POST['seats'], parent::get_user_logged_in(), parent::is_admin(), $_POST['timetable_id'] );
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		//	Redirect::to("/movie/$id/show");

		}
	     
	}
	public static function destroy($id){
		if (parent::check_logged_in()==true) {
			$user = UserModel::find(parent::get_user_logged_in());
			$reservation = ReservationModel::find($id);
			if ($reservation['details'][0]['user_id']==$user['id']) {
				$obj = new ReservationModel();
				$obj->remove($id);
			} elseif (parent::is_admin()==true) {
				$obj = new ReservationModel();
				$obj->remove($id);
			}
		}
		Redirect::to('/reservation');
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

		
		
	}
}

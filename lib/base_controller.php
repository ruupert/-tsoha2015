<?php

  class BaseController{
	  
	  public static function get_user_logged_in(){
		  if (isset($_SESSION['username'])) {
			  return $_SESSION['username'];
		  } else {
			  return null;
		  }
		  
	  }
	  
	  public static function check_logged_in(){
		  if (!isset($_SESSION['login_hash'])) {
			  return false;
		  } else {
			  $user = UserModel::find($_SESSION['username']);
			  if ($user['login_hash']==$_SESSION['login_hash']) {
			  
				  return true;
			  } else {
				  false;
			  }
		  }
	  }
	  public static function is_admin(){
		  if (self::check_logged_in()==true) {
			  if (isset($_SESSION['admin_user'])) {
				  if ($_SESSION['admin_user']==true) {
					  return true;
				  } else {
					  return false;
				  }
			  } else {
				  
			  }
		  }
	  }
	  
	  public static function generate_links() {

		  // tahan voisi teha switch-casen.. riippuu missä ollaan.. tai sitten näitä linkkejä saa tässäkin jotenkin
		  // kivasti niinku railsissa.. jotain path_to sössönsöö..
 		  if (self::is_admin()==true) {
			  return array('link' => array('url' => $_SERVER['HTTP_REFERER'], 'name' => 'Takaisin'),
				                 array('url' => './edit', 'name' => 'Muokkaa'),
				                 array('url' => './destroy', 'name' => 'Poista'));

		  } else {
			  return array('link' => array('url' => $_SERVER['HTTP_REFERER'], 'name' => 'Takaisin'));
		  }
		  
	  }
	  public static function addnew_link($controller) {
  		  if (self::is_admin()==true) {

			  // pitaa kattoa miten naita urleja oikein saa parent::urlFor('/movie/new')
			  // eli paskasti nyt staattista:
			  return array('link' => array('url' => BASE_PATH . '/' . $controller . '/new' , 'name' => 'Uusi'));
		  }
	  }	  
  }

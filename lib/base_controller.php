<?php

  class BaseController{
	  public $navlinks;
	  
	  public static function get_user_logged_in(){
		  if (isset($_SESSION['username'])) {
			  return $_SESSION['username'];
		  } else {
			  return null;
		  }
		  
		  
	  }
	  
	  public static function check_logged_in(){
		  if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) {
			  Redirect::to('/login', 'Kirjautu!');
		  }
			  
	  }
	  
	  public static function generate_links() {

		  $app = new \Slim\Slim();
		  
		  $req = $app->request;
//		  $root_uri = $req->getRootUri();
//		  $resource_uri = $req->getResourceUri();
		  #$navArray = array('link' => array('url' => $req->getReferrer(), 0 => $req->getReferrer(), 'name' => 'Back', 1 => 'Back');
	  
#	          $navlinks = "<a href='" . $req->getReferrer() . "'>Back</a>";
		  

		  
	  }
	  
  }

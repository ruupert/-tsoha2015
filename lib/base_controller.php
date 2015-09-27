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
		  if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) {
			  return true;
		  }			  

	  }
	  
	  public static function generate_links() {

		  
		  
	  }
	  
  }

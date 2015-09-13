<?php

  class MovieController extends BaseController{
	
    public static function index(){
	    
     	   $conn = DB::connection();
	   $query = $conn->prepare('SELECT id,name,description,duration,image FROM movie;');
	   $query->execute();
	   $movies = $query->fetchAll();
	   $conn = null;

	    
    	  // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('movie-list.html', array('movies' => $movies));
    }

    public static function show($id){

     	   $conn = DB::connection();
	   $query = $conn->prepare("SELECT name,description,duration,image FROM movie where id=$id;");
	   $query->execute();
	   $details = $query->fetchAll();
	   $conn = null;
#           $links = CrudLinkhelper::show_links();   

	    View::make('movie-show.html', array('details' => $details));

    	  
    }	  

    public static function edit(){
   	  View::make('movie-edit.html');
    
    }
    public static function add(){
   	  View::make('movie-new.html');
    
    }

    public static function create(){

    }
    public static function update(){
    
    }
    public static function destroy(){
    
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      echo 'Hello World!';
    }
  }
